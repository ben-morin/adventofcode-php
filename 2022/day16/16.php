<?php

ini_set('memory_limit', '2G');
memory_reset_peak_usage();
$start_time = microtime(true);

$lines = file($argv[1] ?? "16.input", FILE_IGNORE_NEW_LINES);
assert($lines !== false);

foreach ($lines as $i => $line)
{
    preg_match_all("/\d+|[A-Z]{2}/", $line, $_a);
    $lines[$i] = [$_a[0][0], (int)$_a[0][1], array_slice($_a[0], 2)];
}
usort($lines, fn($a, $b) => ($a[0] == 'AA') ? -1 : (($b[0] == 'AA') ? 1 : $b[1] - $a[1]));

$M = [];
for ($i = 0; $i < count($lines); $i++) $M[$lines[$i][0]] = $i;

$R = array_fill(0, count($lines), 0);
$E = array_fill(0, count($lines), []);
for ($i = 0; $i < count($lines); $i++)
{
    [$_v, $_r, $edges] = $lines[$i];
    $R[$M[$_v]] = $_r;
    foreach ($edges as $_e) $E[$M[$_v]][] = $M[$_e];
}
unset($lines);
unset($M);

// If I'm at valve [v], I've opened the set of valves [U],
// I have [time] minutes left, and there are [num] other players
// acting after me, how many points possible from this position?

$DP = [];

function f(int $v, int $U, int $time, int $num): int
{
    global $R, $E, $DP;

    if (!$time) return ($num ? f(0, $U, 26, $num - 1) : 0);

    // calculate integer key...
    $key = ($U * count($R) * 31 * 2) + ($v * 31 * 2) + ($time * 2) + $num;
    if (isset($DP[$key])) return $DP[$key];

    $result = 0;
    $no_v = (($U & (1 << $v)) == 0);

    if ($no_v && $R[$v] > 0)
    {
        $_U = ($U | (1 << $v));
        assert($_U > $U);
        $result = max($result, ($time - 1) * $R[$v] + f($v, $_U, $time - 1, $num));
    }

    foreach ($E[$v] as $y) $result = max($result, f($y, $U, $time - 1, $num));

    return $DP[$key] = $result;
}

$part1 = f(0, 0, 30, 0);
$part2 = f(0, 0, 26, 1);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4) . " seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
