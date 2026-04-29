<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$part1 = $part2 = 0;

$_fp = fopen( $argv[1] ?? "2.input", "r");

function is_safe(array $level): bool
{
    $sign = $level[1] <=> $level[0];
    for ($i = 1; $i < count($level); $i++)
    {
        $d = ($level[$i] - $level[$i - 1]) * $sign;
        if ($d < 1 || $d > 3) return false;
    }
    return true;
}

while ($line = trim(fgets($_fp)))
{
    $level = array_map("intval", explode(" ", $line));
    if (is_safe($level))
    {
        $part1++;
        $part2++;
    }
    else for ($i = 0; $i < count($level); $i++)
    {
        $_level = $level;
        unset($_level[$i]);
        if (is_safe(array_values($_level)))
        {
            $part2++;
            continue 2;
        }
    }
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
