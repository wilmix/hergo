<?php
if (!function_exists('convertToISO')) {
    function convertToISO($string) {
        return mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8');
    }
}
?>