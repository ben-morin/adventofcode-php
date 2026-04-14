<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "21.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);

$part1 = $part2 = 0;

$NUM = ["789", "456", "123", " 0A"];
$NUMKEY = [];
foreach ($NUM as $r => $row) foreach (str_split($row) as $c => $v) if ($v !== " ") $NUMKEY[$v] = [$r, $c];

$PAD = [" ^A", "<v>"];
$PADKEY = [];
foreach ($PAD as $r => $row) foreach (str_split($row) as $c => $v) if ($v !== " ") $PADKEY[$v] = [$r, $c];

define("PADS", [[$NUM, $NUMKEY], [$PAD, $PADKEY]]);

const DIRS = ["^" => [-1, 0], ">" => [0, 1], "v" => [1, 0], "<" => [0, -1]];

function search($start, $end, $pads = 0)
{
    [$G, $G_KEY] = PADS[$pads];

    $ROWS = count($G);
    $COLS = strlen($G[0]);
    $Q = [[$start, 0, ""]];
    $V = [$start => 0];
    $perms = [];
    while ($Q)
    {
        [$current, $dist, $path] = array_shift($Q);
        [$r, $c] = $G_KEY[$current];
        if ($current === $end)
        {
            $perms[] = $path . "A";
            continue;
        }
        foreach (DIRS as $dir => $d)
        {
            [$dr, $dc] = $d;
            $_r = $r + $dr;
            $_c = $c + $dc;
            if ($_r < 0 || $_r >= $ROWS || $_c < 0 || $_c >= $COLS) continue;
            if (($next = $G[$_r][$_c]) === " ") continue;
            if (isset($V[$next]) && $V[$next] < $dist + 1) continue;
            $V[$next] = $dist + 1;
            $Q[] = [$next, $dist + 1, $path . $dir];
        }
    }
    return $perms;
}

function f($code, $depth, $pads = 0)
{
    static $CACHE = [];
    if (isset($CACHE[$k = "$code,$depth"])) return $CACHE[$k];
    $result = 0;
    for ($i = 0; $i < strlen($code); $i++)
    {
        $perms = search($code[$i-1] ?? "A", $code[$i], $pads);
        if ($depth == 0)
            $result += min(array_map("strlen", $perms));
        else
        {
            $min = INF;
            foreach ($perms as $p) $min = min($min, f($p, $depth - 1, 1));
            $result += $min;
        }
    }
    $CACHE[$k] = $result;
    return $result;
}

foreach ($F as $line)
{
    $part1 += f($line, 2) * (int)$line;
    $part2 += f($line, 25) * (int)$line;
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
