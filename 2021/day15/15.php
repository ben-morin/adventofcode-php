<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$G = file($argv[1] ?? "15.input", FILE_IGNORE_NEW_LINES);
assert($G !== false);

$ROWS = count($G);
$COLS = strlen($G[0]);

const DIRS = [[-1,0],[1,0],[0,-1],[0,1]];

function risk($y, $x)
{
    global $G, $ROWS, $COLS;
    $v = $G[$y % $ROWS][$x % $COLS] + intdiv($y, $ROWS) + intdiv($x, $COLS);
    return ($v - 1) % 9 + 1;
}

function dijkstra($rows, $cols)
{
    $dist = ["0,0" => 0];
    $Q = new SplPriorityQueue();
    $Q->insert([0, 0], 0);

    while (!$Q->isEmpty())
    {
        [$y, $x] = $Q->extract();
        $k = "$y,$x";
        if ($y == $rows - 1 && $x == $cols - 1) return $dist[$k];
        $d = $dist[$k];
        foreach (DIRS as [$dy, $dx])
        {
            $_y = $y + $dy;
            $_x = $x + $dx;
            if ($_y < 0 || $_y >= $rows || $_x < 0 || $_x >= $cols) continue;
            $_k = "$_y,$_x";
            $_d = $d + risk($_y, $_x);
            if (!isset($dist[$_k]) || $_d < $dist[$_k])
            {
                $dist[$_k] = $_d;
                $Q->insert([$_y, $_x], -$_d);
            }
        }
    }
}

$part1 = dijkstra($ROWS, $COLS);
$part2 = dijkstra($ROWS * 5, $COLS * 5);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
