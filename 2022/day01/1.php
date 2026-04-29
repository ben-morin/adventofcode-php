<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$C = explode("\n\n", trim(file_get_contents($argv[1] ?? "1.input")));
array_walk($C, fn(&$v) => $v = array_sum(explode("\n", $v)));
rsort($C);

$part1 = $C[0];
$part2 = array_sum(array_slice($C, 0, 3));

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
