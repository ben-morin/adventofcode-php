<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$N = file($argv[1] ?? "18.input", FILE_IGNORE_NEW_LINES);
assert($N !== false);

// flatten number into [value, depth] pairs...
$N = array_map(function($s)
{
    $n = [];
    $d = 0;
    for ($i = 0; $i < strlen($s); $i++)
    {
        $c = $s[$i];
        if ($c == ",") continue;
        elseif ($c == "[") $d++;
        elseif ($c == "]") $d--;
        else $n[] = [(int)$c, $d];
    }
    return $n;
}, $N);

function add($a, $b)
{
    $sum = array_merge($a, $b);
    foreach ($sum as $k => $v) $sum[$k][1]++;
    return $sum;
}

function reduce($n)
{
    while (true)
    {
        // explode: first value at depth >= 5 (its right sibling must be same depth)...
        for ($i = 0, $c = count($n); $i < $c; $i++)
        {
            if ($n[$i][1] < 5) continue;
            if ($i > 0) $n[$i-1][0] += $n[$i][0];
            if ($i+2 < $c) $n[$i+2][0] += $n[$i+1][0];
            array_splice($n, $i, 2, [[0, $n[$i][1] - 1]]);
            continue 2;
        }
        // split: first value >= 10...
        for ($i = 0, $c = count($n); $i < $c; $i++)
        {
            if ($n[$i][0] < 10) continue;
            [$v, $d] = $n[$i];
            array_splice($n, $i, 1, [[intdiv($v, 2), $d+1], [intdiv($v+1, 2), $d+1]]);
            continue 2;
        }
        return $n;
    }
}

function magnitude($n)
{
    while (count($n) > 1) for ($i = 0, $c = count($n); $i < $c - 1; $i++)
    {
        if ($n[$i][1] !== $n[$i+1][1]) continue;
        array_splice($n, $i, 2, [[3 * $n[$i][0] + 2 * $n[$i+1][0], $n[$i][1] - 1]]);
        break;
    }
    return $n[0][0];
}

// part 1: sum all in order...
$part1 = $N[0];
for ($i = 1; $i < count($N); $i++) $part1 = reduce(add($part1, $N[$i]));
$part1 = magnitude($part1);

// part 2: max magnitude of any ordered pair...
$part2 = 0;
for ($i = 0; $i < count($N); $i++) for ($j = 0; $j < count($N); $j++)
{
    if ($i == $j) continue;
    $sum = reduce(add($N[$i], $N[$j]));
    $part2 = max($part2, magnitude($sum));
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
