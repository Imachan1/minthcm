<?php

/**
 * Helper utilities
 */

/**
 * Format price for display.
 *
 * @param float $amount
 * @param string $currency
 * @return string
 */
function formatPrice(float $amount, string $currency = 'PLN'): string {
    if ($amount < 0) {
        return '-' . number_format(abs($amount), 2, ',', ' ') . ' ' . $currency;
    }
    return number_format($amount, 2, ',', ' ') . ' ' . $currency;
}

/**
 * Parse price string to float.
 *
 * @param string $price e.g. "1 234,56 PLN"
 * @return float
 */
function parsePrice(string $price): float {
    $clean = preg_replace('/[^\d,]/', '', $price);
    return (float) str_replace(',', '.', $clean);
}
