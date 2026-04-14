<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$G = file($argv[1] ?? "18.input", FILE_IGNORE_NEW_LINES);
assert($G !== false);

$part1 = $part2 = 0;

function f($G, $part2 = false)
{
    $ROW = count($G);
    $COL = strlen($G[0]);
    $DIR = [[-1, -1], [-1, 0], [-1, 1], [0, -1], [0, 1], [1, -1], [1, 0], [1, 1]];
    $corners = function($part2) use (&$G, $ROW, $COL)
    {
        if ($part2) $G[0][0] = $G[0][$COL - 1] = $G[$ROW - 1][0] = $G[$ROW - 1][$COL - 1] = '#';
    };
    for ($i = 0; $i < 100; $i++)
    {
        $corners($part2);
        $_G = [];
        for ($r = 0; $r < $ROW; $r++)
        {
            $_row = '';
            for ($c = 0; $c < $COL; $c++)
            {
                $n = 0;
                foreach ($DIR as $d)
                {
                    $_r = $r + $d[0];
                    $_c = $c + $d[1];
                    if ($_r < 0 || $_r >= $ROW || $_c < 0 || $_c >= $COL) continue;
                    $n += (int)($G[$_r][$_c] === '#');
                }
                if ($G[$r][$c] == '#')
                    $_row .= ($n == 2 || $n == 3) ? '#' : '.';
                else
                    $_row .= ($n == 3) ? '#' : '.';
            }
            $_G[] = $_row;
        }
        $G = $_G;
    }
    $corners($part2);
    return substr_count(implode($G), '#');
}

$part1 = f($G);
$part2 = f($G, true);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
