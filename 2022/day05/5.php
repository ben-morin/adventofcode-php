<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = explode("\n\n", file_get_contents($argv[1] ?? "5.input"));
$S = explode("\n", $F[0]);
$P = explode("\n", $F[1]);

$s_count = count(str_split(array_pop($S), 4));
$C = array_fill(1, $s_count,'');
while ($_c = array_shift($S))
{
    $_c = str_split($_c, 4);
    foreach ($_c as $k => $v)
        $C[$k + 1] .= str_replace(['[',']'], '', trim($v));
}

function f(array $C, $part2 = false): string
{
    global $P;
    $result = '';
    foreach ($P as $_p)
    {
        if (preg_match('/^move (\d+) from (\d) to (\d)$/', trim($_p), $m))
        {
            [, $n, $from, $to] = $m;
            $stack = substr($C[$from], 0, $n);
            if (!$part2) $stack = strrev($stack);
            $C[$from] = substr_replace($C[$from], '', 0, $n);
            $C[$to] = $stack . $C[$to];
        }
    }
    foreach($C as $v) $result .= $v[0];
    return $result;
}

$part1 = f($C);
$part2 = f($C, true);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
