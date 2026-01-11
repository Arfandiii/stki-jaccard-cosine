<?php

namespace App\Helpers;

use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\StopWordRemover\StopWordRemoverFactory;

class PreprocessingText
{
    private static $stopWordRemover;
    private static $stemmer;

    /* ---------- lazy-load Sastrawi ---------- */
    private static function ensureInitialized()
    {
        if (!self::$stopWordRemover || !self::$stemmer) {
            $stopFactory = new StopWordRemoverFactory();
            self::$stopWordRemover = $stopFactory->createStopWordRemover();

            $stemFactory = new StemmerFactory();
            self::$stemmer = $stemFactory->createStemmer();
        }
    }

    /* ---------- 1. case-folding + tokenize ---------- */
    public static function tokenize($text)
    {
        $text = strtolower($text);                       // case folding
        $text = preg_replace('/\b[0-9]+[\.\)]/', '', $text); // 1. 2) 3-
        $text = preg_replace('/[^\p{L}\s]+/u', ' ', $text);  // buang simbol
        return preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
    }

    /* ---------- 2. filter : huruf saja, len > 2 ---------- */
    public static function filterTokens($tokens)
    {
        return array_values(array_filter($tokens, function ($t) {
            return preg_match('/^[a-z]+$/', $t) && mb_strlen($t) > 2;
        }));
    }

    /* ---------- 3. stop-word (Sastrawi + custom) ---------- */
    public static function removeStopwords($tokens)
    {
        self::ensureInitialized();

        return array_values(array_filter(array_map(function ($t) {
            $t = self::$stopWordRemover->remove($t); // hanya Sastrawi bawaan
            return $t !== '' ? $t : null;
        }, $tokens)));
    }

    /* ---------- 4. stemming ---------- */
    public static function stemTokens($tokens)
    {
        self::ensureInitialized();
        return array_values(array_filter(array_map(function ($t) {
            return self::$stemmer->stem($t);
        }, $tokens)));
    }

    /* ---------- 5. satu paket ---------- */
    public static function preprocessText($text)
    {
        self::ensureInitialized();
        $tokens   = self::tokenize($text);
        $filtered = self::filterTokens($tokens);
        $noStop   = self::removeStopwords($filtered);
        $stemmed  = self::stemTokens($noStop);
        return $stemmed;
    }

    /* ---------- opsional debug ---------- */
    public static function preprocessTextDetailed($text)
    {
        self::ensureInitialized();
        $tokens   = self::tokenize($text);
        $filtered = self::filterTokens($tokens);
        $noStop   = self::removeStopwords($filtered);
        $stemmed  = self::stemTokens($noStop);

        return [
            'case_folding_and_tokenizing' => $tokens,
            'filtering'                   => $filtered,
            'stopword_removal'            => $noStop,
            'stemming'                    => $stemmed,
        ];
    }
}
