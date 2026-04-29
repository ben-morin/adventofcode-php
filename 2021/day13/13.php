<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = explode("\n\n", trim(file_get_contents($argv[1] ?? "13.input")));

$D = [];
foreach (explode("\n", array_shift($F)) as $dot)
{
    [$x, $y] = explode(',', $dot);
    $D["$x,$y"] = [(int)$x, (int)$y];
}

foreach (explode("\n", array_shift($F)) as $fold)
{
    preg_match('/fold along ([xy])=(\d+)/', $fold, $m);
    $F[] = [$m[1], (int)$m[2]];
}

$part1 = null;
foreach ($F as [$axis, $v])
{
    $_d = [];
    foreach ($D as [$x, $y])
    {
        if ($axis == 'x' && $x > $v) $x = 2 * $v - $x;
        if ($axis == 'y' && $y > $v) $y = 2 * $v - $y;
        $_d["$x,$y"] = [$x, $y];
    }
    $D = $_d;
    $part1 ??= count($D);
}

[$max_x, $max_y] = [max(array_column($D, 0)) + 1, max(array_column($D, 1)) + 1];
$G = array_fill(0, $max_y, str_repeat(' ', $max_x));
foreach ($D as [$x, $y]) $G[$y][$x] = '#';
$part2 = "\n" . implode("\n", $G);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
