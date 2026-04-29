<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$G = file($argv[1] ?? "11.input", FILE_IGNORE_NEW_LINES);
assert($G !== false);

$G = array_map('str_split', $G);
$ROWS = count($G);
$COLS = count($G[0]);
$N = $ROWS * $COLS;

$part1 = $part2 = 0;
$total = $step = 0;

while ($part1 == 0 || $part2 == 0)
{
    $step++;
    $Q = $F = [];

    // bump every cell; queue any that crossed 9...
    for ($y = 0; $y < $ROWS; $y++) for ($x = 0; $x < $COLS; $x++)
        if (++$G[$y][$x] > 9) $Q[] = [$y, $x];

    // propagate flashes; each cell flashes at most once per step
    while ($Q)
    {
        [$y, $x] = array_pop($Q);
        if (isset($F[$k = "$y,$x"])) continue;
        $F[$k] = 1;
        foreach ([-1, 0, 1] as $dy) foreach ([-1, 0, 1] as $dx)
        {
            if ($dy == 0 && $dx == 0) continue;
            [$_y, $_x] = [$y + $dy, $x + $dx];
            if ($_y < 0 || $_y >= $ROWS || $_x < 0 || $_x >= $COLS) continue;
            if (isset($F["$_y,$_x"])) continue;
            if (++$G[$_y][$_x] > 9) $Q[] = [$_y, $_x];
        }
    }

    // reset flashed cells to 0
    foreach ($F as $k => $_)
    {
        [$y, $x] = explode(",", $k);
        $G[$y][$x] = 0;
    }

    $flashes = count($F);
    $total += $flashes;

    if ($step == 100) $part1 = $total;
    if ($flashes == $N) $part2 = $step;
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
