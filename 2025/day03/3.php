<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "3.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);

function f($bank, $size = 2)
{
    $J = "";
    while (strlen($J) < $size && $bank)
    {
        $limit = strlen($bank) - ($size - strlen($J)) + 1;
        for ($max = 0, $i = 1; $i < $limit; $i++)
        {
            if ($bank[$i] > $bank[$max]) $max = $i;
            if ($bank[$max] == 9) break;
        }
        $J .= $bank[$max];
        $bank = substr($bank, $max + 1);
    }
    return (int)$J;
}

$part1 = array_sum(array_map('f', $F));
$part2 = array_sum(array_map(fn($bank) => f($bank, 12), $F));

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
