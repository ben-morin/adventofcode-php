<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$maps = file_get_contents($argv[1] ?? "13.input");
$maps = explode("\n\n", trim($maps));

$part1 = $part2 = $result = 0;

foreach ($maps as $m => $map)
{
    $map = explode("\n", $map);
    $map = array_map(fn($r)=>str_split(str_replace(["#", "."], [1, 0], $r)), $map);

    foreach (["H" => 100, "V" => 1] as $reflect => $mul)
    {
        if ($reflect == "V") $map = array_map(null, ...$map);

        for ($h = count($map), $r = 0; $r < $h - 1; $r++)
        {
            $diff = 0;
            for ($i = 0; $r - $i >= 0 && $r + 1 + $i < $h; $i++)
            {
                $lhs = bindec(implode($map[$r - $i]));
                $rhs = bindec(implode($map[$r + 1 + $i]));
                if (($diff += substr_count(decbin($lhs ^ $rhs), "1")) > 1) break;
            }
            if ($diff == 0) $part1 += $mul * ($r + 1);
            if ($diff == 1) $part2 += $mul * ($r + 1);
        }
    }
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
