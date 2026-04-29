<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen($argv[1] ?? "2.input", "r");

$f1 = $f2 = $d1 = $d2 = $a = 0;

while ($s = trim(fgets($_fp)))
{
    [$c, $v] = explode(' ', $s);
    $v = intval($v);

    if ($c == 'forward')
    {
        $f1 += $v;
        $f2 += $v;
        $d2 += $a * $v;
    }
    else if ($c == 'down')
    {
        $d1 += $v;
        $a += $v;
    }
    else // up...
    {
        $d1 -= $v;
        $a -= $v;
    }
}

$part1 = $f1 * $d1;
$part2 = $f2 * $d2;

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
