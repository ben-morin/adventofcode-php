<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "1.input", FILE_IGNORE_NEW_LINES);
assert($F != false);
array_map("intval", $F);

$part1 = $part2 = 0;

for ($i = 1, $c = count($F); $i < $c; $i++)
{
    if ($F[$i] > $F[$i - 1]) $part1++;
    if (isset($F[$i + 2]) && $F[$i + 2] > $F[$i - 1]) $part2++;
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
