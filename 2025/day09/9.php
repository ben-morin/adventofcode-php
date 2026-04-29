<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "9.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);

$F = array_map(fn($line) => array_map("intval", explode(',', $line)), $F);

$part1 = $part2 = 0;

for ($i = 0, $c = count($F); $i < $c - 1; $i++) for ($j = $i + 1; $j < $c; $j++)
{
    [$x1, $y1] = $F[$i];
    [$x2, $y2] = $F[$j];

    [$x1, $x2] = [min($x1, $x2), max($x1, $x2)];
    [$y1, $y2] = [min($y1, $y2), max($y1, $y2)];

    $area = ($x2 - $x1 + 1) * ($y2 - $y1 + 1);
    if ($area > $part1) $part1 = $area;
    if ($area <= $part2) continue;

    for ($a = 0; $a < $c; $a++)
    {
        [$ax, $ay] = $F[$a];
        [$bx, $by] = $F[($a + 1) % $c];

        if (!(
            max($ax, $bx) <= $x1 || // completely left
            $x2 <= min($ax, $bx) || // completely right
            max($ay, $by) <= $y1 || // completely above
            $y2 <= min($ay, $by)    // completely below
        )) continue 2;
    }
    $part2 = $area;
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
