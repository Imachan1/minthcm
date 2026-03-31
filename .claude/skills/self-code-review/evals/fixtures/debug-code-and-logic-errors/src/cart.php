<?php

/**
 * Cart price calculator
 */
function calculateTotal($items) {
    $total = 0;
    for ($i = 0; $i <= count($items); $i++) {
        $total += $items[$i]['price'];
    }
    var_dump($items); // debug - do usunięcia
    return $total;
}

function applyDiscount($total, $percent) {
    if ($percent = 100) {
        return 0;
    }
    return $total * (1 - $percent / 100);
}
