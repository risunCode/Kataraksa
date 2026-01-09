<?php

/**
 * HashID Helper
 * Helper untuk encode/decode ID menggunakan Hashids
 * 
 * Project: Kataraksa - Sistem Perpustakaan Digital
 */

use Hashids\Hashids;

if (!function_exists('hashid_encode')) {
    /**
     * Encode ID ke hashid
     */
    function hashid_encode(int $id): string
    {
        $hashids = new Hashids('kataraksa-library-2026', 8);
        return $hashids->encode($id);
    }
}

if (!function_exists('hashid_decode')) {
    /**
     * Decode hashid ke ID
     */
    function hashid_decode(string $hash): ?int
    {
        $hashids = new Hashids('kataraksa-library-2026', 8);
        $decoded = $hashids->decode($hash);
        return !empty($decoded) ? $decoded[0] : null;
    }
}

if (!function_exists('book_url')) {
    /**
     * Generate URL untuk buku dengan slug dan hashid
     */
    function book_url(array $book): string
    {
        $slug = url_title($book['title'], '-', true);
        $hash = hashid_encode($book['id']);
        return base_url('/book/' . $slug . '/' . $hash);
    }
}
