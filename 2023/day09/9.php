<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen( $argv[1] ?? "9.input", "r");

$part1 = $part2 = 0;

while (!feof($_fp))
{
    $s = explode(" ", trim(fgets($_fp)));
    $part1 += move($s);
    $part2 += move(array_reverse($s));
}
fclose($_fp);

function move($s)
{
    $t = [];
    for($i = 1; $i < count($s); $i++) $t[] = $s[$i] - $s[$i-1];
    if (array_sum($t) == 0 && count(array_count_values($t)) == 1)
        return $s[array_key_last($s)];
    else
        return $s[array_key_last($s)] + move($t);
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
