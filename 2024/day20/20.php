<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$G = file($argv[1] ?? "20.input", FILE_IGNORE_NEW_LINES);
assert($G !== false);
$ROWS = count($G);
$COLS = strlen($G[0]);

$start = $end = [];
for ($r = 0; $r < $ROWS; $r++) for ($c = 0; $c < $COLS; $c++)
{
    if ($G[$r][$c] == "S") { $start = [$r, $c]; $G[$r][$c] = "."; }
    if ($G[$r][$c] == "E") { $end = [$r, $c]; $G[$r][$c] = "."; }
    if ($start && $end) break 2;
}

const DIRS = [[-1, 0], [0, 1], [1, 0], [0, -1]];

$part1 = $part2 = 0;

[[$r, $c], $dist] = [$start, 0];
$V = ["$r,$c" => $dist];
while (true)
{
    if ($r == $end[0] && $c == $end[1]) break;
    foreach (DIRS as [$dr, $dc])
    {
        [$_r, $_c] = [$r + $dr, $c + $dc];
        if ($G[$_r][$_c] == "#" || isset($V["$_r,$_c"])) continue;
        $V["$_r,$_c"] = ++$dist;
        [$r, $c] = [$_r, $_c];
        break;
    }
}

function f($time, $min = 100): int
{
    global $V;
    $result = 0;
    foreach ($V as $key => $dist)
    {
        [$r, $c] = array_map("intval", explode(",", $key));
        for ($_r = $r - $time, $rtime = $r + $time; $_r <= $rtime; $_r++)
            for ($_c = $c - $time, $ctime = $c + $time; $_c <= $ctime; $_c++)
                if (($_d = abs($r - $_r) + abs($c - $_c)) <= $time)
                    if (($V["$_r,$_c"] ?? 0) - $dist - $_d >= $min) $result++;
    }
    return $result;
}

$part1 = f(2);
$part2 = f(20);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
