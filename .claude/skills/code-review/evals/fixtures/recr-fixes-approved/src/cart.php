<?php

/**
 * Cart price calculator
 */
function calculateTotal($items) {
    $total = 0;
    for ($i = 0; $i < count($items); $i++) {
        $total += $items[$i]['price'];
    }
    return $total;
}

function formatPrice($amount) {
    return '$' . number_format($amount, 2);
}
