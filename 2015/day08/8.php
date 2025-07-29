<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "8.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);

$part1 = $part2 = 0;

foreach ($F as $line)
{
    $s = stripcslashes($line);
    $part1 += strlen($line) - strlen($s) + 2;
    $s = addcslashes($line, '\"');
    $part2 += strlen($s) - strlen($line) + 2;
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
