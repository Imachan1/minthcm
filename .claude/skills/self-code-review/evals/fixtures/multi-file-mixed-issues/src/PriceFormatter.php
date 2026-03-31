<?php

class PriceFormatter
{
    public static function format(float $price, string $currency = 'PLN'): string
    {
        var_dump($price); // debug code - do usunięcia
        return number_format($price, 2, ',', ' ') . ' ' . $currency;
    }

    public static function formatDiscount(float $price, float $discount): string
    {
        $discounted = $price - ($price * $discount);
        echo "Debug: discounted = $discounted\n"; // debug output
        return self::format($discounted);
    }
}
