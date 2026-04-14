<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "20.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);
$F = array_map(null, array_keys($F), array_map('intval', $F));

$part1 = $part2 = 0;

function decrypt($F, $part2 = false)
{
    if ($part2) for ($d = 0; $d < count($F); $d++) $F[$d][1] *= 811589153;
    for ($i = 0; $i < ($part2 ? 10 : 1); $i++)
    {
        for ($j = 0; $j < count($F); $j++)
        {
            for ($k = 0; $k < count($F); $k++) if ($F[$k][0] == $j) break;
            $F = array_merge(array_slice($F, $k + 1), array_slice($F, 0, $k + 1));
            $N = array_pop($F);
            $k = (($N[1] % count($F)) + count($F)) % count($F);
            $F = array_merge(array_slice($F, $k), array_slice($F, 0, $k));
            $F[] = $N;
        }
    }
    for ($d = 0, $count = count($F); $d < $count; $d++) if ($F[$d][1] == 0) break;
    return $F[($d + 1000) % $count][1] + $F[($d + 2000) % $count][1] + $F[($d + 3000) % $count][1];
}

$part1 = decrypt($F); // No need to clone since we're not modifying the original array
$part2 = decrypt($F, true);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: " . round(microtime(true) - $start_time, 4) . " seconds\n";
echo "   Peak memory: " . round(memory_get_peak_usage() / pow(2, 20), 4), " MiB\n\n";
