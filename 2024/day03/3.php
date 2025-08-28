<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file_get_contents($argv[1] ?? "3.input");

$part1 = $part2 = 0;

$do = true;
if (preg_match_all("/(mul\(\d{1,3},\d{1,3}\)|do\(\)|don't\(\))/", $F, $matches))
    foreach ($matches[1] as $m)
        if ($m[0] == "d")
            $do = ($m == "do()");
        else
        {
            $m = explode(",", substr($m, 4, -1));
            $part1 += array_product($m);
            if ($do) $part2 += array_product($m);
        }

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
