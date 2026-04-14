<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen( $argv[1] ?? "8.input", "r");

$ins = trim(fgets($_fp));
fgets($_fp); // blank

$map = $a_nodes = $a_found = [];

while (!feof($_fp))
{
    $line = trim(fgets($_fp));
    [$n, $l, $r] = explode(" ", preg_replace(array("/[^\dA-Z]/", "/\s+/"), " ", $line));
    $map[$n] = [$l, $r];
    if ($n[2] == "A") $a_nodes[$n] = $n;
}
fclose($_fp);

$count = 0;
while (true) for ($i = 0; $i < strlen($ins); $i++)
{
    $count++;
    foreach ($a_nodes as $a => $node)
    {
        if ($a_found[$a] ?? false) continue;
        $a_nodes[$a] = $map[$node][$ins[$i] == "L" ? 0 : 1];
        if ($a_nodes[$a][2] == "Z") $a_found[$a] = $count;
    }
    if (count($a_found) == count($a_nodes)) break 2;
}

$part1 = $a_found["AAA"];
$part2 = 1;
foreach ($a_found as $n) $part2 = gmp_lcm($part2, $n);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
