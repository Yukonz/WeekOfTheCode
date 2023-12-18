<?php

function fibonacci(int $n) : int
{
    if ($n <= 0) return 0;
    if ($n == 1) return 1;

    $prev_number = 0;
    $current_number = 1;

    for ($index = 2; $index <= $n; $index++)
    {
        $fibonacci_number = $prev_number + $current_number;
        $prev_number = $current_number;
        $current_number = $fibonacci_number;
    }

    return $current_number;
}