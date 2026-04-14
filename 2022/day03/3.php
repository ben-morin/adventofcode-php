<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen( $argv[1] ?? "3.input", "r");

$part1 = $part2 = 0;

while (!feof($_fp))
{
    for ($g = [], $i = 0; $i < 3; $i++)
    {
        $g[] = $a = str_split(trim(fgets($_fp)));
        $l = intdiv(count($a), 2);
        $b = array_chunk($a, $l);
        $c = array_values(array_unique(array_intersect(...$b)))[0];
        $part1 += ord($c)-(ctype_upper($c) ? 38 : 96);
    }
    $d = array_values(array_unique(array_intersect(...$g)))[0];
    $part2 += ord($d)-(ctype_upper($d) ? 38 : 96);
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
