<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$T = file($argv[1] ?? "8.input", FILE_IGNORE_NEW_LINES);
assert($T !== false);

$w = strlen($T[0]);
$h = count($T);

$part1 = ($w * 2) + ($h * 2) - 4;
$part2 = 0;

for ($x = 1; $x < $w - 1; $x++) for ($y = 1; $y < $h - 1; $y++)
{
    $v = false;
    $ss = 1;
    $z = $T[$y][$x];

    for ($_y = $y - 1, $_v = true, $_ss = 0; $_y >= 0; $_y--)
    {
        $_ss++;
        if ($T[$_y][$x] >= $z) { $_v = false; break; }
    }
    $v = $_v;
    $ss *= $_ss;

    for ($_y = $y + 1, $_v = true, $_ss = 0; $_y < $h; $_y++)
    {
        $_ss++;
        if ($T[$_y][$x] >= $z) { $_v = false; break; }
    }
    $v = $v || $_v;
    $ss *= $_ss;

    for ($_x = $x - 1, $_v = true, $_ss = 0; $_x >= 0; $_x--)
    {
        $_ss++;
        if ($T[$y][$_x] >= $z) { $_v = false; break; }
    }
    $v = $v || $_v;
    $ss *= $_ss;

    for ($_x = $x + 1, $_v = true, $_ss = 0; $_x < $w; $_x++)
    {
        $_ss++;
        if ($T[$y][$_x] >= $z) { $_v = false; break; }
    }
    $v = $v || $_v;
    $ss *= $_ss;

    if ($v) $part1++;
    if ($ss > $part2) $part2 = $ss;
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
