<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$pos = explode(',', trim(file_get_contents($argv[1] ?? "7.input")));
sort($pos);

$part1 = $part2 = PHP_INT_MAX;

foreach (range(array_first($pos), array_last($pos)) as $i)
{
    $f1 = $f2 = 0;
    foreach ($pos as $p)
    {
        $n = abs($p - $i);
        $f1 += $n;
        $f2 += ($n * ($n + 1)) / 2;
        if ($f1 > $part1 && $f2 > $part2) break;
    }
    $part1 = min($part1, $f1);
    $part2 = min($part2, $f2);
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
