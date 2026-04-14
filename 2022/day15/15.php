<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$lines = file($argv[1] ?? "15.input", FILE_IGNORE_NEW_LINES);
assert($lines !== false);

$part1 = $part2 = 0;

// adjust limits for example data...
define('PART1', count($lines) <= 14 ? 10 : 2e6);
define('PART2', 4e6);

$S = $B = [];

foreach ($lines as $line)
{
    preg_match("/^Sensor at x=(-?\d+), y=(-?\d+): closest beacon is at x=(-?\d+), y=(-?\d+)$/", $line, $P);
    [, $Sx, $Sy, $Bx, $By] = array_map('intval', $P);
    $Sd = abs($Sx - $Bx) + abs($Sy - $By);
    $S["$Sx,$Sy,$Sd"] = 1;
    $B["$Bx,$By"] = 1;
}

for ($Y = 0 ; $Y <= PART2; $Y++)
{
    $_R = [];
    foreach ($S as $_s => $_)
    {
        [$Sx, $Sy, $Sd] = explode(",", $_s);
        $dy = abs($Sy - $Y);
        if ($dy > $Sd) continue;
        $_R[] = [$Sx - ($Sd - $dy), $Sx + ($Sd - $dy)];
    }

    $R = [];
    while ($r1 = array_shift($_R))
    {
        $_merged = false;
        foreach ($_R as $k => $r2)
        {
            if ($r1[1] < $r2[0] || $r2[1] < $r1[0]) continue;
            $r1 = [min($r1[0], $r2[0]), max($r1[1], $r2[1])];
            $_merged = true;
            unset($_R[$k]);
        }
        if ($_merged)
            array_unshift($_R, $r1);
        else
            $R[] = $r1;
    }

    if ($Y == PART1)
    {
        $part1 = 0;
        foreach ($R as $_r)
        {
            $part1 += abs($_r[0] - $_r[1]) + 1;
            foreach ($B as $_b => $_)
            {
                [$Bx, $By] = explode(",", $_b);
                if ($By == PART1)
                    if ($Bx >= $_r[0] && $Bx <= $_r[1])
                        $part1--;
            }
        }
    }

    $sum = 0;
    foreach ($R as $_r)
        $sum += abs(max($_r[0],0) - min($_r[1] + 1, PART2));

    $X = -1;
    if ($sum < PART2)
    {
        sort($R);
        $last_x = -1;
        for ($i = 0; $i < count($R); $i++)
        {
            if (max($R[$i][0], 0) != $last_x+1)
            {
                $X = $last_x+1;
                $part2 = $X * PART2 + $Y;
                break 2;
            }
            else $last_x = $R[$i][1];
        }
    }
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
