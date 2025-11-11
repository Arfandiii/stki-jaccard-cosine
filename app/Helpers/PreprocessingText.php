<?php

namespace App\Helpers;

use Sastrawi\StopWordRemover\StopWordRemoverFactory;
use Sastrawi\Stemmer\StemmerFactory;

class PreprocessingText
{
    private static ?object $stopWordRemover = null;
    private static ?object $stemmer = null;

    private static function init(): void
    {
        if (self::$stopWordRemover === null) {
            self::$stopWordRemover = (new StopWordRemoverFactory)->createStopWordRemover();
            self::$stemmer         = (new StemmerFactory)->createStemmer();
        }
    }

    /* ---------- API Utama ---------- */
    public static function preprocessText(string $text): array
    {
        self::init();
        $tokens = self::tokenize($text);
        $tokens = self::filterTokens($tokens);
        $tokens = self::removeStopwords($tokens);
        $tokens = self::stemTokens($tokens);
        return array_values($tokens);   // pasti array numerik
    }

    /* ---------- Langkah-langkah ---------- */
    private static function tokenize(string $text): array
    {
        $text = preg_replace('/\b\d+[\.\)]/', '', $text);
        $text = preg_replace('/[^\p{L}\s]+/u', ' ', $text);
        return preg_split('/\s+/', mb_strtolower($text), -1, PREG_SPLIT_NO_EMPTY);
    }

    private static function filterTokens(array $tokens): array
    {
        return array_filter($tokens, fn($t) => preg_match('/^[a-z]{3,}$/', $t));
    }

    private static function removeStopwords(array $tokens): array
    {
        return array_filter(
            array_map(fn($t) => self::$stopWordRemover->remove($t), $tokens),
            fn($t) => $t !== ''
        );
    }

    private static function stemTokens(array $tokens): array
    {
        return array_filter(
            array_map(fn($t) => self::$stemmer->stem($t), $tokens),
            fn($t) => $t !== ''
        );
    }

    /* ---------- API Debug ---------- */
    public static function preprocessTextDetailed(string $text): array
    {
        self::init();
        $tokens   = self::tokenize($text);
        $filtered = self::filterTokens($tokens);
        $noStop   = self::removeStopwords($filtered);
        $stemmed  = self::stemTokens($noStop);

        return [
            'tokenizing'       => array_values($tokens),
            'filtering'        => array_values($filtered),
            'stopword_removal' => array_values($noStop),
            'stemming'         => array_values($stemmed),
        ];
    }
}
