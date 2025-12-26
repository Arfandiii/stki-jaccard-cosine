<?php

namespace App\Helpers;

use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\StopWordRemover\StopWordRemoverFactory;

class PreprocessingText
{
    private static ?object $stopWordRemover = null;
    private static ?object $stemmer = null;

    /**
     * Inisialisasi Sastrawi (Stopword Remover & Stemmer) sekali saja.
     */
    private static function ensureInitialized(): void
    {
        if (self::$stopWordRemover === null || self::$stemmer === null) {
            self::$stopWordRemover = (new StopWordRemoverFactory())->createStopWordRemover();
            self::$stemmer = (new StemmerFactory())->createStemmer();
        }
    }

    /**
     * Case folding: ubah teks menjadi huruf kecil.
     */
    public static function caseFolding(string $text): string
    {
        return strtolower($text);
    }

    /**
     * Normalisasi & tokenisasi teks menjadi array kata.
     */
    public static function tokenize(string $text): array
    {
        // Hilangkan angka daftar (1. 2) 3. dll)
        $text = preg_replace('/\b\d+[\.\)]/', '', $text);
        // Hilangkan simbol selain huruf & spasi
        $text = preg_replace('/[^\p{L}\s]+/u', ' ', $text);
        // Tokenisasi
        return preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * Saring token: hanya huruf & panjang > 2 karakter.
     */
    public static function filterTokens(array $tokens): array
    {
        return array_filter($tokens, function ($token) {
            return preg_match('/^[a-z]+$/', $token) && mb_strlen($token) > 2;
        });
    }

    /**
     * Hapus stopwords dengan Sastrawi.
     */
    public static function removeStopwords(array $tokens): array
    {
        self::ensureInitialized();
        return array_filter(array_map(fn($t) => self::$stopWordRemover->remove($t), $tokens));
    }

    /**
     * Stemming token dengan Sastrawi.
     */
    public static function stemTokens(array $tokens): array
    {
        self::ensureInitialized();
        return array_filter(array_map(fn($t) => self::$stemmer->stem($t), $tokens));
    }

    /**
     * Rangkaian lengkap preprocessing.
     * Output: array kata akhir (unik & ter-stem).
     */
    public static function preprocessText(string $text): array
    {
        self::ensureInitialized();
        $text = self::caseFolding($text);
        $tokens = self::tokenize($text);
        $tokens = self::filterTokens($tokens);
        $tokens = self::removeStopwords($tokens);
        $tokens = self::stemTokens($tokens);
        return array_values(array_unique($tokens));
    }

    /**
     * Debugging: kembalikan setiap tahap preprocessing.
     */
    public static function preprocessTextDetailed(string $text): array
    {
        self::ensureInitialized();
        $case = self::caseFolding($text);
        $tokens = self::tokenize($case);
        $filtered = self::filterTokens($tokens);
        $noStop = self::removeStopwords($filtered);
        $stemmed = self::stemTokens($noStop);
        return [
            'case_folding' => $case,
            'tokenizing' => array_values($tokens),
            'filtering' => array_values($filtered),
            'stopword_removal' => array_values($noStop),
            'stemming' => array_values($stemmed),
        ];
    }
}