<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen( $argv[1] ?? "19.input", "r");

$part1 = $part2 = 0;

$W = [];
while (!feof($_fp) && $line = trim(fgets($_fp)))
{
    [$id, $R] = preg_split("/[{}]/", $line, -1, 1);
    $R = explode(",", $R);
    $R = array_map(fn($a) => preg_split("/(?:([<>])|[:])/", $a, 3, 2), $R);
    $W[$id] = $R;
}

function process($part, $id = "in"): int
{
    global $W;

    if ($id == "R") return 0;
    if ($id == "A") return array_sum($part);

    [$R, [[$final]]] = array_chunk($W[$id], count($W[$id]) - 1);

    foreach ($R as [$key, $op, $num, $next])
        if (($op == "<" && $part[$key] < $num) || ($op == ">" && $part[$key] > $num))
            return process($part, $next);

    return process($part, $final);
}

while (!feof($_fp) && $line = trim(fgets($_fp)))
{
    $part = explode(",", trim($line, "{}"));
    $part = array_combine(['x', 'm', 'a', 's'], array_map(fn($a) => explode("=", $a)[1], $part));
    $part1 += process($part);
}
fclose($_fp);

function range_count($ranges, $id = "in")
{
    global $W;
    if ($id == "R") return 0;
    if ($id == "A") return array_reduce($ranges, fn($c, $r) => $c * ($r[1] - $r[0] + 1), 1);

    [$R, [[$final]]] = array_chunk($W[$id], count($W[$id]) - 1);

    $count = 0;
    foreach ($R as [$key, $op, $num, $next])
    {
        [$low, $high] = $ranges[$key];
        $right = $op == "<" ? [$low, min($high, $num - 1)] : [max($num + 1, $low), $high];
        $wrong = $op == "<" ? [max($num, $low), $high] : [$low, min($high, $num)];

        if ($right[0] <= $right[1]) $count += range_count(array_merge($ranges, [$key => $right]), $next);
        if ($wrong[0] <= $wrong[1]) $ranges[$key] = $wrong; else return $count;
    }
    return $count + range_count($ranges, $final);
}
$part2 = range_count(array_fill_keys(['x', 'm', 'a', 's'], [1, 4000]));

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";