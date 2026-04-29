<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "12.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);

$G = [];
foreach ($F as $line)
{
    [$a, $b] = explode('-', $line);
    if ($a != 'end' && $b != 'start') $G[$a][] = $b;
    if ($b != 'end' && $a != 'start') $G[$b][] = $a;
}

$part1 = $part2 = 0;

function dfs($node, $visited = [], $twice = false)
{
    global $G, $part1, $part2;
    if ($node == 'end')
    {
        $part2++;
        if (!$twice) $part1++;
        return;
    }
    if ($node == strtolower($node)) $visited[$node] = 1;
    foreach ($G[$node] as $next)
    {
        if (!isset($visited[$next]))
            dfs($next, $visited, $twice);
        elseif (!$twice)
            dfs($next, $visited, true);
    }
}

dfs('start');

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
