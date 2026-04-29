<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen( $argv[1] ?? "2.input", "r");

$scores = [
    'A X' => [1+3, 3+0], 'A Y' => [2+6, 1+3], 'A Z' => [3+0, 2+6],
    'B X' => [1+0, 1+0], 'B Y' => [2+3, 2+3], 'B Z' => [3+6, 3+6],
    'C X' => [1+6, 2+0], 'C Y' => [2+0, 3+3], 'C Z' => [3+3, 1+6],
];

$part1 = $part2 = 0;

while ($s = trim(fgets($_fp)))
{
    $part1 += $scores[$s][0];
    $part2 += $scores[$s][1];
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
