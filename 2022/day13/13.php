<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = explode("\n\n", file_get_contents($argv[1] ?? "13.input"));

function compare($l, $r): int
{
    $l = json_decode($l);
    $r = json_decode($r);
    if (is_int($l) && is_int($r)) return $l <=> $r;
    if (is_int($l)) $l = [$l];
    if (is_int($r)) $r = [$r];
    while (count($l) && count($r))
    {
        $_l = json_encode(array_shift($l));
        $_r = json_encode(array_shift($r));
        if ($result = compare($_l, $_r)) return $result;
    }
    return count($l) <=> count($r);
}

$part1 = $part2 = 0;
$P = [];

foreach ($F as $i => $_pair)
{
    [$L, $R] = explode("\n", trim($_pair));
    array_push($P, trim($L), trim($R));
    if (compare($L, $R) < 1) $part1 += $i + 1;
}

array_push($P, '[[2]]', '[[6]]');
usort($P, "compare");
$part2 = (array_search("[[2]]", $P) + 1) * (array_search("[[6]]", $P) + 1);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
