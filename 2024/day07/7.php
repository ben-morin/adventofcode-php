<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen($argv[1] ?? "7.input", "r");

$part1 = $part2 = 0;

function test($V, $N, $P2 = false)
{
    if (count($N) == 1) return $V == $N[0];
    $n1 = array_shift($N);
    $n2 = array_shift($N);
    if ($n1 > $V) return false;
    return test($V, [$n1+$n2, ...$N], $P2)
        || test($V, [$n1*$n2, ...$N], $P2)
        || ($P2 && test($V, [intval($n1.$n2), ...$N], $P2));
}

while ($line = trim(fgets($_fp)))
{
    [$V, $N] = explode(": ", $line);
    $V = (int)$V;
    $N = array_map("intval", explode(" ", $N));

    if (test($V, $N)) $part1 += $V;
    if (test($V, $N, true)) $part2 += $V;
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
