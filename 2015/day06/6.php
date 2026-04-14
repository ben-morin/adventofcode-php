<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "6.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);

$part1 = $part2 = 0;

$G = array_fill(0, 1000, array_fill(0, 1000, 0));
$G2 = array_fill(0, 1000, array_fill(0, 1000, 0));

foreach ($F as $line)
{
    preg_match("/(on|off|toggle) (\d+),(\d+) through (\d+),(\d+)/", $line, $m);
    [$op, $x1, $y1, $x2, $y2] = array_slice($m, 1);
    for ($x = $x1; $x <= $x2; $x++)
        for ($y = $y1; $y <= $y2; $y++)
        {
            // part 1...
            $light = $G[$x][$y];
            $G[$x][$y] = (int)($op == "on" || ($op == "toggle" && !$light));
            $part1 += $G[$x][$y] - $light;

            // part 2...
            $light = $G2[$x][$y];
            if ($op == "off")
                $G2[$x][$y] = max($light - 1, 0);
            else
                $G2[$x][$y] = $light + ($op == "on" ? 1 : 2);
            $part2 += $G2[$x][$y] - $light;
        }
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
