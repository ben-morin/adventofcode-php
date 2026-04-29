<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$state = explode(',', trim(file_get_contents($argv[1] ?? "6.input")));
$LF = array_fill(0, 9, 0);

while (count($state)) $LF[array_shift($state)]++;

$part1 = $part2 = 0;

for ($i = 1; $i <= 256; $i++)
{
    $_fish = $LF[0];
    foreach (range(0, 7) as $f) $LF[$f] = $LF[$f+1];
    $LF[6] += $_fish;
    $LF[8] = $_fish;
    if ($i == 80) $part1 = array_sum($LF);
}
$part2 = array_sum($LF);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
