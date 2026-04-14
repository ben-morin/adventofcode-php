<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen( $argv[1] ?? "1.input", "r");

$part1 = $part2 = 0;

$L = $R = [];
while ($line = trim(fgets($_fp)))
{
    [$l, $r] = explode(" ", $line, 2);
    $L[] = (int)$l;
    $R[] = (int)$r;
}

sort($L);
sort($R);
foreach ($L as $i => $l) $part1 += abs($R[$i] - $l);

$R = array_count_values($R);
foreach ($L as $l) $part2 += $l * ($R[$l] ?? 0);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
