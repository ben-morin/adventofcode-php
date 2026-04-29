<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen( $argv[1] ?? "18.input", "r");

$M = [];
while ($line = trim(fgets($_fp)))
{
    [$dir, $dist, $hex] = explode(" ", $line);
    $dir2 = ['R','D','L','U'][substr($hex,-2, 1)];
    $dist2 = hexdec(substr($hex,2,5));
    $M[] = [[$dir, $dist], [$dir2, $dist2]];
}
fclose($_fp);

function f($part = 1): int
{
    global $M;

    $B = $S = 0;
    $P = [0, 0];
    foreach ($M as $m)
    {
        [$dir, $d] = $m[$part - 1];
        $B += $d;
        $P2 = match ($dir)
        {
            'R' => [$P[0] + $d, $P[1]],
            'D' => [$P[0], $P[1] + $d],
            'L' => [$P[0] - $d, $P[1]],
            'U' => [$P[0], $P[1] - $d],
        };

        // shoelace formula, triangle form...
        $S += $P[0] * $P2[1] - $P2[0] * $P[1];
        $P = $P2;
    }
    $A = abs($S)/2;

    // pick's theorem...
    return $A + ($B/2) + 1 ;
}

$part1 = f(1);
$part2 = f(2);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
