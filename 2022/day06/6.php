<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$S = file_get_contents($argv[1] ?? "6.input");

$part1 = $part2 = 0;

function f($s, $part2 = false): int
{
    $m = ($part2 ? 14 : 4);
    for ($i = $m; $i <= strlen($s); $i++)
        if (count(array_unique(str_split(substr($s, $i - $m, $m)))) == $m)
            return $i;
    return 0;
}

$part1 = f($S);
$part2 = f($S, true);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
