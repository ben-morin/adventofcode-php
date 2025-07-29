<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen($argv[1] ?? "5.input", "r");

$part1 = $part2 = 0;

while (!feof($_fp) && $s = trim(fgets($_fp)))
{
    if (preg_match_all("/[aeiou]/", $s) >= 3)
        if (preg_match("/(.)\\1/", $s))
            if (!preg_match("/ab|cd|pq|xy/", $s))
                $part1++;

    if (preg_match("/(..).*\\1/", $s))
        if (preg_match("/(.).\\1/", $s))
            $part2++;
}
fclose($_fp);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
