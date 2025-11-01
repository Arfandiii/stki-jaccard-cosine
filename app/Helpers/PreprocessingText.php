<?php

namespace App\Helpers;

class Preprocessing
{
     private static $stopWordRemover;
    private static $stemmer;

    /**
     * Inisialisasi Sastrawi (Stopword Remover & Stemmer)
     */
    private static function ensureInitialized()
    {
        if (!self::$stopWordRemover || !self::$stemmer) {
            $stopWordRemoverFactory = new StopWordRemoverFactory();
            self::$stopWordRemover = $stopWordRemoverFactory->createStopWordRemover();

            $stemmerFactory = new StemmerFactory();
            self::$stemmer = $stemmerFactory->createStemmer();
        }
    }

    /**
     * Melakukan normalisasi simbol dan tokenisasi teks menjadi array kata.
     */
    public static function tokenize($text)
    {
        // Hilangkan angka daftar seperti: 1. 2) 3. dll
        $text = preg_replace('/\b[0-9]+[\.\)]/', '', $text);

        // Hilangkan simbol lain yang tidak relevan (misal tanda titik koma ganda, tanda petik ganda, dll)
        $text = preg_replace('/[^\p{L}\s]+/u', ' ', $text);

        // Tokenisasi dengan split berdasarkan spasi (karena simbol sudah dibersihkan)
        return preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY); 
    }


    /**
     * Menyaring token hanya yang terdiri dari huruf dan panjang > 2 karakter.
     */
    public static function filterTokens($tokens)
    {
        // Filtering token (hanya huruf dan tidak kosong)
        return array_filter($tokens, function ($token) {
            return preg_match('/^[a-z]+$/', $token) && mb_strlen($token) > 2;
        });
    }

    /**
     * Menghapus stopwords standar Sastrawi dan daftar kata tidak bermakna khusus.
     */
    public static function removeStopwords($tokens)
    {
        // Remove Stopwords menggunakan Sastrawi saja
        return array_filter(array_map(function ($token) {
            return self::$stopWordRemover->remove($token);
        }, $tokens), function ($token) {
            return $token !== '';
        });
    }


    /**
     * Melakukan stemming terhadap token.
     */
    public static function stemTokens($tokens)
    {
        return array_filter(array_map(function($token) {
            return self::$stemmer->stem($token);
        }, $tokens), function($token) {
            return $token !== '';
        });
    }

    /**
     * Menjalankan semua proses preprocessing dalam satu fungsi:
     * → tokenisasi → filter → stopword removal → stemming.
     */
    public static function preprocessText($text)
    {
        // Inisialisasi Sastrawi jika belum
        self::ensureInitialized();
        // 2. Tokenize
        $tokens = self::tokenize($text);
        // 3. Filter tokens
        $filteredTokens = self::filterTokens($tokens);
        // 4. Remove stopwords
        $noStopwords = self::removeStopwords($filteredTokens);
        // 5. Stemming
        $stemmedTokens = self::stemTokens($noStopwords);
        // 6. Kembalikan hasil akhir
        return $stemmedTokens;
    }

    public static function preprocessTextDetailed($text)
    {
        self::ensureInitialized();

        // 2. Tokenize
        $tokens = self::tokenize($text);

        // 3. Filter
        $filtered = self::filterTokens($tokens);

        // 4. Remove stopwords
        $noStopwords = self::removeStopwords($filtered);

        // 5. Stemming
        $stemmed = self::stemTokens($noStopwords);

        return [
            'tokenizing' => $tokens,
            'filtering' => array_values($filtered),
            'stopword_removal' => array_values($noStopwords),
            'stemming' => array_values($stemmed),
        ];
    }

}