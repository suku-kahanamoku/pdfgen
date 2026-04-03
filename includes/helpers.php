<?php
function format_czech_text($text) {
    return preg_replace('/(?<=^| )([a-z]{1,2})( )/i', '$1&nbsp;', $text);
}
function format_czk(float|int $amount): string {
    return number_format($amount, 0, ',', ' ');
}
function parse_czk(string $priceStr): float {
    $clean = preg_replace('/[^0-9\.-]/', '', str_replace(',', '.', $priceStr));
    return (float) $clean;
}function format_date(string $date): string {
    if (empty($date)) return '';
    try {
        return (new DateTime($date))->format('d.m.Y');
    } catch (Exception $e) {
        return $date;
    }
}

function safe_text($text): string {
    if (empty($text) || is_array($text)) return '';
    return htmlspecialchars(preg_replace('/\[cite.*?\]/', '', (string)$text));
}