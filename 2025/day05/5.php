<?php

memory_reset_peak_usage();
$start_time = microtime(true);

[$R, $ID] = explode("\n\n", file_get_contents($argv[1] ?? "5.input"));
$R = explode("\n", $R);
$ID = array_map("intval", explode("\n", $ID));

$R = array_map(fn($range) => array_map("intval", explode("-", $range)), $R);
sort($R);

$part1 = $part2 = 0;

foreach ($R as [$a, $b])
{
    if (($_id ??= -1) >= $a) $a = $_id + 1;
    if ($a <= $b) $part2 += $b - $a + 1;
    $_id = max($_id, $b);
}

foreach ($ID as $_id) foreach ($R as [$a, $b])
    if ($_id >= $a && $_id <= $b)
    {
        $part1++;
        continue 2;
    }

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
