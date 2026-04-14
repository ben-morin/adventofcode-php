<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "6.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);

$part1 = $part2 = 0;

$P = array_map(fn($line) => preg_split("/\s+/", trim($line)), $F);

for ($c = 0; $c < count($P[0]); $c++)
{
    $terms = array_column($P, $c);
    $op = array_pop($terms);
    $part1 += ($op == "+") ? array_sum($terms) : array_product($terms);
}

$P = array_reverse(array_map(null, ...array_map("str_split", $F)));

for ($i = 0, $terms = []; $i <= count($P); $i++)
{
    $_term = $P[$i] ?? [];
    if ($value = (int)trim(implode($_term)))
    {
        $op = array_pop($_term);
        $terms[] = $value;
        continue;
    }
    $part2 += ($op == "+") ? array_sum($terms) : array_product($terms);
    $terms = [];
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
