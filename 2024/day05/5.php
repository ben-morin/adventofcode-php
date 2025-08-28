<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = explode("\n\n", file_get_contents($argv[1] ?? "5.input"));
$P = array_flip(explode("\n", $F[0]));
$U = explode("\n", $F[1]);

$part1 = $part2 = 0;

foreach ($U as $update)
{
    $update = array_map("intval", explode(",", $update));

    $good = true;
    for ($i = 0; $i < count($update) - 1; $i++)
        for ($j = $i + 1; $j < count($update); $j++)
            if (!isset($P["{$update[$i]}|{$update[$j]}"])) { $good = false; break 2; };
    if ($good) { $part1 += $update[intdiv(count($update), 2)]; continue; }

    $counts = array_combine($update, array_fill(0, count($update), 0));
    foreach ($update as $l) foreach ($update as $r)
        if (isset($P["{$l}|{$r}"])) $counts[$l]++;
    arsort($counts);
    $part2 += array_keys($counts)[intdiv(count($counts), 2)];
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
