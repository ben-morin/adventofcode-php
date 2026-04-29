<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$lines = file_get_contents($argv[1] ?? "11.input");
$lines = explode("\n", trim($lines));
$lines = array_map(fn($r)=>str_split($r), $lines);

$empty_cols = [];
for ($x = $dx = 0; $x < count($lines[0]); $x++)
{
    $col = implode(array_column($lines, $x));
    if (substr_count($col, ".") == strlen($col)) $dx++;
    $empty_cols[$x] = $dx;
}

$P = $empty_rows = [];
for ($y = $dy = 0; $y < count($lines); $y++)
{
    $line = $lines[$y];
    if (substr_count(implode("", $line), ".") == count($line)) $dy++;
    $empty_rows[$y] = $dy;
    foreach ($line as $x => $ch) if ($ch == "#") $P[] = [$x, $y];
}
unset($lines);

$part1 = $part2 = 0;
$expansion = [2, 1e6];

foreach ([0, 1] as $part)
{
    $C = [];
    for ($a = 0; $a < count($P); $a++) for ($b = $a+1; $b < count($P); $b++)
    {
        [$p1, $p2] = [min($a, $b), max($a, $b)];
        if (isset($C["{$p1},{$p2}"])) continue;
        [$x1, $y1, $x2, $y2] = [$P[$p1][0], $P[$p1][1], $P[$p2][0], $P[$p2][1]];
        $x1 += $empty_cols[$x1] * ($expansion[$part]-1);
        $y1 += $empty_rows[$y1] * ($expansion[$part]-1);
        $x2 += $empty_cols[$x2] * ($expansion[$part]-1);
        $y2 += $empty_rows[$y2] * ($expansion[$part]-1);
        $C["{$p1},{$p2}"] = abs($x2 - $x1) + abs($y2 - $y1);
    }
    if ($part) $part2 = array_sum($C); else $part1 = array_sum($C);
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
