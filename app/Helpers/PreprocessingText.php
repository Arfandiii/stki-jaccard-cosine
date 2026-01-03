<?php

namespace App\Helpers;

use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\StopWordRemover\StopWordRemoverFactory;

class PreprocessingText
{
    private static $stemmer;
    private static $stopRemover;   // <- tambahkan ini

    private static function init()
    {
        if (!self::$stemmer) {
            $stemFactory   = new StemmerFactory();
            $stopFactory   = new StopWordRemoverFactory();

            self::$stemmer   = $stemFactory->createStemmer();
            self::$stopRemover = $stopFactory->createStopWordRemover();
        }
    }

    /**
     * 1. Tokenize:
     *    - lowercase
     *    - buang angka daftar (1. 2) 3- dsb)
     *    - buang simbol, pisah spasi
     */
    public static function tokenize(string $text): array
    {
        $text = strtolower($text);
        // hilangkan angka daftar: 1. 2) 3-
        // $text = preg_replace('/\b\d+[\.\)-]/', '', $text);
        $text = preg_replace('/[^a-zA-Z\s]+/', ' ', $text);
        // buang simbol kecuali huruf & spasi
        $text = preg_replace('/[^\p{L}\s]+/u', ' ', $text);
        // split spasi berlebih
        return preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * 2. FilterTokens:
     *    - hanya huruf
     *    - panjang > 2
     */
    public static function filterTokens(array $tokens): array
    {
        return array_values(array_filter($tokens, function ($t) {
            return preg_match('/^[a-z]+$/', $t) && mb_strlen($t) > 2;
        }));
    }

    /**
     * 3. RemoveStopwords:
     *    - gunakan stopword bawaan Sastrawi
     */
    public static function removeStopwords(array $tokens): array
    {
        self::init();
        return array_values(array_filter(array_map(function ($t) {
            $clean = self::$stopRemover->remove($t);
            return $clean !== '' ? $clean : null;
        }, $tokens)));
    }

    /**
     * 4. StemTokens:
     *    - stemming Sastrawi
     */
    public static function stemTokens(array $tokens): array
    {
        self::init();
        return array_values(array_filter(array_map(function ($t) {
            return self::$stemmer->stem($t);
        }, $tokens)));
    }

    /**
     * Gabungan cepat
     */
    public static function preprocessText(string $text): array
    {
        return self::stemTokens(
            self::removeStopwords(
                self::filterTokens(
                    self::tokenize($text)
                )
            )
        );
    }

    /**
     * Detail per tahap (untuk debug atau ditampilkan)
     */
    public static function preprocessTextDetailed(string $text): array
    {
        $tokens   = self::tokenize($text);
        $filtered = self::filterTokens($tokens);
        $noStop   = self::removeStopwords($filtered);
        $stemmed  = self::stemTokens($noStop);

        return [
            'tokenize'       => $tokens,
            'filterTokens'   => $filtered,
            'removeStopwords'=> $noStop,
            'stemTokens'     => $stemmed,
            'final'          => $stemmed,
        ];
    }
}
