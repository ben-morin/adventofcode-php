<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$lines = file_get_contents($argv[1] ?? "3.input");
$lines = explode("\n", trim($lines));

$part1 = $part2 = 0;
$gears = [];

function gear($y, $x, $n): void
{
    global $gears;
    $gears[json_encode([$y,$x])] ??= [];
    $gears[json_encode([$y,$x])][] = $n;
    $gears[json_encode([$y,$x])] = array_unique($gears[json_encode([$y,$x])]);
}

for ($y = 0; $y < count($lines); $y++)
{
    if (preg_match_all("/\d+/", "{$lines[$y]}", $match, PREG_OFFSET_CAPTURE))
    {
        foreach ($match[0] as $m)
        {
            $n = (int)$m[0];
            $x1 = $m[1];
            $x2 = $m[1] + strlen($m[0]) - 1;
            $p = false;

            for ($_x = max($x1-1, 0); $_x < min($x2+2, strlen($lines[0])); $_x++)
            {
                if ($y > 0)
                {
                    if ($lines[$y - 1][$_x] != ".") $p = true;
                    if ($lines[$y - 1][$_x] == "*") gear($y-1, $_x, json_encode([$y, $x1, $n]));
                }
                if ($y + 1 < count($lines))
                {
                    if ($lines[$y + 1][$_x] != ".") $p = true;
                    if ($lines[$y + 1][$_x] == "*") gear($y+1, $_x, json_encode([$y, $x1, $n]));
                }
            }

            if ($x1 > 0)
            {
                if ($lines[$y][$x1 - 1] != ".") $p = true;
                if ($lines[$y][$x1 - 1] == "*") gear($y, $x1-1, json_encode([$y, $x1, $n]));
            }

            if ($x2 + 1 < strlen($lines[0]))
            {
                if ($lines[$y][$x2 + 1] != ".") $p = true;
                if ($lines[$y][$x2 + 1] == "*") gear($y, $x2+1, json_encode([$y, $x1, $n]));
            }

            if ($p) $part1 += $n;
        }
    }
}

if (count($gears)) foreach ($gears as $g) if (count($g) == 2)
{
    $g[0] = json_decode($g[0]);
    $g[1] = json_decode($g[1]);
    $part2 += (int)$g[0][2] * (int)$g[1][2];
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
