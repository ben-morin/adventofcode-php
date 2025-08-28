<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen( $argv[1] ?? "18.input", "r");
$RC = (str_contains(stream_get_meta_data($_fp)["uri"], "example")) ? 7 : 71;
$BC = ($RC == 7 ? 12 : 1024);
$G = array_fill(0, $RC, str_repeat(".", $RC));

const DIRS = [[-1, 0], [0, 1], [1, 0], [0, -1]];

$part1 = $part2 = 0;

$i = 0;
while (++$i && $line = trim(fgets($_fp)))
{
    [$x,$y] = array_map("intval", explode(",", $line));
    $G[$y][$x] = "#";
    if ($i < $BC) continue;
    if (isset($path) && !str_contains($path, "$y,$x")) continue;

    $V = [];
    $H = new SplMinHeap();
    $H->insert([0, 0, 0, "0,0"]);
    while (!$H->isEmpty())
    {
        [$dist, $r, $c, $path] = $H->extract();
        if ($r == $RC - 1 && $c == $RC - 1)
        {
            if ($i == $BC) $part1 = $dist;
            continue 2;
        }
        if (isset($V[$key = "$r,$c"])) continue;
        $V[$key] = $dist;
        foreach (DIRS as [$dr, $dc])
        {
            [$_r, $_c] = [$r + $dr, $c + $dc];
            if ($_r < 0 || $_r >= $RC || $_c < 0 || $_c >= $RC || $G[$_r][$_c] == "#") continue;
            if (isset($V[$key = "$_r,$_c"])) continue;
            $H->insert([$dist + 1, $_r, $_c, $path."|$_r,$_c"]);
        }
    }
    $part2 = "$x,$y";
    break;
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
