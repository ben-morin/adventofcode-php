<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen($argv[1] ?? "13.input", "r");

$G = [];
while ($line = trim(fgets($_fp)))
{
    preg_match("/^(\w+) would (gain|lose) (\d+) happiness units by sitting next to (\w+).$/", $line, $m);
    $G[$m[1]][$m[4]] = (int)$m[3] * ($m[2] == "gain" ? 1 : -1);
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

function f($N)
{
    global $G;
    $result = PHP_INT_MIN;
    foreach (p($N) as $n)
    {
        $score = ($G[$n[0]][$n[count($n) - 1]] ?? 0) + ($G[$n[count($n) - 1]][$n[0]] ?? 0);
        for ($i = 0; $i < count($n) - 1; $i++)
            $score += ($G[$n[$i]][$n[$i + 1]] ?? 0) + ($G[$n[$i + 1]][$n[$i]] ?? 0);
        $result = max($result, $score);
    }
    return $result;
}

$part1 = f($N);
$part2 = f(array_merge($N, ["Self"]));

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
