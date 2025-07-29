<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$N = (int)trim(file_get_contents($argv[1] ?? "20.input"));

$part1 = $part2 = 0;

$house = 1;
while (!$part1 || !$part2)
{
    $sum1 = $sum2 = 0;
    $limit = sqrt($house);
    for ($i = 1; $i <= $limit; $i++)
    {
        if ($house % $i) continue;
        $sum1 += $i;
        if ($i <= 50) $sum2 += $i;
        if ($house == $i * $i) continue;
        $sum1 += intdiv($house, $i);
        if ($i <= 50) $sum2 += intdiv($house, $i);
    }
    if (!$part1 && ($sum1 * 10 >= $N)) $part1 = $house;
    if (!$part2 && ($sum2 * 11 >= $N)) $part2 = $house;
    $house++;
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
