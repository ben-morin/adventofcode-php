<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen( $argv[1] ?? "23.input", "r");

$G = [];
while ($line = trim(fgets($_fp)))
{
    [$a, $b] = explode("-", $line);
    $G[$a] ??= []; $G[$a][$b] ??= 1;
    $G[$b] ??= []; $G[$b][$a] ??= 1;
}
ksort($G);

$part1 = $part2 = 0;

$SPELL = [];
foreach ($G as $_a => $a) if ($_a[0] == "t")
    foreach (array_keys($a) as $_b)
        foreach (array_keys($G[$_b]) as $_c)
            if (isset($G[$_c][$_a]))
            {
                $key = [$_a, $_b, $_c];
                sort($key);
                $SPELL[implode(",", $key)] = 1;
            }
$part1 = count($SPELL);

function f($P, $R = [], $X = [], &$C = [])
{
    global $G;
    if (!$P && !$X) if (count($R) > count($C)) $C = $R;
    foreach ($P as $v)
    {
        $adj = isset($G[$v]) ? array_keys($G[$v]) : [];
        f(array_intersect($P, $adj), array_merge($R, [$v]), array_intersect($X, $adj), $C);
        $P = array_diff($P, [$v]);
        $X = array_merge($X, [$v]);
    }
    return $C;
}

$C = f(array_keys($G));
sort($C);
$part2 = implode(",", $C);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
