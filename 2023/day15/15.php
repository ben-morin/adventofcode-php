<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$IS = file_get_contents($argv[1] ?? "15.input");
$IS = explode(",", trim($IS));

function h($s): int
{
    $h = 0;
    foreach (str_split($s) as $c) $h = ($h + ord($c)) * 17 % 256;
    return $h;
}

$part1 = $part2 = 0;

$H = [];
foreach ($IS as $step)
{
    $part1 += h($step);
    [$lb, $op, $fl] = preg_split("/([-=])/", $step, -1, PREG_SPLIT_DELIM_CAPTURE);
    if ($op == "=") $H[h($lb)][$lb] = $fl; else unset($H[h($lb)][$lb]);
}
foreach ($H as $box => $lens)
    foreach (array_values($lens) as $slot => $fl)
        $part2 += ($box + 1) * ($slot + 1) * $fl;

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
