<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$S = trim(file_get_contents($argv[1] ?? "10.input"));

$part1 = $part2 = 0;

for ($t = 0; $t < 50; $t++)
{
    if ($t == 40) $part1 = strlen($S);
    $_S = '';
    for ($c = 1, $i = 1; $i < strlen($S); $i++)
    {
        if ($S[$i] == $S[$i - 1]) { $c++; continue; }
        $_S .= $c . $S[$i - 1];
        $c = 1;
    }
    $S = $_S . $c . $S[$i - 1];
}
$part2 = strlen($S);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
