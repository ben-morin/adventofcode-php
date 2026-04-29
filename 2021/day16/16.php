<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = trim(file_get_contents($argv[1] ?? "16.input"));

$BIT = '';
foreach (str_split($F) as $h) $BIT .= sprintf('%04b', hexdec($h));
$POS = 0;

function bits($n)
{
    // NOTE: calling this function advances POS...
    global $BIT, $POS;
    $b = substr($BIT, $POS, $n);
    $POS += $n;
    return $b;
}

function packet(&$vsum = 0)
{
    global $POS;

    $vsum += bindec(bits(3));
    $T = bindec(bits(3));
    $ops = [];

    if ($T == 4) // literal
    {
        $v = '';
        while (true)
        {
            $group = bits(5);
            $v .= substr($group, 1);
            if ($group[0] == '0') break;
        }
        return bindec($v);
    }

    // operator: collect sub-packet values...
    if (bits(1) == '0') // length of sub-packets...
    {
        // call bits() first as it advances POS...
        $end = bindec(bits(15)) + $POS;
        while ($POS < $end) $ops[] = packet($vsum);
    }
    else // number of sub-packets...
    {
        $n = bindec(bits(11));
        for ($i = 0; $i < $n; $i++) $ops[] = packet($vsum);
    }

    return match ($T)
    {
        0 => array_sum($ops),
        1 => array_product($ops),
        2 => min($ops),
        3 => max($ops),
        5 => intval($ops[0] > $ops[1]),
        6 => intval($ops[0] < $ops[1]),
        7 => intval($ops[0] == $ops[1])
    };
}

$part2 = packet($part1);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
