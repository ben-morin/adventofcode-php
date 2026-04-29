<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file_get_contents($argv[1] ?? "2.input");
$F = explode(",", trim($F));

$part1 = $part2 = 0;

foreach ($F as $line)
{
    [$a, $b] = array_map("intval", explode("-", $line));
    for ($id = $a; $id <= $b; $id++)
    {
        if (preg_match('/^(.+)\1$/', $id)) $part1 += $id;
        if (preg_match('/^(.+)\1+$/', $id)) $part2 += $id;
    }
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
