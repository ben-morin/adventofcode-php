<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "10.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);

$PAIR  = ['(' => ')', '[' => ']', '{' => '}', '<' => '>'];
$SYN   = [')' => 3, ']' => 57, '}' => 1197, '>' => 25137];
$AUTO  = [')' => 1, ']' => 2,  '}' => 3,    '>' => 4];

$part1 = $part2 = 0;
$C = [];

foreach ($F as $line)
{
    if ($line == '') continue;
    $S = [];
    $corrupt = false;
    for ($i = 0, $n = strlen($line); $i < $n; $i++)
    {
        $c = $line[$i];
        if (isset($PAIR[$c]))
            $S[] = $PAIR[$c];
        elseif (array_pop($S) !== $c)
        {
            $part1 += $SYN[$c];
            $corrupt = true;
            break;
        }
    }
    if (!$corrupt && $S)
    {
        $total = 0;
        foreach (array_reverse($S) as $c) $total = $total * 5 + $AUTO[$c];
        $C[] = $total;
    }
}

sort($C);
$part2 = $C[intdiv(count($C), 2)];

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
