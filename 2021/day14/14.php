<?php

memory_reset_peak_usage();
$start_time = microtime(true);

[$T, $F] = explode("\n\n", trim(file_get_contents($argv[1] ?? "14.input")));

$PAIRS = [];
$COUNT = [$T[0] => 1];
for ($i = 1; $i < strlen($T); $i++)
{
    $COUNT[$T[$i]] = ($COUNT[$T[$i]] ?? 0) + 1;
    $p = $T[$i-1].$T[$i];
    $PAIRS[$p] = ($PAIRS[$p] ?? 0) + 1;
}

$RULE = [];
foreach (explode("\n", $F) as $_rule)
{
    [$p, $e] = explode(" -> ", $_rule);
    $RULE[$p] = [$e, $p[0].$e, $e.$p[1]];
}

$part1 = $part2 = 0;
for ($step = 1; $step <= 40; $step++)
{
    $_pairs = [];
    foreach ($PAIRS as $p => $n)
    {
        [$c, $left, $right] = $RULE[$p];
        $COUNT[$c] = ($COUNT[$c] ?? 0) + $n;
        $_pairs[$left]  = ($_pairs[$left] ?? 0) + $n;
        $_pairs[$right] = ($_pairs[$right] ?? 0) + $n;
    }
    $PAIRS = $_pairs;
    if ($step == 10) $part1 = max($COUNT) - min($COUNT);
}
$part2 = max($COUNT) - min($COUNT);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
