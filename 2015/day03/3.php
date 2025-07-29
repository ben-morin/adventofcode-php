<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$MOVES = trim(file_get_contents($argv[1] ?? "3.input"));
$D = ["^" => [0, 1], "v" => [0, -1], ">" => [1, 0], "<" => [-1, 0]];

function f($s_num = 1): int
{
    global $MOVES, $D;
    $C = ["0,0" => $s_num];
    $S = array_fill(0, 3, [0,0]);
    for ($n = $i = 0; $i < strlen($MOVES); $i++)
    {
        $n = ++$n % $s_num;
        [$x, $y] = [$S[$n][0] += $D[$MOVES[$i]][0], $S[$n][1] += $D[$MOVES[$i]][1]];
        $C["{$x},{$y}"] = ($C["{$x},{$y}"] ?? 0) + 1;
    }
    return count($C);
}

$part1 = f();
$part2 = f(2);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
