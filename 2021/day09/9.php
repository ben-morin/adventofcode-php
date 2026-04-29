<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$G = file($argv[1] ?? "9.input", FILE_IGNORE_NEW_LINES);
assert($G !== false);

$G = array_map('str_split', $G);
$ROWS = count($G);
$COLS = count($G[0]);

$part1 = $part2 = 0;
$B = [];

for ($i = 0; $i < $ROWS; $i++) for ($j = 0; $j < $COLS; $j++)
{
    $n = $G[$i][$j];
    if ($i > 0 && $G[$i - 1][$j] <= $n) continue;
    if ($i < $ROWS - 1 && $G[$i + 1][$j] <= $n) continue;
    if ($j > 0 && $G[$i][$j - 1] <= $n) continue;
    if ($j < $COLS - 1 && $G[$i][$j + 1] <= $n) continue;

    $part1 += $n + 1;

    // flood-fill the basin around this low point (stops at 9s)...
    $V = [];
    $S = [[$i, $j]];
    while ($S)
    {
        [$y, $x] = array_pop($S);
        if (isset($V[$k = "$y,$x"])) continue;
        if ($y < 0 || $y >= $ROWS || $x < 0 || $x >= $COLS) continue;
        if ($G[$y][$x] == 9) continue;
        $V[$k] = 1;
        $S[] = [$y - 1, $x];
        $S[] = [$y + 1, $x];
        $S[] = [$y, $x - 1];
        $S[] = [$y, $x + 1];
    }
    $B[] = count($V);
}

rsort($B);
$part2 = $B[0] * $B[1] * $B[2];

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: " . round(microtime(true) - $start_time, 4) . " seconds\n";
echo "   Peak memory: " . round(memory_get_peak_usage() / pow(2, 20), 4), " MiB\n\n";
