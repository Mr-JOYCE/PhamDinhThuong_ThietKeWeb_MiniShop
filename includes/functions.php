<?php

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function format_price($amount)
{
    return number_format((int) $amount, 0, ',', '.') . ' đ';
}

function url($path = '')
{
    return BASE_URL . ltrim($path, '/');
}
