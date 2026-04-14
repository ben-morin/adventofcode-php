<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$G = file($argv[1] ?? "7.input", FILE_IGNORE_NEW_LINES);
assert($G !== false);

define("ROWS", count($G));
$start = [0, strpos($G[0], 'S')];

$part1 = $part2 = 0;

function f($r, $c)
{
    global $G, $part1;
    static $CACHE = [];
    return match (true)
    {
        isset($CACHE[$key = "$r,$c"]) => $CACHE[$key],
        $r + 1 == ROWS => $CACHE[$key] = 1,
        $G[$r + 1][$c] == '^' => $CACHE[$key] = (function() use($r, $c, &$part1)
        {
            $part1++;
            return f($r + 1, $c - 1) + f($r + 1, $c + 1);
        })(),
        default => $CACHE[$key] = f($r + 1, $c),
    };
}

$part2 = f(...$start);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: " . round(microtime(true) - $start_time, 4) . " seconds\n";
echo "   Peak memory: " . round(memory_get_peak_usage() / pow(2, 20), 4), " MiB\n\n";
