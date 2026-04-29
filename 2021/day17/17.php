<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = trim(file_get_contents($argv[1] ?? "17.input"));
preg_match('/x=(-?\d+)\.\.(-?\d+), y=(-?\d+)\.\.(-?\d+)/', $F, $m);
[, $X1, $X2, $Y1, $Y2] = array_map('intval', $m);

$part1 = 0;
$part2 = 0;

for ($dx = 1; $dx <= $X2; $dx++) for ($dy = $Y1; $dy <= -$Y1; $dy++)
{
    $x = $y = $max_y = 0;
    [$vx, $vy] = [$dx, $dy];
    while ($x <= $X2 && $y >= $Y1)
    {
        // velocity...
        $x += $vx;
        $y += $vy;
        // drag...
        $vx -= $vx <=> 0;
        $vy--;
        // highest point...
        $max_y = max($max_y, $y);
        // hit...
        if ($x >= $X1 && $x <= $X2 && $y >= $Y1 && $y <= $Y2)
        {
            $part1 = max($part1, $max_y);
            $part2++;
            break;
        }
    }
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
