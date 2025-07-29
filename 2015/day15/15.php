<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "15.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);

foreach ($F as $i => $line)
{
    preg_match('/(-?\d+).+?(-?\d+).+?(-?\d+).+?(-?\d+).+?(-?\d+)/', $line, $m);
    $F[$i] = array_slice($m, 1);
}
assert(count($F) == 4);

$part1 = $part2 = 0;

function sum100(): Generator
{
    for ($a = 0; $a <= 100; $a++)
        for ($b = 0; $b <= 100 - $a; $b++)
            for ($c = 0; $c <= 100 - $a - $b; $c++)
                if (($d = 100 - $a - $b - $c) >= 0)
                    yield [$a, $b, $c, $d];
}

foreach (sum100() as $C)
{
    for ($n = [], $i = 0; $i < 5; $i++)
        if (($n[$i] = $C[0]*$F[0][$i] + $C[1]*$F[1][$i] + $C[2]*$F[2][$i] + $C[3]*$F[3][$i]) < 0)
            continue 2;
    $score = $n[0] * $n[1] * $n[2] * $n[3];
    $part1 = max($part1, $score);
    if ($n[4] == 500) $part2 = max($part2, $score);
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
