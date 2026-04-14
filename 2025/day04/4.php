<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$G = file($argv[1] ?? "4.input", FILE_IGNORE_NEW_LINES);
assert($G !== false);

define("ROWS", count($G));
define("COLS", strlen($G[0]));

const DIRS = [[1,0], [0,1], [-1,0], [0,-1], [1,1], [1,-1], [-1,1], [-1,-1]];

$part1 = $part2 = 0;

while (true)
{
    $removed = [];
    for ($r = 0; $r < ROWS; $r++) for ($c = 0; $c < COLS; $c++)
    {
        if ($G[$r][$c] != "@") continue;
        $n = 0;
        foreach (DIRS as [$dr, $dc])
        {
            $_r = $r + $dr;
            $_c = $c + $dc;
            if ($_r < 0 || $_r >= ROWS || $_c < 0 || $_c >= COLS) continue;
            if ($G[$_r][$_c] == "@") $n++;
            if ($n > 3) continue 2;
        }
        $removed[] = [$r, $c];
    }
    if (!$removed) break;
    if (!$part1) $part1 = count($removed);
    $part2 += count($removed);
    foreach ($removed as [$r, $c]) $G[$r][$c] = ".";
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
