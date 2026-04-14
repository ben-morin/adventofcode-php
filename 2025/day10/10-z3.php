<?php

exec("which z3", $_, $res);
if ($res !== 0) die("z3 is required for this solution.\n");

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
    $jolt = array_map(fn($x) => [$x, []], $jolt);

    $B = $Bi = [];
    foreach ($line as $i => $_b)
    {
        $_b = array_map('intval', explode(',', trim($_b, '()')));
        $B[] = array_reduce($_b, fn($c, $n) => $c | (1 << $n), 0);
        foreach ($_b as $n) $jolt[$n][1][] = "B{$i}";
        $Bi[] = "B{$i}";
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

    // part 2: build smt-lib problem...
    $smt = "(set-option :produce-models true)";
    foreach ($Bi as $_b)
    {
        $smt .= "(declare-fun {$_b} () Int)";
        $smt .= "(assert (>= {$_b} 0))";
    }
    foreach ($jolt as $i => [$target, $_b]) $smt .= match (count($_b))
    {
        0 => "",
        1 => "(assert (= {$_b[0]} $target))",
        default => "(assert (= (+ " . implode(" ", $_b) . ") $target))"
    };
    $smt .= "(minimize (+ " . implode(" ", $Bi) . "))";
    $smt .= "(check-sat)(get-model)";

    $output = shell_exec("z3 -in <<< " . escapeshellarg($smt));
    preg_match_all('/\s([0-9]+)\)/', $output, $m);

    $part2 += array_sum($m[1] ?? []);
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
