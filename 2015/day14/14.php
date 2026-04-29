<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen($argv[1] ?? "14.input", "r");

$G = [];
while ($line = trim(fgets($_fp)))
{
    preg_match("/(\w+) can fly (\d+) km\/s for (\d+) seconds, but then must rest for (\d+) seconds./", $line, $m);
    $G[] = [$m[1], (int)$m[2], (int)$m[3], (int)$m[4], (int)$m[3], 0, 0];
}

$part1 = $part2 = 0;

for ($t = 0 ; $t < 2503; $t++)
{
    foreach ($G as $i => [,$speed,$fly,$rest,$left,,])
    {
        if ($left == -$rest) $left = $fly;
        $G[$i][5] += ($left > 0 ? $speed : 0);
        $G[$i][4] = $left - 1;
        $part1 = max($part1, $G[$i][5]);
    }
    foreach (array_column($G, 5) as $i => $dist)
    {
        if ($dist == $part1) $G[$i][6]++;
        $part2 = max($part2, $G[$i][6]);
    }
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
