<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "3.input", FILE_IGNORE_NEW_LINES);
assert($F != false);

define("WIDTH", strlen($F[0]));
$bits = array_fill(0, WIDTH, 0);

foreach ($F as $n)
	foreach(str_split($n) as $k => $v)
		$bits[$k] += ($v == '1' ? 1 : -1);

// part 1...
$gamma = $epsilon = '';
foreach ($bits as $v)
{
	$gamma .= intval($v >= 0);
	$epsilon .= intval($v < 0);
}
$part1 = bindec($gamma) * bindec($epsilon);

// part 2...
$oxygen = $co2 = $F;
for ($i = 0; $i < WIDTH; $i++) foreach (['oxygen', 'co2'] as $rating)
{
    if (count($$rating) == 1) continue;
    $ones = array_values(array_filter($$rating, fn($s) => $s[$i] == '1'));
    $zeros = array_values(array_filter($$rating, fn($s) => $s[$i] == '0'));
    if ($rating == 'oxygen')
        $$rating = (count($ones) >= count($zeros) ? $ones : $zeros);
    else // co2...
        $$rating = (count($zeros) <= count($ones) ? $zeros : $ones);
}
$part2 = bindec($oxygen[0]) * bindec($co2[0]);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
