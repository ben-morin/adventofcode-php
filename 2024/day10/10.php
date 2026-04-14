<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$G = file($argv[1] ?? "10.input", FILE_IGNORE_NEW_LINES);
const DIR = [[-1,0],[0,1],[1,0],[0,-1]];

$part1 = $part2 = 0;

function bfs($start, $p2 = false)
{
    global $G;
    $ROWS = count($G);
    $COLS = strlen($G[0]);
    $Q = [$start];
    $V = [];
    $result = 0;
    while ($Q)
    {
        [$r, $c] = array_shift($Q);
        if (!$p2)
        {
            if (isset($V["$r,$c"])) continue;
            $V["$r,$c"] = 1;
        }

        if ($G[$r][$c] == 9) { $result++; continue; }

        foreach (DIR as [$dr,$dc])
        {
            [$_r, $_c] = [$r + $dr, $c + $dc];
            if ($_r < 0 || $_r >= $ROWS || $_c < 0 || $_c >= $COLS) continue;
            if ($G[$_r][$_c] == $G[$r][$c] + 1) $Q[] = [$_r,$_c];
        }
    }
    return $result;
}

for ($r = 0; $r < count($G); $r++) for ($c = 0; $c < strlen($G[$r]); $c++)
{
    if ($G[$r][$c] != 0) continue;
    $part1 += bfs([$r,$c]);
    $part2 += bfs([$r,$c], true);
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
