<?php

memory_reset_peak_usage();
$start_time = microtime(true);

const DEBUG = false;

$_fp = fopen( $argv[1] ?? "25.input", "r");

function name($a, $b)
{
    $k = array_merge(explode("-", $a), explode("-", $b));
    sort($k);
    return implode("-", $k);
}

$V = $E = [];
while (!feof($_fp) && $line = trim(fgets($_fp)))
{
    [$from, $to] = explode(": ", $line);
    $to = explode(" ", $to);
    $V[$from] = 1;
    foreach ($to as $_to)
    {
        $V[$_to] = 1;
        $E[name($from, $_to)] = 1;
    }
}
fclose($_fp);

// https://en.wikipedia.org/wiki/Karger%27s_algorithm
// Karger's algorithm is a randomized algorithm, so runtime varies.

function contract($V, $E): array
{
    $M = [];
    while (count($V) > 2)
    {
        // random edge...
        $edge = array_rand($E);
        // get vertexes on either side of edge, mapped if necessary...
        [$Ea, $Eb] = explode("-", $edge);
        [$a, $b] = [$M[$Ea] ?? $Ea, $M[$Eb] ?? $Eb];
        // remove edges...
        foreach (explode("-", $a) as $_a)
            foreach (explode("-", $b) as $_b)
                unset($E[name($_a, $_b)]);
        // new supernode, map old nodes to new...
        $sn = name($a, $b);
        foreach (explode("-", $sn) as $from) $M[$from] = $sn;
        $V[$sn] = $V[$a] + $V[$b];
        unset($V[$a], $V[$b]);
    }
    return [$V, $E];
}

$part1 = $min = INF;
$_V = [];
$t = 0;

while ($min > 3)
{
    $t++;
    [$_V, $_E] = contract($V, $E);
    if (DEBUG) echo json_encode(array_keys($_E))."\n";
    $min = min($min, count($_E));
}
$part1 = array_product($_V);
$part2 = 0;

echo "part 1: {$part1}", DEBUG ? " (in t={$t})\n" : "\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
