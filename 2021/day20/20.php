<?php

memory_reset_peak_usage();
$start_time = microtime(true);

[$A, $G] = explode("\n\n", trim(file_get_contents($argv[1] ?? "20.input")));

$A = array_map(fn($c) => $c == '#' ? 1 : 0, str_split(str_replace("\n", "", $A)));
$G = array_map(fn($l) => array_map(fn($c) => $c == '#' ? 1 : 0, str_split($l)), explode("\n", $G));

$part1 = $part2 = 0;

for ($step = 1, $edge = 0; $step <= 50; $step++)
{
    $H = count($G);
    $W = count($G[0]);

    // pad by 2 pixels of $edge on each side...
    $row = [array_fill(0, $W, $edge), array_fill(0, $W, $edge)];
    $pad = array_map(fn($r) => array_merge([$edge, $edge], $r, [$edge, $edge]),
        array_merge($row, $G, $row));

    $_G = [];
    for ($y = 0, $_h = $H + 2; $y < $_h; $y++)
    {
        // image enhancement algorithm: 9 pixels form a binary number...
        [$r0, $r1, $r2] = array_slice($pad, $y, 3);
        $row = [];
        for ($x = 0, $_w = $W + 2; $x < $_w; $x++)
            $row[] = $A[
                ($r0[$x] << 8) | ($r0[$x+1] << 7) | ($r0[$x+2] << 6) |
                ($r1[$x] << 5) | ($r1[$x+1] << 4) | ($r1[$x+2] << 3) |
                ($r2[$x] << 2) | ($r2[$x+1] << 1) | $r2[$x+2]
            ];
        $_G[] = $row;
    }
    $G = $_G;
    $edge = $A[$edge ? 511 : 0];

    if ($step == 2 || $step == 50)
    {
        $count = 0;
        foreach ($G as $row) $count += array_sum($row);
        ($step == 2) ? $part1 = $count : $part2 = $count;
    }
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
