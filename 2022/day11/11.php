<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$M = explode("\n\n", file_get_contents($argv[1] ?? "11.input"));

foreach ($M as $_m => $line)
{
    $l = explode("\n", $line);
    $items = array_map('intval', explode(', ', trim(substr($l[1], 17))));
    $op = explode(' ', substr($l[2], 23)); // [op, val]
    $div = (int)substr($l[3], 21);
    $throw = [(int)substr($l[4], 29), (int)substr($l[5], 30)]; // [true, false]
    $M[$_m] = [$items, $op, $div, $throw];
}

function f($M, $part2 = false)
{
    $LCM = array_product(array_column($M, 2));
    $count = array_fill(0, count($M), 0);
    $rounds = $part2 ? 10000 : 20;
    for ($round = 0; $round < $rounds; $round++)
    {
        for ($i = 0; $i < count($M); $i++) while ($item = array_shift($M[$i][0]))
        {
            $count[$i]++;
            [[$op, $val], $div, $throw] = [$M[$i][1], $M[$i][2], $M[$i][3]];
            $val = ($val == "old") ? $item : (int)$val;
            $item = ($op == "+") ? $item + $val : $item * $val;
            if ($part2)
                $item = $item % $LCM;
            else
                $item = intdiv($item, 3);
            $to = ($item % $div) ? $throw[1] : $throw[0];
            $M[$to][0][] = $item;
        }
    }
    rsort($count);
    return $count[0] * $count[1];
}

$part1 = f($M);
$part2 = f($M, true);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
