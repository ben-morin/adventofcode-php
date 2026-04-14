<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file_get_contents($argv[1] ?? "12.input");

preg_match_all("/-?\d+/", $F, $m);
$part1 = array_sum($m[0]);

function remove_red($item)
{
    if (!is_array($item)) return $item;
    if (!array_is_list($item) && in_array('red', $item)) return 0;
    return array_map('remove_red', $item);
}

$F = array_map('remove_red', json_decode($F, true));

preg_match_all("/-?\d+/", json_encode($F), $m);
$part2 = array_sum($m[0]);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
