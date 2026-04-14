<?php

memory_reset_peak_usage();
$start_time = microtime(true);

const DEBUG = false;

$M = file_get_contents($argv[1] ?? "10.input");
$M = explode("\n", trim($M));
$S = [0,0,0];

foreach ($M as $y => $r)
{
    $S = [strpos($r, "S"), $y, 0];
    if ($S[0] !== false) break;
}

$part1 = $part2 = 0;

const LEFT = [-1, 0, "FL-"], RIGHT = [1, 0, "-7J"];
const   UP = [0, -1, "F7|"],  DOWN = [0, 1, "LJ|"];

$moves = [
    "S" => [LEFT, RIGHT, UP, DOWN],
    "F" => [RIGHT, DOWN],
    "-" => [LEFT, RIGHT],
    "7" => [LEFT, DOWN],
    "|" => [UP, DOWN],
    "J" => [LEFT, UP],
    "L" => [RIGHT, UP]
];

$V = [];
$s_moves = [];
$Q = [$S];

while ($Q)
{
    [$x, $y, $d] = array_shift($Q);
    $p = $M[$y][$x] ?? ".";
    $V["[{$x},{$y}]"] = $d;
    foreach ($moves[$p] as $move)
    {
        [$dx, $dy, $next] = $move;
        $nx = $x + $dx;
        $ny = $y + $dy;

        if ($nx < 0 || $nx >= strlen($M[0])) continue;
        if (isset($V["[{$nx},{$ny}]"])) continue;
        if (!str_contains($next, $M[$ny][$nx] ?? ".")) continue;

        $Q[] = [$nx, $ny, $d + 1];
        if ($p == "S") $s_moves[] = $move;
    }
}
$M[$S[1]][$S[0]] = array_search($s_moves, $moves);
$part1 = max($V);

foreach ($M as $y => $r)
{
    $crossed = 0;
    for ($x = 0; $x < strlen($r); $x++)
    {
        // if the point is in V it's a pipe...
        if (isset($V["[{$x},{$y}]"]))
        {
            if (str_contains("|LJ", $r[$x])) $crossed++;
            continue;
        }
        $M[$y][$x] = $crossed % 2 ? "I" : "O";
        $part2 += $crossed % 2 ? 1 : 0;
    }
}

if (DEBUG) echo implode("\n", $M), "\n\n";

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
