<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen( $argv[1] ?? "2.input", "r");

$part1 = $part2 = 0;

while (!feof($_fp) && $line = trim(fgets($_fp)))
{
    $box = array_map("intval", explode("x", $line));
    sort($box);
    [$l, $w, $h] = $box;
    [$a, $b, $c] = [$l*$w, $w*$h, $h*$l];
    $part1 += 2*$a + 2*$b + 2*$c + min($a, $b, $c);
    $part2 += 2*$l + 2*$w + $l*$w*$h;
}
fclose($_fp);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
