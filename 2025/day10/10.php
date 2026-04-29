<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "10.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);

$part1 = $part2 = 0;

foreach ($F as $line)
{
    $line = explode(' ', $line);

    $goal = trim(array_shift($line), "[]");
    $goal = bindec(strrev(strtr($goal, '.#', '01')));

    $jolt = trim(array_pop($line), "{}");
    $jolt = array_map("intval", explode(",", $jolt));

    $B = [];
    foreach ($line as $i => $_b)
    {
        $_b = array_map('intval', explode(',', trim($_b, '()')));
        $B[] = array_reduce($_b, fn($c, $n) => $c | (1 << $n), 0);
    }

    // part 1: brute-force all combinations of buttons...
    $score = INF;
    for ($push = 0, $c = count($B); $push < (1 << $c); $push++)
    {
        if (substr_count(decbin($push), '1') > $score) continue;
        $_res = $_score = 0;
        foreach ($B as $i => $n) if (($push >> $i) & 1) { $_res ^= $n; $_score++; }
        if ($_res == $goal) $score = min($score, $_score);
    }
    $part1 += $score;

    // part 2: using /u/tenthmascot method...
    $part2 += solve($B, $jolt);
}

function patterns(array $B, int $nvars): array
{
    // enumerate button subsets, compute sum pattern, group by parity,
    // keep the minimum button count per pattern...
    $nbuttons = count($B);
    $best = [];
    for ($mask = 0; $mask < (1 << $nbuttons); $mask++)
    {
        $pattern = array_fill(0, $nvars, 0);
        $count = 0;
        for ($b = 0; $b < $nbuttons; $b++) if (($mask >> $b) & 1)
        {
            $count++;
            for ($v = 0; $v < $nvars; $v++) $pattern[$v] += ($B[$b] >> $v) & 1;
        }
        $key = json_encode($pattern);
        if (!isset($best[$key]) || $best[$key] > $count)
            $best[$key] = $count;
    }
    $_res = [];
    foreach ($best as $key => $cost)
    {
        $pattern = json_decode($key);
        $parity = 0;
        foreach ($pattern as $v => $x) $parity |= ($x & 1) << $v;
        $_res[$parity][] = [$pattern, $cost];
    }
    return $_res;
}

function solve(array $B, array $goal): int
{
    // match parity patterns against the goal, subtract, divide by 2, recurse...
    $nvars = count($goal);
    $pat_cost = patterns($B, $nvars);
    $cache = [];

    $solve = function(array $goal) use (&$solve, &$cache, $pat_cost, $nvars): int
    {
        $key = implode(',', $goal);
        if (isset($cache[$key])) return $cache[$key];
        if (!array_sum($goal)) return 0;

        $parity = 0;
        foreach ($goal as $v => $x) $parity |= ($x & 1) << $v;

        $_res = 1e6;
        foreach ($pat_cost[$parity] ?? [] as [$pat, $cost])
        {
            $_goal = [];
            for ($i = 0; $i < $nvars; $i++)
            {
                if ($pat[$i] > $goal[$i]) continue 2;
                $_goal[] = ($goal[$i] - $pat[$i]) >> 1;
            }
            $_res = min($_res, $cost + 2 * $solve($_goal));
        }
        return $cache[$key] = $_res;
    };

    return $solve($goal);
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
