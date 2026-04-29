<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = explode("\n\n", trim(file_get_contents($argv[1] ?? "19.input")));

$SCANNERS = [];
foreach ($F as $s)
{
    $b = explode("\n", $s);
    array_shift($b); // scanner N
    $SCANNERS[] = array_map(fn($_b) => array_map('intval', explode(',', $_b)), $b);
}

function rotate($x, $y, $z, $i)
{
    // the 24 orientation-preserving rotations of (x,y,z)...
    return [
        [$x, $y, $z], [$x, -$z, $y], [$x, -$y, -$z], [$x, $z, -$y],
        [-$y, $x, $z], [$z, $x, $y], [$y, $x, -$z], [-$z, $x, -$y],
        [-$x, -$y, $z], [-$x, -$z, -$y], [-$x, $y, -$z], [-$x, $z, $y],
        [$y, -$x, $z], [$z, -$x, -$y], [-$y, -$x, -$z], [-$z, -$x, $y],
        [-$z, $y, $x], [$y, $z, $x], [$z, -$y, $x], [-$y, -$z, $x],
        [-$z, -$y, -$x], [-$y, $z, -$x], [$z, $y, -$x], [$y, -$z, -$x]
    ][$i];
}

$ROTS = [];
// precompute all 24 beacon rotations...
foreach ($SCANNERS as $s => $beacons) for ($r = 0; $r < 24; $r++)
{
    $b = [];
    foreach ($beacons as [$x, $y, $z]) $b[] = rotate($x, $y, $z, $r);
    $ROTS[$s][$r] = $b;
}

function align($placed, $rotations)
{
    // try to align rotations against already-placed beacons...
    for ($r = 0; $r < 24; $r++)
    {
        $delta = [];
        foreach ($placed as [$px, $py, $pz]) foreach ($rotations[$r] as [$cx, $cy, $cz])
        {
            $k = ($px-$cx).",".($py-$cy).",".($pz-$cz);
            if (($delta[$k] = ($delta[$k] ?? 0) + 1) >= 12)
                return [$r, array_map('intval', explode(',', $k))];
        }
    }
    return null;
}

// BFS: scanner 0 at origin; match each UN-scanner against PL-scanners...
$PL = [0 => $SCANNERS[0]];
$POS = [0 => [0, 0, 0]];
$UN = array_flip(range(1, count($SCANNERS) - 1));
$Q = [0];

while ($Q)
{
    $p = array_shift($Q);
    foreach (array_keys($UN) as $u)
    {
        if (!$m = align($PL[$p], $ROTS[$u])) continue;
        [$r, $off] = $m;
        // transform scanner u's rotated beacons into absolute coords...
        $abs = [];
        foreach ($ROTS[$u][$r] as [$x, $y, $z])
            $abs[] = [$x + $off[0], $y + $off[1], $z + $off[2]];
        $PL[$u] = $abs;
        $POS[$u] = $off;
        unset($UN[$u]);
        $Q[] = $u;
    }
}

// part 1: union of all beacons in absolute coords...
$part1 = [];
foreach ($PL as $b) foreach ($b as [$x, $y, $z]) $part1["$x,$y,$z"] = 1;
$part1 = count($part1);

// part 2: max Manhattan distance between any two scanner positions
$part2 = 0;
$POS = array_values($POS);
for ($i = 0; $i < count($POS); $i++) for ($j = $i+1; $j < count($POS); $j++)
{
    [$x1, $y1, $z1, $x2, $y2, $z2] = array_merge($POS[$i], $POS[$j]);
    $d = abs($x1 - $x2) + abs($y1 - $y2) + abs($z1 - $z2);
    $part2 = max($part2, $d);
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
