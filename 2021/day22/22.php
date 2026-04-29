<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen($argv[1] ?? "22.input", "r");

$S = [];
while (!feof($_fp) && $_s = trim(fgets($_fp)))
{
    preg_match('/^(on|off) x=(-?\d+)\.\.(-?\d+),y=(-?\d+)\.\.(-?\d+),z=(-?\d+)\.\.(-?\d+)$/', $_s, $m);
    $S[] = [$m[1] == 'on', (int)$m[2], (int)$m[3], (int)$m[4], (int)$m[5], (int)$m[6], (int)$m[7]];
}

function f($S, $part2 = false)
{
    // set of non-overlapping "on" cubes...
    $R = [];
    foreach ($S as [$on, $x1, $x2, $y1, $y2, $z1, $z2])
    {
        if (!$part2)
        {
            $x1 = max(-50, $x1); $x2 = min(50, $x2);
            $y1 = max(-50, $y1); $y2 = min(50, $y2);
            $z1 = max(-50, $z1); $z2 = min(50, $z2);
            if ($x1 > $x2 || $y1 > $y2 || $z1 > $z2) continue;
        }
        for ($i = 0, $c = count($R); $i < $c; $i++)
        {
            list($_x1, $_x2, $_y1, $_y2, $_z1, $_z2) = $R[$i];
            // non-overlapping...
            if ($_x2 < $x1 || $_x1 > $x2 || $_y2 < $y1 || $_y1 > $y2 || $_z2 < $z1 || $_z1 > $z2)
                continue;
            // remove and put back the non-overlapping parts...
            unset($R[$i]);
            if ($_x1 < $x1) $R[] = [$_x1, $x1 - 1, $_y1, $_y2, $_z1, $_z2];
            if ($_x2 > $x2) $R[] = [$x2 + 1, $_x2, $_y1, $_y2, $_z1, $_z2];
            $mx1 = max($_x1, $x1); $mx2 = min($_x2, $x2);
            if ($_y1 < $y1) $R[] = [$mx1, $mx2, $_y1, $y1 - 1, $_z1, $_z2];
            if ($_y2 > $y2) $R[] = [$mx1, $mx2, $y2 + 1, $_y2, $_z1, $_z2];
            $my1 = max($_y1, $y1); $my2 = min($_y2, $y2);
            if ($_z1 < $z1) $R[] = [$mx1, $mx2, $my1, $my2, $_z1, $z1 - 1];
            if ($_z2 > $z2) $R[] = [$mx1, $mx2, $my1, $my2, $z2 + 1, $_z2];
        }
        if ($on) $R[] = [$x1, $x2, $y1, $y2, $z1, $z2];
        $R = array_values($R);
    }
    return array_reduce($R, function($sum, $c) {
        [$x1, $x2, $y1, $y2, $z1, $z2] = $c;
        return $sum + ($x2 - $x1 + 1) * ($y2 - $y1 + 1) * ($z2 - $z1 + 1);
    }, 0);
}

$part1 = f($S);
$part2 = f($S, true);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
