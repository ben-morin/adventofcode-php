<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$lines = file_get_contents($argv[1] ?? "22.input");
$lines = explode("\n", trim($lines));

$B = $C = [];

foreach ($lines as $l)
{
    $l = explode('~', $l);
    [$x1, $y1, $z1] = explode(',', $l[0]);
    [$x2, $y2, $z2] = explode(',', $l[1]);
    $brick = [];
    if ($x1 == $x2 && $y1 == $y2)
        foreach (range($z1, $z2) as $z) { $brick[] = [$x1, $y1, $z]; $C["$x1,$y1,$z"] = 1; }
    elseif ($x1 == $x2 && $z1 == $z2)
        foreach (range($y1, $y2) as $y) { $brick[] = [$x1, $y, $z1]; $C["$x1,$y,$z1"] = 1; }
    elseif ($y1 == $y2 && $z1 == $z2)
        foreach (range($x1, $x2) as $x) { $brick[] = [$x, $y1, $z1]; $C["$x,$y1,$z1"] = 1; }
    $B[] = $brick;
}
unset($lines);

while (true)
{
    $found = false;
    foreach ($B as $i => $brick)
    {
        $can_move = true;
        foreach ($brick as [$x, $y, $z])
        {
            if ($z == 1) $can_move = false;
            if (isset($C["$x,$y,".$z-1]) && !in_array([$x, $y, $z-1], $brick)) $can_move = false;
        }
        if ($can_move)
        {
            $found = true;
            $B[$i] = [];
            foreach ($brick as [$x, $y, $z])
            {
                unset($C["$x,$y,$z"]);
                $C["$x,$y,".$z-1] = 1;
                $B[$i][] = [$x, $y, $z-1];
            }
        }
    }
    if (!$found) break;
}

$part1 = $part2 = 0;

foreach ($B as $i => $brick)
{
    $BB = $B;
    $CC = $C;
    $result = [];

    foreach ($brick as [$x, $y, $z]) unset($CC["$x,$y,$z"]);

    while(true)
    {
        $found = false;
        foreach ($BB as $j => $_brick)
        {
            if ($j == $i) continue;
            $can_move = true;
            foreach ($_brick as [$x, $y, $z])
            {
                if ($z == 1) $can_move = false;
                if (isset($CC["$x,$y,".$z-1]) && !in_array([$x, $y, $z-1], $_brick)) $can_move = false;
            }
            if ($can_move)
            {
                $found = true;
                $result["$j"] = 1;
                $BB[$j] = [];
                foreach ($_brick as [$x, $y, $z])
                {
                    unset($CC["$x,$y,$z"]);
                    $CC["$x,$y,".$z-1] = 1;
                    $BB[$j][] = [$x, $y, $z-1];
                }
            }
        }
        if (!$found) break;
    }
    if (!count($result)) $part1++;
    $part2 += count($result);
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
