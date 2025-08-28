<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "22.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);

$part1 = $part2 = 0;

const COUNT = 2000;
const PRUNE = 16777216;

$V = [];
foreach ($F as $n)
{
    $N = [$n];
    for ($i = 0; $i < COUNT; $i++)
    {
        $n = ($n ^ ($n <<  6)) % PRUNE; //  n * 64
        $n = ($n ^ ($n >>  5)) % PRUNE; //  n // 32
        $n = ($n ^ ($n << 11)) % PRUNE; //  n * 2048
        $N[] = $n;
    }
    $part1 += end($N);

    $N = array_map(fn($n) => $n % 10, $N);
    for ($C = [], $i = 0, $_n = count($N) - 1; $i < $_n; $i++) $C[] = $N[$i + 1] - $N[$i];
    for ($S = [], $i = 0, $_c = count($C) - 3; $i < $_c; $i++)
    {
        $key = "{$C[$i]},{$C[$i+1]},{$C[$i+2]},{$C[$i+3]}";
        if (!isset($S[$key])) $S[$key] = $N[$i + 4];
    }
    foreach ($S as $key => $v) $V[$key] = ($V[$key] ?? 0) + $v;
}
$part2 = max($V);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
