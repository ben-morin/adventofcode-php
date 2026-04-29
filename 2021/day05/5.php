<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "5.example", FILE_IGNORE_NEW_LINES);
assert($F !== false);

$part1 = $part2 = 0;
$D1 = $D2 = [];

function mark(&$board, $key)
{
	$board[$key] = ($board[$key] ?? 0) + 1;
    // first time a key hits 2 it's counted...
	return ($board[$key] == 2);
}

foreach ($F as $line)
{
	preg_match('/(\d+),(\d+) -> (\d+),(\d+)/', $line, $m);
	[, $x1, $y1, $x2, $y2] = $m;
	$dx = $x2 <=> $x1;
	$dy = $y2 <=> $y1;
	$steps = max(abs($x2 - $x1), abs($y2 - $y1));
	$hv = ($x1 == $x2 || $y1 == $y2);
	for ($i = 0; $i <= $steps; $i++)
	{
		$key = ((int)$x1 + $i * $dx) . "," . ((int)$y1 + $i * $dy);
		if ($hv && mark($D1, $key)) $part1++;
		if (mark($D2, $key)) $part2++;
	}
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
