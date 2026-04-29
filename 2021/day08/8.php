<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "8.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);

$part1 = $part2 = 0;

function _sort($s) { $a = str_split($s); sort($a); return implode($a); }

function overlap($a, $b)
{
    $n = 0;
    foreach (str_split($a) as $c) if (strpos($b, $c) !== false) $n++;
    return $n;
}

foreach ($F as $line)
{
    if ($line == '') continue;
    [$left, $right] = explode(' | ', $line);
    $signals = array_map("_sort", explode(' ', $left));
    $outputs = array_map("_sort", explode(' ', $right));

    $by_len = [];
    foreach ($signals as $s) $by_len[strlen($s)][] = $s;

    $D = array_fill(0, 10, '');
    $D[1] = $by_len[2][0];
    $D[7] = $by_len[3][0];
    $D[4] = $by_len[4][0];
    $D[8] = $by_len[7][0];
    // 0, 6, 9 (6 segments): only 6 is missing a segment of 1;
    // of the remaining, only 9 contains all 4 segments of 4...
    foreach ($by_len[6] as $s)
    {
        if (overlap($s, $D[1]) == 1)
            $D[6] = $s;
        elseif (overlap($s, $D[4]) == 4)
            $D[9] = $s;
        else
            $D[0] = $s;
    }
    // 2, 3, 5 (5 segments): only 3 contains both segments of 1;
    // of the remaining, 5 shares 3 segments with 4...
    foreach ($by_len[5] as $s)
    {
        if (overlap($s, $D[1]) == 2)
            $D[3] = $s;
        elseif (overlap($s, $D[4]) == 3)
            $D[5] = $s;
        else
            $D[2] = $s;
    }

    $D_map = array_flip($D);
    $n = 0;
    foreach ($outputs as $o)
    {
        $l = strlen($o);
        // 1, 4, 7, 8 (segment count)...
        if ($l == 2 || $l == 4 || $l == 3 || $l == 7) $part1++;
        $n = $n * 10 + $D_map[$o];
    }
    $part2 += $n;
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
