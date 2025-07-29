<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "17.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);
rsort($F);

$part1 = $part2 = 0;

function f($current = [], $next = 0, $target = 150): Generator
{
    global $F;
    $sum = array_sum($current);
    if (array_sum($current) == $target)
        yield $current;
    for ($i = $next; $i < count($F); $i++) if ($sum + $F[$i] <= $target)
    {
        $current[] = $F[$i];
        yield from f($current, $i + 1);
        array_pop($current);
    }
}

$min = PHP_INT_MAX;
foreach (f() as $c)
{
    $part1++;
    if (count($c) < $min)
    {
        $min = count($c);
        $part2 = 1;
    }
    elseif (count($c) == $min) $part2++;
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
