<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen( $argv[1] ?? "2.input", "r");

$part1 = $part2 = 0;

while (!feof($_fp))
{
    $line = trim(fgets($_fp));
    [$game, $hands] = explode(":", $line);

    $game = (int)substr($game, 5);
    $hands = explode("; ", trim($hands));
    $max = ["r" => 0, "g" => 0, "b" => 0];

    foreach ($hands as $hand)
    {
        $h = explode(", ", $hand);
        foreach ($h as $d)
        {
            [$v, $k] = explode(" ", $d);
            $max[$k[0]] = max($max[$k[0]], $v);
        }
    }

    if ($max["r"] <= 12 && $max["g"] <= 13 && $max["b"] <= 14) $part1 += $game;
    $part2 += $max["r"] * $max["g"] * $max["b"];
}
fclose($_fp);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
