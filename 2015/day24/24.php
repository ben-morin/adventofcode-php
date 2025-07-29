<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "24.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);

$F = array_map('intval', $F);
rsort($F);

function combos(int $r): Generator
{
    global $F;
    $n = count($F);
    if ($r > $n || $r == 0) return;
    $idx = range(0, $r - 1);
    while (true)
    {
        yield array_map(fn($i) => $F[$i], $idx);
        $i = $r - 1;
        while ($i >= 0 && $idx[$i] === $i + $n - $r) $i--;
        if ($i < 0) return;
        $idx[$i]++;
        for ($j = $i + 1; $j < $r; $j++)
            $idx[$j] = $idx[$j - 1] + 1;
    }
}

function f($compartments)
{
    global $F;
    $result = INF;
    $target = intdiv(array_sum($F), $compartments);
    $min_pkg = 1;
    $max_pkg = count($F);
    while (array_sum(array_slice($F, 0, $min_pkg)) <= $target) $min_pkg++;
    for ($i = $min_pkg; $i <= $max_pkg; $i++) foreach (combos($i) as $c)
    {
        if (array_sum($c) != $target) continue;
        $result = min($result, array_product($c));
        $max_pkg = $i;
    }
    return $result;
}

$part1 = f(3);
$part2 = f(4);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
