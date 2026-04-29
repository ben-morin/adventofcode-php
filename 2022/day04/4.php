<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen( $argv[1] ?? "4.input", "r");

$part1 = $part2 = 0;

while ($s = trim(fgets($_fp)))
{
    $s = explode(',', $s);
    [$a, $b] = explode('-', $s[0]);
    [$x, $y] = explode('-', $s[1]);
    // part 1
    if ($a >= $x && $b <= $y) $part1++;
    elseif ($x >= $a && $y <= $b) $part1++;
    // part 2
    if ($a >= $x && $a <= $y) $part2++;
    elseif ($x >= $a && $x <= $b) $part2++;
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
