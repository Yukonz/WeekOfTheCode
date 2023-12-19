<?php

function subpattern(string $a, string $b) : string
{
    $string_a_arr = str_split($a);
    $string_b_arr = str_split($b);
    $subpattern_arr = [];

    foreach ($string_a_arr as $char_a) {
        if (in_array($char_a, $subpattern_arr)) continue;

        foreach ($string_b_arr as $char_b) {
            if ($char_a == $char_b) {
                $subpattern_arr[] = $char_a;
                break;
            }
        }
    }

    return implode('', $subpattern_arr);
}