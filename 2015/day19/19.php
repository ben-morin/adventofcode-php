<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "19.example", FILE_IGNORE_NEW_LINES);
assert($F !== false);

$M = array_pop($F);
array_pop($F);

$part1 = $part2 = 0;

$C = $F2 = [];
foreach ($F as $line)
{
    [$from, $to] = explode(" => ", $line);
    while (($pos = strpos($M, $from, $pos ?? 0)) !== false)
    {
        $C[substr_replace($M, $to, $pos, strlen($from))] = 1;
        $pos++;
    }
    // part 2, reverse mapping...
    $F2[strrev($to)] = strrev($from);
}
$part1 = count($C);

$M = strrev($M);
$keys = join("|", array_keys($F2));
while ($M !== "e")
{
    $M = preg_replace_callback("/{$keys}/", fn($m) => $F2[$m[0]], $M, 1);
    $part2++;
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
