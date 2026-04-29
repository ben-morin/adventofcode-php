<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$s = trim(file_get_contents($argv[1] ?? "1.input"));

$part1 = $part2 = 0;

for ($i = 0; $i < strlen($s); $i++)
    if (0 > ($part1 += $s[$i] == '(' ? 1 : -1) && !$part2)
        $part2 = $i+1;

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
