<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$lines = file_get_contents($argv[1] ?? "6.input");
$lines = explode("\n", preg_replace("/[ ]+/", " ", $lines));
$time = explode(" ", explode(": ", $lines[0])[1]);
$distance = explode(" ", explode(": ", $lines[1])[1]);

function move($t, $d): int
{
    $r1 = ceil(( $t + sqrt($t * $t - 4 * $d)) / 2);
    $r2 = floor(($t - sqrt($t * $t - 4 * $d)) / 2);
    return $r1 - $r2 - 1;
}

for ($part1 = 1, $i = 0; $i < count($time); $i++)
    $part1 *= move($time[$i], $distance[$i]);

$part2 = move(implode("", $time), implode("", $distance));

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
