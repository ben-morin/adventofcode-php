<?php

memory_reset_peak_usage();
$start_time = microtime(true);

const DEBUG = false;

$F = file($argv[1] ?? "14.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);

$SOURCE = 500;
$FLOOR_OFFSET = 2;

// adjust width for example data...
$SOURCE_OFFSET = (count($F) > 2 ? 0 : 475);
$SOURCE -= $SOURCE_OFFSET;

$C = [];
$Cw = $SOURCE * 2;
$Ch = PHP_INT_MIN;

foreach ($F as $R)
{
    $R = explode(' -> ', $R);
    [$x1, $y1] = explode(',', array_shift($R));
    $x1 -= $SOURCE_OFFSET;
    while($R)
    {
        [$x2, $y2] = explode(',', array_shift($R));
        $x2 -= $SOURCE_OFFSET;
        $Ch = max($Ch, (int)$y1, (int)($y2));
        while (count($C) < $Ch + $FLOOR_OFFSET) $C[] = array_fill(0, $Cw, '.');
        if ($x1 != $x2)
            foreach(range($x1, $x2) as $_x) $C[$y1][$_x] = '#';
        else // $y1 != $y2
            foreach(range($y1, $y2) as $_y) $C[$_y][$x1] = '#';
        [$x1, $y1] = [$x2, $y2];
    }
}

$C[] = array_fill(0, $Cw, '#');
if (DEBUG) { foreach($C as $row) echo implode('', $row)."\n"; echo "\n"; }

$part1 = $part2 = 0;

function sand($px, $py = 0, &$sand = 0)
{
    global $C, $Ch, $part1;
    if ($C[$py + 1][$px] == '.') sand($px, $py + 1, $sand);
    if ($C[$py + 1][$px - 1] == '.') sand($px - 1, $py + 1, $sand);
    if ($C[$py + 1][$px + 1] == '.') sand($px + 1, $py + 1, $sand);
    $C[$py][$px] = 'o';
    if ($py > $Ch && !$part1)
    {
        if (DEBUG) { foreach($C as $row) echo implode('', $row)."\n"; echo "\n"; }
        $part1 = $sand;
    }
    return ++$sand;
}

$part2 = sand($SOURCE);

if (DEBUG) { foreach($C as $row) echo implode('', $row)."\n"; echo "\n"; }

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
