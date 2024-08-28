<?php

// In your helpers.php or directly in the file
function formatIDR($amount)
{
    return 'Rp ' . number_format($amount, 2, ',', '.');
}
