<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$key = trim(file_get_contents($argv[1] ?? "4.input"));

$part1 = $part2 = 0;

for ($i = 0; !($part1 && $part2); $i++)
{
    $hash = md5($key.$i);
    if (!$part1 && str_starts_with($hash, "00000")) $part1 = $i;
    if (!$part2 && str_starts_with($hash, "000000")) $part2 = $i;
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
