<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "25.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);

const SNAFU = [-2 => '=', -1 => '-', 0, 1, 2];

function snafu_int($s)
{
    $i = 0;
    foreach (array_reverse(str_split($s)) as $ex => $d)
        $i += array_flip(SNAFU)[$d] * pow(5, $ex);
    return $i;
}

function int_snafu($i)
{
    $s = ''; $c = false;
    while ($i > 0)
    {
        $d = $i % 5 + (int)$c;
        if ($c = ($d > 2)) $d -= 5;
        $s .= SNAFU[$d];
        $i = intdiv($i, 5);
    }
    if ($c) $s .= "1";
    return strrev($s);
}

$part1 = $part2 = 0;

foreach($F as $s) $part1 += snafu_int($s);
$part1 = int_snafu($part1);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
