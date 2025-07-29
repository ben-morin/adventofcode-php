<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file_get_contents($argv[1] ?? "25.input");

preg_match_all('/(\d+)/', $F, $m);
[$row, $col] = array_map("intval", $m[0]);

$part1 = $part2 = 0;
[$code, $base, $mod] = [20151125, 252533, 33554393];

for ($count = 0, $i = 1; $i < ($row + $col - 1); $i++) $count += $i;
$count += $col;

if (function_exists('bcpowmod'))
{
    $part1 = ($code * bcpowmod($base, $count - 1, $mod)) % $mod;
}
else for ($i = 1, $part1 = $code; $i < $count; $i++)
    $part1 = ($part1 * $base) % $mod;

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
