<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$G = file($argv[1] ?? "8.input", FILE_IGNORE_NEW_LINES);
assert($G !== false);
$ROWS = count($G);
$COLS = strlen($G[0]);

$part1 = $part2 = 0;

$F = [];
for ($r = 0; $r < $ROWS; $r++) for ($c = 0; $c < $COLS; $c++)
{
    $_f = $G[$r][$c];
    if ($_f == '.') continue;
    $F[$_f] ??= [];
    $F[$_f][] = [$r, $c];
}

$A = $A2 = [];
foreach ($F as $_f)
{
    for ($i = 0; $i < count($_f)-1; $i++) for ($j = $i + 1; $j < count($_f); $j++)
    {
        [[$r1, $c1], [$r2, $c2]] = [$_f[$i], $_f[$j]];
        [$dr, $dc] = [$r2 - $r1, $c2 - $c1];

        foreach ([[$r1 - $dr, $c1 - $dc], [$r2 + $dr, $c2 + $dc]] as [$r, $c])
        {
            if ($r < 0 || $r >= $ROWS || $c < 0 || $c >= $COLS) continue;
            $A["$r,$c"] = 1;
        }

        foreach ([[$r1, $c1, 1], [$r2, $c2, -1]] as [$_r, $_c, $s]) for ($k = 1; true; $k++)
        {
            $r = $_r + $k * $dr * $s;
            $c = $_c + $k * $dc * $s;
            if ($r < 0 || $r >= $ROWS || $c < 0 || $c >= $COLS) break;
            $A2["$r,$c"] = 1;
        }
    }
}

$part1 = count($A);
$part2 = count($A2);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
