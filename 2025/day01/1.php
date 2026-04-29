<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "1.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);

$D = 50;
$part1 = $part2 = 0;

foreach ($F as $n)
{
    $s = ($n[0] <=> 'O');
    $n = (int)substr($n, 1);
    while ($n--) if (!($D = (($D + $s) % 100 + 100) % 100)) $part2++;
    if (!$D) $part1++;
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
