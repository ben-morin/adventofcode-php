<?php

memory_reset_peak_usage();
$start_time = microtime(true);
const DEBUG = false;

$_fp = fopen( $argv[1] ?? "14.input", "r");

$T = 100;
const COLS = 101;
const ROWS = 103;
$MX = intdiv(COLS, 2 );
$MY = intdiv(ROWS, 2 );
const DIR = [[-1,0],[0,1],[1,0],[0,-1]];

$part1 = $part2 = 0;
$QUAD = ["-1,-1" => 0, "1,-1" => 0, "-1,1" => 0, "1,1" => 0];

function MOD($num, $mod) { return ($mod + ($num % $mod)) % $mod; }
function move($x, $y, $vx, $vy, $T = 1)
{
    $x = MOD($x + $vx * $T, COLS);
    $y = MOD($y + $vy * $T, ROWS);
    return [$x, $y];
}

$R = [];
while ($line = trim(fgets($_fp)))
{
    preg_match_all("/(-?\d+)/", $line, $m);
    [$x, $y, $vx, $vy] = array_map('intval', $m[0]);
    [$x, $y] = move($x, $y, $vx, $vy, $T);
    $R[] = [$x, $y, $vx, $vy];
    $key = ($x <=> $MX).",".($y <=> $MY);
    if (!str_contains($key, 0)) $QUAD[$key]++;
}
$part1 = array_product($QUAD);

function fill($G, $x, $y)
{
    $ROWS = count($G);
    $COLS = strlen($G[0]);
    $Q = [[$y,$x]];
    $V = [];
    $C = 0;
    while ($Q)
    {
        [$r, $c] = array_shift($Q);
        if (isset($V[$key = "$r,$c"])) continue;
        $V[$key] = 1;
        $C++;
        foreach (DIR as [$dr, $dc])
        {
            [$_r, $_c] = [$r + $dr, $c + $dc];
            if ($_r < 0 || $_r >= $ROWS || $_c < 0 || $_c >= $COLS) continue;
            if ($G[$_r][$_c] != ".") $Q[] = [$_r, $_c];
        }
    }
    return $C;
}

while ($T < 1e4)
{
    $T++;
    foreach ($R as $key => [$x, $y, $vx, $vy])
    {
        [$x, $y] = move($x, $y, $vx, $vy);
        $R[$key] = [$x, $y, $vx, $vy];
    }
    $G = [];
    foreach (range(0, ROWS-1) as $_) $G[] = str_repeat(".", COLS);
    foreach ($R as [$x, $y, $vx, $vy]) $G[$y][$x] = (int)$G[$y][$x] + 1;
    if (fill($G, $MX, $MY) > intdiv(array_sum($QUAD),3))
    {
        $part2 = $T;
        if (DEBUG) foreach ($G as $g) echo "{$g}\n";
        break;
    };
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
