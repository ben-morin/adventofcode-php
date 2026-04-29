<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "12.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);

define("Fh", count($F));
define("Fw", strlen($F[0]));

$S = $E = $A = [];

for ($y = 0; $y < Fh; $y++) for ($x = 0; $x < Fw; $x++)
{
    $c = $F[$y][$x];
    if ($c == 'S') { $S = [$y, $x]; $F[$y][$x] = $c = "a"; }
    if ($c == 'E') { $E = [$y, $x]; $F[$y][$x] = $c = "z"; }
    if ($c == 'a') $A[] = [$y, $x];
}

$part1 = $part2 = 0;

function f($part2 = false)
{
    global $F, $S, $E, $A;

    $Q = [[$S, 0]];
    if ($part2) $Q = array_map(fn($yx) => [$yx, 0], $A);
    $V = [];

    while ($Q)
    {
        [[$y, $x], $d] = array_shift($Q);

        if (isset($V[$key = "$y,$x"])) continue;
        $V[$key] = true;

        if ($E == [$y, $x]) return $d;

        foreach ([[-1, 0], [0, 1], [1, 0], [0, -1]] as [$dy, $dx])
        {
            [$_y, $_x] = [$y + $dy, $x + $dx];
            if ($_y < 0 || $_y >= Fh || $_x < 0 || $_x >= Fw) continue;
            if (ord($F[$_y][$_x]) - ord($F[$y][$x]) <= 1)
                $Q[] = [[$_y, $_x], $d + 1];
        }
    }
    return -1;
}

$part1 = f();
$part2 = f(true);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
