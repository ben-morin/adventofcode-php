<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "10.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);

$x = 1;
$count = 0;
$sum = [];
$CRT = str_repeat(' ', 240);

function cycle($x)
{
    global $count, $sum, $CRT;
    $_c = ((++$count - 1) % 40) + 1;
    if ($_c >= $x && $_c <= $x + 2) $CRT[$count-1] = '#';
    if ($count % 40 == 20) $sum[] = $count * $x;
}

foreach ($F as $s)
{
    // first cycle...
    cycle($x);
    if ($s !== "noop") // addx...
    {
        // second cycle...
        cycle($x);
        $x += (int)(explode(" ", $s)[1]);
    }
}

$part1 = array_sum($sum);
$part2 = "\n".implode("\n", str_split($CRT, 40));

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
