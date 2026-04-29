<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "11.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);

$G = [];
foreach ($F as $line)
{
    [$v, $edges] = explode(": ", $line);
    $G[$v] = explode(" ", $edges);
}

function f($s, $d): int
{
    global $G;
    static $CACHE = [];
    if ($s == $d) return 1;
    if (isset($CACHE[$key = "$s,$d"])) return $CACHE[$key];
    $count = 0;
    foreach ($G[$s] ?? [] as $v) $count += f($v, $d);
    return $CACHE[$key] = $count;
}

$part1 = f("you", "out");
$part2 = f("svr", "dac") * f("dac", "fft") * f("fft", "out") +
         f("svr", "fft") * f("fft", "dac") * f("dac", "out");

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
