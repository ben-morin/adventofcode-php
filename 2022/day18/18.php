<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$C = file($argv[1] ?? "18.input", FILE_IGNORE_NEW_LINES);
assert($C !== false);
$C = array_flip($C);

$MIN = [INF, INF, INF];
$MAX = [-INF, -INF, -INF];
foreach ($C as $key => $_)
{
    $cube = array_map('intval', explode(',', $key));
    foreach ($cube as $i => $v)
    {
        $MIN[$i] = min($MIN[$i], $v - 1);
        $MAX[$i] = max($MAX[$i], $v + 1);
    }
}

$part1 = $part2 = 0;

foreach ($C as $key => $_)
{
    [$x, $y, $z] = array_map('intval', explode(',', $key));
    foreach ([[-1, 0, 0], [1, 0, 0], [0, -1, 0], [0, 1, 0], [0, 0, -1], [0, 0, 1]] as [$dx, $dy, $dz])
        if (!isset($C[implode(',', [$x + $dx, $y + $dy, $z + $dz])]))
            $part1++;
}

$V = [];
$Q = [$MAX];

while ($Q)
{
    [$x, $y, $z] = array_shift($Q);
    $key = "$x,$y,$z";

    if (isset($C[$key]))
    {
        $part2++;
        continue;
    }

    if (isset($V[$key])) continue;
    $V[$key] = true;

    foreach ([[-1, 0, 0], [1, 0, 0], [0, -1, 0], [0, 1, 0], [0, 0, -1], [0, 0, 1]] as [$dx, $dy, $dz])
    {
        $cube = [$x + $dx, $y + $dy, $z + $dz];
        foreach ($cube as $i => $v) if ($v < $MIN[$i] || $v > $MAX[$i]) continue 2;
        $Q[] = $cube;
    }
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
