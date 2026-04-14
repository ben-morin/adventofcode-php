<?php

memory_reset_peak_usage();
$start_time = microtime(true);


$F = explode("\n\n", rtrim(file_get_contents($argv[1] ?? '22.input')));
$G = explode("\n", $F[0]);
preg_match_all('/\d+|[RL]/', $F[1], $m);
$P = $m[0];

$ROWS = count($G);
$COLS = array_reduce($G, fn($max, $r) => max($max, strlen($r)), 0);

for ($r = 0; $r < $ROWS; $r++)
    $G[$r] .= str_repeat(' ', $COLS - strlen($G[$r]));

const DIR = [[0,1], [1,0], [0,-1], [-1,0]]; // right, down, left, up

if ($ROWS == 12) // example input...
{
    define("CUBE", intdiv($ROWS, 3));
    define("SIDE", [[0, 2], [1, 0], [1, 1], [1, 2], [2, 2], [2, 3]]);
    define("WRAP", [
        "1,0" => [6, 2], "1,1" => [4, 1], "1,2" => [3, 1], "1,3" => [2, 1],
        "2,0" => [3, 0], "2,1" => [5, 3], "2,2" => [6, 3], "2,3" => [1, 1], // . . 1 .
        "3,0" => [4, 0], "3,1" => [5, 0], "3,2" => [2, 2], "3,3" => [1, 0], // 2 3 4 .
        "4,0" => [6, 1], "4,1" => [5, 1], "4,2" => [3, 2], "4,3" => [1, 3], // . . 5 6
        "5,0" => [6, 0], "5,1" => [2, 3], "5,2" => [3, 3], "5,3" => [4, 3],
        "6,0" => [1, 2], "6,1" => [2, 0], "6,2" => [5, 2], "6,3" => [4, 2],
    ]);
}
else // actual input...
{
    define("CUBE", intdiv($COLS, 3));
    define("SIDE", [[0, 1], [0, 2], [1, 1], [2, 1], [2, 0], [3, 0]]);
    define("WRAP", [
        "1,0" => [2, 0], "1,1" => [3, 1], "1,2" => [5, 0], "1,3" => [6, 0],
        "2,0" => [4, 2], "2,1" => [3, 2], "2,2" => [1, 2], "2,3" => [6, 3], // . 1 2
        "3,0" => [2, 3], "3,1" => [4, 1], "3,2" => [5, 1], "3,3" => [1, 3], // . 3 .
        "4,0" => [2, 2], "4,1" => [6, 2], "4,2" => [5, 2], "4,3" => [3, 3], // 5 4 .
        "5,0" => [4, 0], "5,1" => [6, 1], "5,2" => [1, 0], "5,3" => [3, 0], // 6 . .
        "6,0" => [4, 3], "6,1" => [2, 1], "6,2" => [1, 1], "6,3" => [5, 3],
    ]);
}

function to_grid($r, $c, $side)
{
    [$_r, $_c] = SIDE[$side - 1];
    return [$_r * CUBE + $r, $_c * CUBE + $c];
}

function to_side($r, $c)
{
    foreach (SIDE as $i => [$_r, $_c])
        if ($r >= $_r * CUBE && $r < ($_r + 1) * CUBE && $c >= $_c * CUBE && $c < ($_c + 1) * CUBE)
            return [$i + 1, $r - $_r * CUBE, $c - $_c * CUBE];
    assert(false);
}

function cube_wrap($r, $c, $d)
{
    [$side, $sr, $sc] = to_side($r, $c);
    [$_side, $_d] = WRAP["$side,$d"];
    $x = match ($d)
    {
        0 => $sr,
        1 => CUBE - 1 - $sc,
        2 => CUBE - 1 - $sr,
        3 => $sc
    };
    [$_r, $_c] = match ($_d)
    {
        0 => [$x, 0],
        1 => [0, CUBE - 1 - $x],
        2 => [CUBE - 1 - $x, CUBE - 1],
        3 => [CUBE - 1, $x]
    };
    return [...to_grid($_r, $_c, $_side), $_d];
}

function wrap($r, $c, $d, $part2 = false)
{
    global $G, $ROWS, $COLS;

    if ($part2) return cube_wrap($r, $c, $d);

    [$dr, $dc] = DIR[$d];
    $_r = ($r + $dr + $ROWS) % $ROWS;
    $_c = ($c + $dc + $COLS) % $COLS;
    while ($G[$_r][$_c] === ' ')
    {
        $_r = ($_r + $dr + $ROWS) % $ROWS;
        $_c = ($_c + $dc + $COLS) % $COLS;
    }
    return [$_r, $_c, $d];
}

function f($part2 = false)
{
    global $G, $ROWS, $COLS, $P;

    $r = $d = 0;
    $c = strpos($G[0], '.');

    foreach ($P as $n)
    {
        if ($n == "L" || $n == "R")
        {
            $d = ($d + ($n == "R" ? 1 : 3)) % 4;
            continue;
        }

        for ($i = 0; $i < $n; $i++)
        {
            [$dr, $dc] = DIR[$d];
            // next pos...
            [$_r, $_c, $_d] = [$r + $dr, $c + $dc, $d];
            // out of bounds or space?
            if (($_r < 0) || ($_r >= $ROWS) || ($_c < 0) || ($_c >= $COLS) || $G[$_r][$_c] == ' ')
                [$_r, $_c, $_d] = wrap($r, $c, $d, $part2);
            // wall?
            if ($G[$_r][$_c] == '#') break;
            // move...
            [$r, $c, $d] = [$_r, $_c, $_d];
        }
    }

    return ($r + 1) * 1000 + ($c + 1) * 4 + $d;
}

$part1 = f();
$part2 = f(true);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
