<?php

define('ALL_NUMBERS', [1, 2, 3, 4, 5, 6, 7, 8, 9]);

$sudoku = [
    [3, 0, 6, 5, 0, 8, 4, 0, 0],
    [5, 2, 0, 0, 0, 0, 0, 0, 0],
    [0, 8, 7, 0, 0, 0, 0, 3, 1],
    [0, 0, 3, 0, 1, 0, 0, 8, 0],
    [9, 0, 0, 8, 6, 3, 0, 0, 5],
    [0, 5, 0, 0, 9, 0, 6, 0, 0],
    [1, 3, 0, 0, 0, 0, 2, 5, 0],
    [0, 0, 0, 0, 0, 0, 0, 7, 4],
    [0, 0, 5, 2, 0, 6, 3, 0, 0]
];

function row_get_available_numbers(array $sudoku, int $row): array
{
    $row_numbers = [];

    foreach ($sudoku[$row] as $number) {
        if ($number) $row_numbers[] = $number;
    }

    return array_diff(ALL_NUMBERS, $row_numbers);
}

function col_get_available_numbers(array $sudoku, int $col): array
{
    $col_numbers = [];
    $col_items = [];

    foreach ($sudoku as $row) {
        $col_items[] = $row[$col];
    }

    foreach ($col_items as $number) {
        if ($number) $col_numbers[] = $number;
    }

    return array_diff(ALL_NUMBERS, $col_numbers);
}

function square_get_available_numbers(array $sudoku, int $row, int $col): array
{
    $square_items = [];

    for ($i = floor(($col + 1) / 3) * 3; $i < ceil(($col + 1) / 3) * 3; $i++) {
        for ($j = floor(($row + 1) / 3) * 3; $j < ceil(($row + 1) / 3) * 3; $j++) {
            $square_items[] = $sudoku[$j][$i];
        }
    }

    return array_diff(ALL_NUMBERS, $square_items);
}

function get_cell_available_values(array $sudoku, int $row, int $col): array
{
    $row_av_numbers = row_get_available_numbers($sudoku, $row);
    $col_av_numbers = col_get_available_numbers($sudoku, $col);
    $square_av_numbers = square_get_available_numbers($sudoku, $row, $col);

    return array_values(array_intersect($row_av_numbers, $col_av_numbers, $square_av_numbers));
}

function resolveSudoku(&$sudoku) : bool
{
    $min_row = -1;
    $min_col = -1;
    $min_values = [];

    while (true) {
        $min_row = -1;

        for ($row = 0; $row < 9; $row++) {
            for ($col = 0; $col < 9; $col++) {
                if ($sudoku[$row][$col]) continue;

                $possible_values = get_cell_available_values($sudoku, $row, $col);
                $possible_values_count = count($possible_values);

                if (!$possible_values_count) {
                    return false;
                }

                if ($possible_values_count == 1) {
                    $sudoku[$row][$col] = $possible_values[array_key_first($possible_values)];
                }

                if ($min_row < 0 || $possible_values_count < count($min_values)) {
                    $min_row = $row;
                    $min_col = $col;
                    $min_values = $possible_values;
                }
            }
        }

        if ($min_row == -1) {
            return true;
        } else if (1 < count($min_values)) {
            break;
        }
    }

    foreach ($min_values as $value) {
        $sudoku_solved = $sudoku;
        $sudoku_solved[$min_row][$min_col] = $value;

        if (solve_sudoku($sudoku_solved)) {
            $sudoku = $sudoku_solved;
            return true;
        }
    }

    return false;
}

resolveSudoku($sudoku);
