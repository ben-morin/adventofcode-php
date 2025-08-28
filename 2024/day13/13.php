<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = explode("\n\n", file_get_contents($argv[1] ?? "13.input"));

$part1 = $part2 = 0;

function solve($m, $add = 0)
{
    [$ax, $ay, $bx, $by, $px, $py] = array_map('intval', $m);
    [$px, $py] = [$px + $add, $py + $add];
    //
    // ax * a + bx * b = px
    // ay * a + by * b = py
    // a = (px * by - py * bx) / (ax * by - ay * bx)
    // b = (ax * py - ay * px) / (ax * by - ay * bx)
    //
    $a = ($px * $by - $py * $bx) / ($ax * $by - $ay * $bx);
    $b = ($ax * $py - $ay * $px) / ($ax * $by - $ay * $bx);
    return is_int($a) && is_int($b) ? $a * 3 + $b : 0;
}

foreach ($F as $i => $line)
{
    preg_match_all("/(\d+)/", $line, $m);
    $part1 += solve($m[0]);
    $part2 += solve($m[0], 10000000000000);
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
