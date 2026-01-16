<?php
namespace App\Services\Similarity;

class CosineSimilarity
{
    
    public static function calculateCosineSimilarity($tfidfTable, $docCount)
    {
        $similarities = [];

        // Hitung panjang vektor Query
        $queryVectorLength = 0;
        foreach ($tfidfTable as $row) {
            $queryVectorLength += pow($row['Q'], 2);
        }
        $queryVectorLength = sqrt($queryVectorLength);

        // Identifikasi dokumen yang relevan (mengandung minimal 1 term dari query)
        $relevantDocs = [];

        foreach ($tfidfTable as $row) {
            if ($row['Q'] > 0) {
                for ($i = 1; $i <= $docCount; $i++) {
                    if (!empty($row["D$i"]) && $row["D$i"] > 0) {
                        $relevantDocs["D$i"] = true;
                    }
                }
            }
        }

        // Hitung cosine similarity HANYA untuk dokumen yang relevan
        foreach ($relevantDocs as $docKey => $_) {
            $i = intval(substr($docKey, 1)); // Ambil nomor dokumen dari "D1", "D2", ...

            $dotProduct = 0;
            $docVectorLength = 0;

            foreach ($tfidfTable as $row) {
                $docVal = $row["D$i"];
                $queryVal = $row["Q"];

                $dotProduct += $docVal * $queryVal;
                $docVectorLength += pow($docVal, 2);
            }

            $docVectorLength = sqrt($docVectorLength);

            // Hitung cosine similarity (hindari pembagian nol)
            $cosine = ($queryVectorLength > 0 && $docVectorLength > 0)
                ? round($dotProduct / ($queryVectorLength * $docVectorLength), 4)
                : 0;

            $similarities[] = [
                'doc' => "D$i",
                'similarity' => $cosine
            ];

        }
        usort($similarities, fn($a, $b) => $b['similarity'] <=> $a['similarity']);

        return $similarities;
    }
}