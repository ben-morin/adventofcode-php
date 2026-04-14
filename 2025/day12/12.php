<?php

memory_reset_peak_usage();
$start_time = microtime(true);

const MULTIPLIER = 1.2; // present size multiplier

$P = file_get_contents($argv[1] ?? "12.input");
$P = explode("\n\n", trim($P));

$R = explode("\n", array_pop($P));
$P = array_map(fn($v) => substr_count($v, "#"), $P);

$part1 = $part2 = 0;

foreach ($R as $line)
{
    [$area, $nums] = explode(': ', $line);
    $area = array_product(explode('x', $area));
    $nums = array_map("intval", explode(' ', trim($nums)));
    $size = array_sum(array_map(fn($k, $v) => $v * $P[$k], array_keys($nums), $nums));
    if ($size > $area) continue;
    if ($size * MULTIPLIER < $area) $part1 += 1;
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
