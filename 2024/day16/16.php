<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$G = file($argv[1] ?? "16.input", FILE_IGNORE_NEW_LINES);
assert($G !== false);
$ROWS = count($G);
$COLS = strlen($G[0]);

assert($G[$ROWS-2][1] == "S");
$start = [$ROWS-2, 1];
assert($G[1][$COLS-2] == "E");
$end = [1, $COLS-2];

const DIRS = [[-1, 0], [0, 1], [1, 0], [0, -1]];

$part1 = $part2 = 0;

function search($start, array $start_dirs, $end, &$V)
{
    global $G;
    $V = [];
    $H = new SplMinHeap();
    foreach ($start_dirs as $dir) $H->insert([0, $start[0], $start[1], $dir]);
    $min = INF;
    while (!$H->isEmpty())
    {
        [$cost, $r, $c, $dir] = $H->extract();
        if (isset($V[$key = "$r,$c,$dir"])) continue;
        $V[$key] = $cost;
        if ($r == $end[0] && $c == $end[1])
        {
            if ($cost > $min) break;
            $min = $cost;
        }
        foreach ([$dir, ($dir + 1) % 4, ($dir + 3) % 4] as $_dir)
        {
            [$dr, $dc] = DIRS[$_dir];
            [$_r, $_c] = [$r + $dr, $c + $dc];
            if ($G[$_r][$_c] == "#") continue;
            if ($_dir == $dir)
                $H->insert([$cost + 1, $_r, $_c, $dir]);
            else
                $H->insert([$cost + 1000, $r, $c, $_dir]);
        }
    }
    return $min;
}

$part1 = search($start, [1], $end, $V1);
$V = [];
search($end, [0, 1, 2, 3], $start, $V2);
foreach ($V1 as $key => $cost)
{
    [$r, $c, $dir] = array_map("intval", explode(",", $key));
    $key = "$r,$c,".($dir + 2) % 4;
    if ($cost + ($V2[$key] ?? 0) == $part1) $V["$r,$c"] = 1;
}
$part2 = count($V);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
