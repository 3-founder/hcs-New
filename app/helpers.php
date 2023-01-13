<?php

if(!function_exists('getMonth()')) {
    function getMonth(int $index, bool $indexed = false) {
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        return ($indexed) ? $months[$index] : $months[$index - 1];
    }
}

if(!function_exists('npwp')) {
    function npwp($value) {
        if(is_null($value)) return null;

        return preg_replace(
            '/(\d{2})(\d{3})(\d{3})(\d{1})(\d{3})(\d{3})/',
            '$1.$2.$3.$4-$5.$6',
            $value
        );
    }
}
