<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$S = file($argv[1] ?? "16.input", FILE_IGNORE_NEW_LINES);
assert($S !== false);

$part1 = $part2 = 0;

function f(string $key, int $x, $part2 = false): bool
{
    return match ($key)
    {
        'children' => $x == 3,
        'cats' => ($part2 ? $x > 7 : $x == 7),
        'samoyeds', 'cars' => $x == 2,
        'pomeranians' => ($part2 ? $x < 3 : $x == 3),
        'akitas', 'vizslas' => $x == 0,
        'goldfish' => ($part2 ? $x < 5 : $x == 5),
        'trees' => ($part2 ? $x > 4 : $x == 4),
        'perfumes' => $x == 1,
        default => false,
    };
}

foreach ($S as $i => $line)
{
    preg_match('/Sue (\d+):/', $line, $sue);
    $sue = $sue[1];
    preg_match_all("/(\w+): (\d+)/", $line, $m, PREG_SET_ORDER);
    // part 1...
    $count = array_sum(array_map(fn($x) => f($x[1], $x[2]), $m));
    if (count($m) == $count) $part1 = $sue;
    // part 2...
    $count = array_sum(array_map(fn($x) => f($x[1], $x[2], true), $m));
    if (count($m) == $count) $part2 = $sue;
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
