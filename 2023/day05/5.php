<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$lines = file_get_contents($argv[1] ?? "5.input");
$lines = explode("\n", trim($lines));

$seeds = explode(" ", explode(": ", $lines[0])[1]);
$seeds = array_map("intval", $seeds);
$maps = array_fill(0, 7, []);

$i = 3;
foreach ($maps as &$map) while ($i < count($lines))
{
    if ($lines[$i] == "") { $i += 2; break; }
    $map[] = explode(" ", $lines[$i]);
    $i++;
}
unset($map);

function map_range($seed_range)
{
    global $maps;

    $_check = [$seed_range];
    foreach ($maps as $map)
    {
        $_mapped = [];
        foreach ($map as $m)
        {
            $_split = [];
            list($ss, $se, $ds) = [$m[1], $m[1]+$m[2], $m[0]];
            while ($_check)
            {
                list($seed_s, $seed_e) = array_pop($_check);

                if ($seed_s < $ss && $ss < $seed_e)
                    $_split[] = [$seed_s, $ss];

                if (($seed_s < $ss && $ss < $seed_e) || ($seed_s < $se && $se < $seed_e) || ($ss <= $seed_s && $seed_e <= $se))
                    $_mapped[] = [max($seed_s, $ss) - $ss + $ds, min($se, $seed_e) - $ss + $ds];
                else
                    $_split[] = [$seed_s, $seed_e];

                if ($seed_s < $se && $se < $seed_e)
                    $_split[] = [$se, $seed_e];
            }
            if (!$_check = $_split) break;
        }
        $_check = array_merge($_mapped, $_check);
    }
    $min = INF;
    foreach ($_check as $_r) $min = min($min, $_r[0]);
    return $min;
}

$part1 = $part2 = INF;

foreach ($seeds as $s)
    $part1 = min($part1, map_range([$s, $s+1]));

while ($seeds)
{
    $start = array_shift($seeds);
    $len = array_shift($seeds);
    $seed_range = [$start, $start + $len];
    $part2 = min($part2, map_range($seed_range));
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
