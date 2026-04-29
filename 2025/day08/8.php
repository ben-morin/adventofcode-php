<?php

ini_set('memory_limit', '256M');
memory_reset_peak_usage();
$start_time = microtime(true);

$B = file($argv[1] ?? "8.input", FILE_IGNORE_NEW_LINES);
assert($B !== false);
define('LIMIT', count($B) == 20 ? 10 : 1000);

$B = array_map(fn($line) => array_map("intval", explode(',', $line)), $B);
$N = count($B);

// calculate distance between all boxes...
$D = [];
foreach ($B as $i => [$x1, $y1, $z1]) for ($j = $i + 1; $j < $N; $j++)
{
    [$x2, $y2, $z2] = $B[$j];
    $D[] = [($x1 - $x2) ** 2 + ($y1 - $y2) ** 2 + ($z1 - $z2) ** 2, $i, $j];
}
sort($D);

// union-find (every node starts as its own parent)...
$P = array_keys($B);
function find($x) { global $P; return ($P[$x] == $x) ? $x : $P[$x] = find($P[$x]); }
function union($x, $y) { global $P; $P[find($x)] = find($y); }

$part1 = $part2 = 0;

// kruskal...
for ($i = $conn = 0, $_c = count($D); $i < $_c; $i++)
{
    // edges in order of increasing distance...
    [$dist, $u, $v] = $D[$i];
    // part 1 after LIMIT edges...
    if ($i == LIMIT)
    {
        // calculate circuit sizes...
        for ($x = 0, $S = []; $x < $N; $x++)
        {
            $S[$root = find($x)] ??= 0;
            $S[$root]++;
        }
        sort($S);
        $part1 = array_product(array_slice($S, -3));
    }
    // connect boxes if not already connected...
    if (find($u) !== find($v))
    {
        union($u, $v);
        if (++$conn == $N - 1)
        {
            $part2 = $B[$u][0] * $B[$v][0];
            break;
        }
    }
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
