<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$M = file_get_contents($argv[1] ?? "21.input");
$M = explode("\n", trim($M));
$Mh = count($M);
$Mw = strlen($M[0]);
assert($Mw == $Mh);

$start = [0, 0];
foreach ($M as $y => $r) if (($x = strpos($r, "S")) !== false) { $start = [$x, $y]; break; }

function MOD($num, $mod): int { return ($mod + ($num % $mod)) % $mod; }

$part1 = $part2 = 0;

$steps1 = 64;
$steps2 = 26501365;

$result = [0,0];
$calc = [];

$Q = new SplMinHeap();
$Q->insert([0, $start[0], $start[1]]);
$C["{$start[0]},{$start[1]}"] = 1;

while (count($Q))
{
    [$s, $y, $x] = $Q->extract();

    $_s = $s-1;
    if (MOD($_s, $Mw) == MOD($steps2, $Mw) && !isset($calc[$_s]))
        $calc[$_s] = $result[($_s) % 2];

    if ($_s == $steps1) $part1 = $result[($_s) % 2];

    if ($s > $steps1 && count($calc) >= 3) break;

    $result[$s % 2]++;

    foreach ([[$y + 1, $x], [$y - 1, $x], [$y, $x + 1], [$y, $x - 1]] as [$nr, $nc])
    {
        if ($M[MOD($nr, $Mh)][MOD($nc,$Mw)] == "#" || isset($C["$nr,$nc"])) continue;
        $C["$nr,$nc"] = 1;
        $Q->insert([$s+1, $nr, $nc]);
    }

}

$n = intdiv($steps2, $Mw);
[$u1, $u2, $u3] = array_values($calc);

$a = ($u3 - 2*$u2 + $u1) / 2;
$b = $u2 - $u1 - $a;
$c = $u1;

$part2 = $a * $n * $n + $b * $n + $c;

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";

/*
   https://www.wolframalpha.com/input?i=quadratic+fit+calculator&assumption=%7B%22F%22%2C+%22QuadraticFitCalculator%22%2C+%22data3x%22%7D+-%3E%22%7B0%2C+1%2C+2%7D%22&assumption=%7B%22F%22%2C+%22QuadraticFitCalculator%22%2C+%22data3y%22%7D+-%3E%22%7B3752%2C+33614%2C+93252%7D%22
   y = 3752 + 14974*x + 14888*x^2
*/
