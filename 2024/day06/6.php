<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$G = file($argv[1] ?? "6.input", FILE_IGNORE_NEW_LINES);
assert($G !== false);

$part1 = $part2 = 0;

$start = [0, 0, 0];
foreach ($G as $r => $s) if (str_contains($s, "^"))
    $start = [$r, strpos($s, "^"), 0];

function run($G, $start, $P2 = false)
{
    $ROWS = count($G);
    $COLS = strlen($G[0]);
    $DR = [-1, 0, 1, 0];
    $DC = [0, 1, 0, -1];
    $V = [];
    $O = 0;

    [$r, $c, $dir] = $start;

    while (true)
    {
        $key = ($r << 10) | ($c << 2);
        if ($P2)
        {
            $key |= $dir;
            if (isset($V[$key])) return 1;
        }
        else if ($V && !isset($V[$key]))
        {
            $_G = $G;
            $_G[$r][$c] = "#";
            $O += run($_G, $start, true);
        }
        $V[$key] = 1;

        $_r = $r + $DR[$dir];
        $_c = $c + $DC[$dir];

        if ($_r < 0 || $_r >= $ROWS || $_c < 0 || $_c >= $COLS)
            return $P2 ? 0 : [count($V), $O];

        if ($G[$_r][$_c] != "#")
        {
            $start = [$r, $c, $dir];
            $r = $_r;
            $c = $_c;
        }
        else $dir = ++$dir % 4;
    }
}

[$part1, $part2] = run($G, $start);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
