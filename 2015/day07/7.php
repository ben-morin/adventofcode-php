<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen($argv[1] ?? "7.input", "r");

$W = [];
while ($line = trim(fgets($_fp)))
{
    $line = explode(" ", $line);
    array_unshift($line, ...array_fill(0, 5 - count($line), '='));
    [$a, $op, $b,, $c] = $line;
    $W[$c] = [$a, $op, $b];
}

$C = [];
function f($wire)
{
    global $C, $W;
    if (isset($C[$wire])) return $C[$wire];
    if (is_numeric($wire)) return (int)$wire;

    [$a, $op, $b] = $W[$wire];

    return $C[$wire] = 0xFFFF & match ($op)
    {
        "AND" => f($a) & f($b),
        "OR" => f($a) | f($b),
        "LSHIFT" => f($a) << f($b),
        "RSHIFT" => f($a) >> f($b),
        "NOT" => ~f($b),
        default => f($b),
    };
}

$part1 = f("a");

$C = ["b" => $part1];
$part2 = f("a");

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
