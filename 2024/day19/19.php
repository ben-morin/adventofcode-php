<?php

memory_reset_peak_usage();
$start_time = microtime(true);
const DEBUG = false;

$_fp = fopen( $argv[1] ?? "19.input", "r");
$T = explode(", ", trim(fgets($_fp).fgets($_fp)));

usort($T, function($a, $b) { return strlen($b) - strlen($a); });

$part1 = $part2 = 0;

$C = [];
function f($d)
{
    global $C, $T;
    if (!strlen($d)) return 1;
    if (isset($C[$d])) return $C[$d];
    $result = 0;
    foreach ($T as $t) if (str_starts_with($d, $t)) $result += f(substr($d, strlen($t)));
    return ($C[$d] = $result);
}

while ($line = trim(fgets($_fp)))
{
    if (DEBUG) echo "trying: {$line}\n";
    $part2 += ($count = f($line));
    if ($count) $part1++;
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
