<?php

namespace App\Services;

use App\Services\TextPreprocessor;

class ConfusionMatrix
{
    /**
     * Hitung confusion matrix HANYA untuk dokumen yang memiliki term sama dengan query
     */
    public static function calculateForSearch(
        array $cosineResults, 
        array $jaccardResults, 
        array $allDocuments, 
        string $query, 
        float $predictionThreshold = 0.1,
        float $groundTruthThreshold = 0.25
    ): array {
        // Preprocess query untuk mendapatkan token
        $queryTokens = TextPreprocessor::preprocessText($query);
        
        if (empty($queryTokens)) {
            return self::emptyConfusionMatrix();
        }
        
        // FILTER: Ambil hanya dokumen yang memiliki minimal 1 term yang sama dengan query
        $filteredDocuments = self::filterDocumentsByQueryTokens($allDocuments, $queryTokens);
        
        if (empty($filteredDocuments)) {
            return self::emptyConfusionMatrix();
        }
        
        // Ground truth hanya untuk dokumen yang difilter
        $groundTruth = self::determineGroundTruth($filteredDocuments, $query, $groundTruthThreshold);
        
        // Hitung jumlah dokumen yang ditemukan di masing-masing algoritma
        $cosineFound = self::countDocumentsAboveThreshold($cosineResults, $predictionThreshold, 'cosine');
        $jaccardFound = self::countDocumentsAboveThreshold($jaccardResults, $predictionThreshold, 'jaccard');
        
        // Total unik dokumen yang ditemukan
        $uniqueFound = self::getUniqueDocumentsFound($cosineResults, $jaccardResults, $predictionThreshold);
        
        // Inisialisasi confusion matrix
        $cosineMatrix = [
            'tp' => 0, 'fp' => 0, 'tn' => 0, 'fn' => 0,
            'precision' => 0, 'recall' => 0, 'f1' => 0, 'accuracy' => 0
        ];
        
        $jaccardMatrix = [
            'tp' => 0, 'fp' => 0, 'tn' => 0, 'fn' => 0,
            'precision' => 0, 'recall' => 0, 'f1' => 0, 'accuracy' => 0
        ];
        
        $totalAnalyzedDocs = count($filteredDocuments); // Hanya dokumen yang memiliki term query
        $totalRelevant = array_sum($groundTruth);
        
        // Buat mapping ID ke index untuk dokumen yang difilter
        $idToIndex = self::createDocumentIndex($filteredDocuments);
        
        // Analisis Cosine untuk dokumen yang difilter
        $cosineMatrix = self::analyzeAlgorithm(
            $cosineResults, 
            $filteredDocuments, 
            $groundTruth, 
            $idToIndex, 
            $predictionThreshold, 
            'cosine'
        );
        
        // Analisis Jaccard untuk dokumen yang difilter
        $jaccardMatrix = self::analyzeAlgorithm(
            $jaccardResults, 
            $filteredDocuments, 
            $groundTruth, 
            $idToIndex, 
            $predictionThreshold, 
            'jaccard'
        );
        
        // Tentukan winner
        $winner = self::determineWinner($cosineMatrix, $jaccardMatrix);
        
        // Hitung average scores hanya untuk dokumen yang ditemukan
        $avgCosine = self::calculateAverageScore($cosineResults, 'cosine');
        $avgJaccard = self::calculateAverageScore($jaccardResults, 'jaccard');
        
        return [
            'cosine' => $cosineMatrix,
            'jaccard' => $jaccardMatrix,
            'winner' => $winner,
            'total_documents' => $totalAnalyzedDocs, // Jumlah dokumen yang dianalisis
            'total_filtered_documents' => count($allDocuments), // Jumlah total dokumen sesuai filter
            'total_relevant' => $totalRelevant,
            'total_not_relevant' => $totalAnalyzedDocs - $totalRelevant,
            'average_scores' => [
                'cosine' => round($avgCosine, 4),
                'jaccard' => round($avgJaccard, 4)
            ],
            'search_summary' => [
                'cosine_found' => $cosineFound,
                'jaccard_found' => $jaccardFound,
                'unique_found' => $uniqueFound,
                'prediction_threshold' => $predictionThreshold,
                'ground_truth_threshold' => $groundTruthThreshold,
                'query_tokens_count' => count($queryTokens)
            ],
            'threshold' => $predictionThreshold,
            'ground_truth_threshold' => $groundTruthThreshold,
            'confidence_level' => self::calculateConfidenceLevel($cosineMatrix, $jaccardMatrix),
            'analysis_info' => [
                'documents_analyzed' => $totalAnalyzedDocs,
                'documents_filtered_out' => count($allDocuments) - $totalAnalyzedDocs,
                'analysis_criteria' => 'Dokumen dengan minimal 1 term yang sama dengan query'
            ]
        ];
    }
    
    /**
     * Filter dokumen hanya yang memiliki minimal 1 term yang sama dengan query
     */
    private static function filterDocumentsByQueryTokens(array $documents, array $queryTokens): array
    {
        if (empty($queryTokens)) {
            return [];
        }
        
        $filtered = [];
        
        foreach ($documents as $document) {
            $docTokens = $document['tokens'] ?? [];
            
            // Cek apakah ada intersection antara token dokumen dan query
            $intersection = array_intersect($docTokens, $queryTokens);
            
            if (!empty($intersection)) {
                // Dokumen memiliki minimal 1 term yang sama dengan query
                $filtered[] = $document;
            }
        }
        
        return $filtered;
    }
    
    /**
     * Kosongkan confusion matrix
     */
    private static function emptyConfusionMatrix(): array
    {
        return [
            'cosine' => [
                'tp' => 0, 'fp' => 0, 'tn' => 0, 'fn' => 0,
                'precision' => 0, 'recall' => 0, 'f1' => 0, 'accuracy' => 0
            ],
            'jaccard' => [
                'tp' => 0, 'fp' => 0, 'tn' => 0, 'fn' => 0,
                'precision' => 0, 'recall' => 0, 'f1' => 0, 'accuracy' => 0
            ],
            'winner' => 'draw',
            'total_documents' => 0,
            'total_filtered_documents' => 0,
            'total_relevant' => 0,
            'total_not_relevant' => 0,
            'average_scores' => ['cosine' => 0, 'jaccard' => 0],
            'search_summary' => [],
            'threshold' => 0.1,
            'ground_truth_threshold' => 0.2,
            'confidence_level' => 'low',
            'analysis_info' => [
                'documents_analyzed' => 0,
                'documents_filtered_out' => 0,
                'analysis_criteria' => 'No query tokens'
            ]
        ];
    }
    
    /**
     * Tentukan ground truth berdasarkan query
     */
    private static function determineGroundTruth(array $documents, string $query, float $relevanceThreshold = 0.1): array
    {
        $groundTruth = [];
        
        if (empty($documents) || empty($query)) {
            return $groundTruth;
        }
        
        // Preprocess query untuk mendapatkan keywords
        $queryTokens = TextPreprocessor::preprocessText($query);
        
        if (empty($queryTokens)) {
            foreach ($documents as $index => $doc) {
                $groundTruth[$index] = false;
            }
            return $groundTruth;
        }
        
        foreach ($documents as $index => $doc) {
            $docTokens = $doc['tokens'] ?? [];
            
            if (empty($docTokens)) {
                $groundTruth[$index] = false;
                continue;
            }
            
            // Hitung persentase token query yang ada di dokumen
            $intersection = array_intersect($queryTokens, $docTokens);
            $overlapCount = count($intersection);
            $overlapPercentage = $overlapCount / max(1, count($queryTokens));
            
            // Dokumen dianggap relevan jika overlap ≥ threshold
            $groundTruth[$index] = ($overlapPercentage >= $relevanceThreshold);
        }
        
        return $groundTruth;
    }
    
    /**
     * Analisis satu algoritma
     */
    private static function analyzeAlgorithm(
        array $results, 
        array $documents, 
        array $groundTruth, 
        array $idToIndex, 
        float $threshold,
        string $scoreKey
    ): array {
        $matrix = [
            'tp' => 0, 'fp' => 0, 'tn' => 0, 'fn' => 0,
            'precision' => 0, 'recall' => 0, 'f1' => 0, 'accuracy' => 0
        ];
        
        $totalDocs = count($documents);
        $foundIds = [];
        
        // Analisis dokumen yang ditemukan
        foreach ($results as $result) {
            $docId = $result['id'];
            $foundIds[] = $docId;
            
            if (isset($idToIndex[$docId])) {
                $docIndex = $idToIndex[$docId];
                $isRelevant = $groundTruth[$docIndex];
                $score = $result[$scoreKey] ?? $result['similarity'] ?? 0;
                $isPredicted = $score >= $threshold;
                
                if ($isRelevant && $isPredicted) $matrix['tp']++;
                elseif (!$isRelevant && $isPredicted) $matrix['fp']++;
                elseif ($isRelevant && !$isPredicted) $matrix['fn']++;
                else $matrix['tn']++;
            }
        }
        
        // Analisis dokumen yang tidak ditemukan (tidak muncul di hasil)
        foreach ($documents as $index => $doc) {
            if (!in_array($doc['id'], $foundIds)) {
                $isRelevant = $groundTruth[$index];
                // Dokumen tidak ditemukan = diprediksi tidak relevan
                if ($isRelevant) {
                    $matrix['fn']++;
                } else {
                    $matrix['tn']++;
                }
            }
        }
        
        // Hitung metrics
        return self::calculateMetrics($matrix);
    }
    
    /**
     * Hitung metrics (precision, recall, f1, accuracy)
     */
    private static function calculateMetrics(array $matrix): array
    {
        // Precision = TP / (TP + FP)
        if (($matrix['tp'] + $matrix['fp']) > 0) {
            $matrix['precision'] = $matrix['tp'] / ($matrix['tp'] + $matrix['fp']);
        } else {
            $matrix['precision'] = 0;
        }
        
        // Recall = TP / (TP + FN)
        if (($matrix['tp'] + $matrix['fn']) > 0) {
            $matrix['recall'] = $matrix['tp'] / ($matrix['tp'] + $matrix['fn']);
        } else {
            $matrix['recall'] = 0;
        }
        
        // F1-Score = 2 * (precision * recall) / (precision + recall)
        if (($matrix['precision'] + $matrix['recall']) > 0) {
            $matrix['f1'] = 2 * ($matrix['precision'] * $matrix['recall']) / 
                            ($matrix['precision'] + $matrix['recall']);
        } else {
            $matrix['f1'] = 0;
        }
        
        // Accuracy = (TP + TN) / (TP + TN + FP + FN)
        $total = $matrix['tp'] + $matrix['tn'] + $matrix['fp'] + $matrix['fn'];
        if ($total > 0) {
            $matrix['accuracy'] = ($matrix['tp'] + $matrix['tn']) / $total;
        } else {
            $matrix['accuracy'] = 0;
        }
        
        return $matrix;
    }
    
    /**
     * Tentukan pemenang berdasarkan F1-Score (utama) dan metrics lainnya
     */
    private static function determineWinner(array $cosineMatrix, array $jaccardMatrix): string
    {
        // Prioritaskan F1-Score karena balance antara precision dan recall
        if ($cosineMatrix['f1'] > $jaccardMatrix['f1'] + 0.05) {
            return 'cosine';
        } elseif ($jaccardMatrix['f1'] > $cosineMatrix['f1'] + 0.05) {
            return 'jaccard';
        }
        
        // Jika F1-Score hampir sama, lihat precision
        if (abs($cosineMatrix['precision'] - $jaccardMatrix['precision']) > 0.1) {
            if ($cosineMatrix['precision'] > $jaccardMatrix['precision']) {
                return 'cosine';
            } else {
                return 'jaccard';
            }
        }
        
        // Jika masih sama, lihat recall
        if (abs($cosineMatrix['recall'] - $jaccardMatrix['recall']) > 0.1) {
            if ($cosineMatrix['recall'] > $jaccardMatrix['recall']) {
                return 'cosine';
            } else {
                return 'jaccard';
            }
        }
        
        return 'draw';
    }
    
    /**
     * Hitung tingkat keyakinan
     */
    private static function calculateConfidenceLevel(array $cosineMatrix, array $jaccardMatrix): string
    {
        $f1Diff = abs($cosineMatrix['f1'] - $jaccardMatrix['f1']);
        
        if ($f1Diff > 0.15) {
            return 'high';
        } elseif ($f1Diff > 0.05) {
            return 'medium';
        } else {
            return 'low';
        }
    }
    
    /**
     * Hitung jumlah dokumen di atas threshold untuk masing-masing algoritma
     */
    private static function countDocumentsAboveThreshold(array $results, float $threshold, string $scoreKey): int
    {
        $count = 0;
        foreach ($results as $result) {
            $score = $result[$scoreKey] ?? $result['similarity'] ?? 0;
            if ($score >= $threshold) {
                $count++;
            }
        }
        return $count;
    }
    
    /**
     * Hitung dokumen unik yang ditemukan
     */
    private static function getUniqueDocumentsFound(array $cosineResults, array $jaccardResults, float $threshold): int
    {
        $foundIds = [];
        
        foreach ($cosineResults as $result) {
            if (($result['cosine'] ?? 0) >= $threshold) {
                $foundIds[$result['id']] = true;
            }
        }
        
        foreach ($jaccardResults as $result) {
            if (($result['jaccard'] ?? 0) >= $threshold) {
                $foundIds[$result['id']] = true;
            }
        }
        
        return count($foundIds);
    }
    
    /**
     * Buat mapping ID dokumen ke index
     */
    private static function createDocumentIndex(array $documents): array
    {
        $index = [];
        foreach ($documents as $idx => $doc) {
            $index[$doc['id']] = $idx;
        }
        return $index;
    }
    
    /**
     * Hitung average score untuk algoritma
     */
    private static function calculateAverageScore(array $results, string $scoreKey): float
    {
        if (empty($results)) {
            return 0;
        }
        
        $total = 0;
        $count = 0;
        
        foreach ($results as $result) {
            $score = $result[$scoreKey] ?? 0;
            if ($score > 0) {
                $total += $score;
                $count++;
            }
        }
        
        return $count > 0 ? $total / $count : 0;
    }
    
    /**
     * Format metrics untuk ditampilkan
     */
    public static function formatMetrics(array $matrix, int $decimals = 4): array
    {
        return [
            'tp' => $matrix['tp'],
            'fp' => $matrix['fp'],
            'tn' => $matrix['tn'],
            'fn' => $matrix['fn'],
            'precision' => round($matrix['precision'], $decimals),
            'recall' => round($matrix['recall'], $decimals),
            'f1' => round($matrix['f1'], $decimals),
            'accuracy' => round($matrix['accuracy'], $decimals),
            'precision_percent' => round($matrix['precision'] * 100, 1),
            'recall_percent' => round($matrix['recall'] * 100, 1),
            'f1_percent' => round($matrix['f1'] * 100, 1),
            'accuracy_percent' => round($matrix['accuracy'] * 100, 1)
        ];
    }
    
    /**
     * Generate summary report
     */
    public static function generateReport(array $confusionMatrix): array
    {
        $cosine = $confusionMatrix['cosine'];
        $jaccard = $confusionMatrix['jaccard'];
        
        $report = [
            'summary' => [
                'total_documents' => $confusionMatrix['total_documents'],
                'total_filtered_documents' => $confusionMatrix['total_filtered_documents'] ?? 0,
                'total_relevant' => $confusionMatrix['total_relevant'] ?? 0,
                'total_not_relevant' => $confusionMatrix['total_not_relevant'] ?? 0,
                'prediction_threshold' => $confusionMatrix['threshold'],
                'ground_truth_threshold' => $confusionMatrix['ground_truth_threshold'] ?? 0.2,
                'winner' => $confusionMatrix['winner'],
                'confidence_level' => $confusionMatrix['confidence_level'] ?? 'medium',
                'analysis_info' => $confusionMatrix['analysis_info'] ?? []
            ],
            'search_summary' => $confusionMatrix['search_summary'] ?? [],
            'cosine' => self::formatMetrics($cosine),
            'jaccard' => self::formatMetrics($jaccard),
            'comparison' => [
                'better_precision' => $cosine['precision'] > $jaccard['precision'] ? 'cosine' : 
                                    ($cosine['precision'] < $jaccard['precision'] ? 'jaccard' : 'draw'),
                'better_recall' => $cosine['recall'] > $jaccard['recall'] ? 'cosine' : 
                                    ($cosine['recall'] < $jaccard['recall'] ? 'jaccard' : 'draw'),
                'better_f1' => $cosine['f1'] > $jaccard['f1'] ? 'cosine' : 
                                ($cosine['f1'] < $jaccard['f1'] ? 'jaccard' : 'draw'),
                'better_accuracy' => $cosine['accuracy'] > $jaccard['accuracy'] ? 'cosine' : 
                                    ($cosine['accuracy'] < $jaccard['accuracy'] ? 'jaccard' : 'draw'),
                'precision_difference' => abs($cosine['precision'] - $jaccard['precision']),
                'recall_difference' => abs($cosine['recall'] - $jaccard['recall']),
                'f1_difference' => abs($cosine['f1'] - $jaccard['f1']),
                'accuracy_difference' => abs($cosine['accuracy'] - $jaccard['accuracy'])
            ]
        ];
        
        return $report;
    }
}