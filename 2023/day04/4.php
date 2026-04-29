<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen( $argv[1] ?? "4.input", "r");

$part1 = $part2 = $card = 0;
$counts = [];

while (!feof($_fp))
{
    $line = preg_replace("/\s{2,}/", " ", trim(fgets($_fp)));
    $line = explode(": ", $line)[1];

    list($nums, $play) = explode(" | ", $line);
    $wins = array_intersect(explode(" ", $play), explode(" ", $nums));

    $card++;
    $counts[$card] = ($counts[$card] ?? 0) + 1;

    if (!empty($wins))
    {
        $part1 += pow(2,count($wins)-1);
        for ($i = $card+1; $i <= $card+count($wins); $i++)
            $counts[$i] = ($counts[$i] ?? 0) + $counts[$card];
    }
}
$part2 = array_sum($counts);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
