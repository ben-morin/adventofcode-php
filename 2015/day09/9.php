<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen($argv[1] ?? "9.input", "r");

$G = [];
while ($line = trim(fgets($_fp)))
{
    preg_match("/^(\w+) to (\w+) = (\d+)$/", $line, $m);
    $G[$m[1]][$m[2]] = (int)$m[3];
    $G[$m[2]][$m[1]] = (int)$m[3];
}
$N = array_keys($G);

function p(array &$a, $k = null)
{
    if (is_null($k)) $k = count($a);
    if ($k <= 1) { yield $a; return; }
    yield from p($a, $k - 1);
    for ($i = 0; $i < $k - 1; $i++)
    {
        if ($k & 1)
            [$a[0], $a[$k - 1]] = [$a[$k - 1], $a[0]];
        else
            [$a[$i], $a[$k - 1]] = [$a[$k - 1], $a[$i]];
        yield from p($a, $k - 1);
    }
}

$part1 = PHP_INT_MAX;
$part2 = -1;

foreach (p($N) as $n)
{
    for ($i = $dist = 0; $i < count($n) - 1; $i++)
        $dist += $G[$n[$i]][$n[$i + 1]];
    $part1 = min($part1, $dist);
    $part2 = max($part2, $dist);
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
