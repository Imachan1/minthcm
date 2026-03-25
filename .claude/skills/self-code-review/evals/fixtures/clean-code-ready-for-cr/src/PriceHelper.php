<?php

class PriceHelper {

    /**
     * Format price with currency symbol.
     *
     * @param float  $amount   Amount to format
     * @param string $currency Currency code (default: PLN)
     * @return string
     */
    public static function formatPrice(float $amount, string $currency = 'PLN'): string {
        $formatted = number_format($amount, 2, ',', ' ');
        return $formatted . ' ' . $currency;
    }

    /**
     * Parse price string to float.
     * Accepts formats: "12,50 PLN", "12.50", "1 234,50 PLN"
     *
     * @param string $price Price string to parse
     * @return float
     * @throws InvalidArgumentException if the string cannot be parsed
     */
    public static function parsePrice(string $price): float {
        // Remove currency suffix and whitespace
        $clean = preg_replace('/[A-Z]+$/', '', trim($price));
        $clean = trim($clean);

        // Normalize: remove space thousands separator, replace comma decimal
        $clean = str_replace([' ', "\u{00A0}"], '', $clean);
        $clean = str_replace(',', '.', $clean);

        if (!is_numeric($clean)) {
            throw new InvalidArgumentException("Cannot parse price: '$price'");
        }

        return (float) $clean;
    }
}
