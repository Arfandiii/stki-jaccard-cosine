<?php
use App\Models\SuratTerm;
use App\Services\PreprocessingText;   // class Anda

function insertTerms(string $suratType, int $suratId, array $terms)
{
    foreach (array_count_values($terms) as $term => $tf) {
        SuratTerm::updateOrCreate(
            ['surat_type' => $suratType, 'surat_id' => $suratId, 'term' => $term],
            ['tf' => $tf]
        );
    }
}