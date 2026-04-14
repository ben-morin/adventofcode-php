<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$S = file_get_contents($argv[1] ?? "11.input");
$S = array_map('intval', explode(" ", $S));

$part1 = $part2 = 0;

$CACHE = [];
function blink($s, $t)
{
    global $CACHE;

    if (isset($CACHE[$key = "$s,$t"])) return $CACHE[$key];

    if ($t == 0)
        $c = 1;
    elseif ($s == 0)
        $c = blink(1, $t - 1);
    elseif (strlen($s) % 2 == 0)
    {
        [$l, $r] = str_split($s, strlen($s) / 2);
        $c = blink((int)$l, $t - 1) + blink((int)$r, $t - 1);
    }
    else $c = blink($s * 2024, $t - 1);

    return $CACHE[$key] = $c;
}

foreach ($S as $stone)
{
    $part1 += blink($stone, 25);
    $part2 += blink($stone, 75);
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
