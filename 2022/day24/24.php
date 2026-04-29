<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$G = file($argv[1] ?? "24.input", FILE_IGNORE_NEW_LINES);
assert($G !== false);

define("ROWS", count($G));
define("COLS", strlen($G[0]));

const MOVES = [[0, 0], [1, 0], [-1, 0], [0, 1], [0, -1]];

$start_pos = [0, strpos($G[0], '.')];
$end_pos = [ROWS - 1, strpos($G[ROWS - 1], '.')];

function mod($a, $b) { return ($a % $b + $b) % $b; }

function f($start_pos, $end_pos, $t = 0)
{
    global $G;

    static $max_r = ROWS - 2;
    static $max_c = COLS - 2;

    $V = [];
    $Q = [[$t, $start_pos[0], $start_pos[1]]];

    while ($Q)
    {
        [$t, $r, $c] = array_shift($Q);
        // reached end...
        if ([$r, $c] == $end_pos) return $t;
        // check visited cache...
        if (isset($V[$key = "$t,$r,$c"])) continue;
        $V[$key] = 1;
        // possible moves...
        foreach (MOVES as $_m)
        {
            $_r = $r + $_m[0];
            $_c = $c + $_m[1];
            // out of play or on a wall...
            if ($_r < 0 || $_r >= ROWS || $_c < 0 || $_c >= COLS) continue;
            if ($G[$_r][$_c] == '#') continue;
            // check for blizzards...
            if ($G[mod($_r - $t - 2, $max_r) + 1][$_c] == 'v') continue;
            if ($G[mod($_r + $t, $max_r) + 1][$_c] == '^') continue;
            if ($G[$_r][mod($_c - $t - 2, $max_c) + 1] == '>') continue;
            if ($G[$_r][mod($_c + $t, $max_c) + 1] == '<') continue;
            $Q[] = [$t + 1, $_r, $_c];
        }
    }
    assert(false, "no path found");
}

$part1 = f($start_pos, $end_pos);
$part2 = f($start_pos, $end_pos, f($end_pos, $start_pos, $part1));

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: " . round(microtime(true) - $start_time, 4) . " seconds\n";
echo "   Peak memory: " . round(memory_get_peak_usage() / pow(2, 20), 4), " MiB\n\n";
