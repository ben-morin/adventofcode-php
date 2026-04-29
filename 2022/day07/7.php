<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen( $argv[1] ?? "7.input", "r");

$DIR = ["/" => []];
$PATH = "/";

while ($s = trim(fgets($_fp)))
{
    if ($s == "$ ls") continue;
    if ($s[0] == '$')
    {
        [,,$_cwd] = explode(' ', $s);
        if ($_cwd == "/") continue;
        $PATH = ($_cwd == "..") ? dirname($PATH) : rtrim($PATH, '/')."/{$_cwd}";
        if (!isset($DIR[$PATH])) $DIR[$PATH] = [];
    }
    else // dir or file...
    {
        $_f = explode(' ', $s);
        if ($_f[0] == 'dir') continue;
        foreach ($DIR as $k => $d) if (str_contains($PATH, $k)) $DIR[$k][] = $_f[0];
    }
}

$part1 = 0;
$part2 = [];

foreach ($DIR as $k => $d)
{
    $part2[$k] = array_sum($d);
    if ($part2[$k] <= 100000) $part1 += $part2[$k];
}

$need = 30000000 - (70000000 - array_sum($DIR['/']));
sort($part2);
while (count($part2) && $part2[0] < $need) array_shift($part2);
$part2 = $part2[0];

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
