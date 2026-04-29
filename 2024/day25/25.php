<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file_get_contents($argv[1] ?? "25.input");
$F = explode("\n\n", $F);

$L = $K = [];
foreach ($F as $line) if ($line[0] == "#") $L[] = $line; else $K[] = $line;

$part1 = $part2 = 0;

foreach ($K as $key) foreach ($L as $lock)
{
    for ($i = 0; $i < strlen($key); $i++)
        if ($key[$i] == "#" && $lock[$i] == "#") continue 2;
    $part1 += 1;
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
