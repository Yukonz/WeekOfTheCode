<?php

function coinsSum(int $sum, array $coins) : array
{
    if ($sum <= 0 || empty($coins) || $sum < min($coins)) return [];

    rsort($coins);

    $min_coins_number = ceil($sum / $coins[0]);

    $all_coin_sets = [];
    $iteration_coin_sets = [];

    for ($i = 0; $i <= floor($sum / $coins[0]); $i++) {
        $coin_set = [];

        for ($n = 0; $n <= $i; $n++) {
            if ($n > 0) {
                $coin_set[] = $coins[0];
            }
        }

        $all_coin_sets[] = $coin_set;
    }

    unset($coins[0]);
    $coins = array_values($coins);

    while ($coins) {
        $first_coin_value = $coins[0];

        $iteration_coin_sets = $all_coin_sets;
        $all_coin_sets = [];

        foreach ($iteration_coin_sets as $set_index => $coin_set) {
            $max_coins_number = floor(($sum - array_sum($coin_set)) / $first_coin_value);

            for ($i = 0; $i <= $max_coins_number; $i++) {
                $next_coin_set = [];

                for ($n = 0; $n <= $i; $n++) {
                    if ($n > 0) {
                        $next_coin_set[] = $first_coin_value;
                    }
                }

                $all_coin_sets[] = array_merge($coin_set, $next_coin_set);
            }

            if (count($coins) == 1) {
                if (count($all_coin_sets[array_key_last($all_coin_sets)]) == $min_coins_number) {
                    return $all_coin_sets[array_key_last($all_coin_sets)];
                }
            }
        }

        unset($coins[0]);
        $coins = array_values($coins);

        $iteration_coin_sets = $all_coin_sets;
    }

    $min_set_length = 0;
    $final_coins_set = [];

    foreach ($all_coin_sets as $key => $coin_set) {
        if (array_sum($coin_set) != $sum) continue;

        if (!$min_set_length || $min_set_length > count($coin_set)) {
            $min_set_length = count($coin_set);
            $final_coins_set = $coin_set;
        }
    }

    return $final_coins_set;
}

echo implode(',', coinsSum(13, [5,4,1]));
//5,4,4

echo implode(',', coinsSum(14, [6,3,1]));
//6,6,1,1

echo implode(',', coinsSum(140, [50,25,10,5]));
//50,50,25,10,5
