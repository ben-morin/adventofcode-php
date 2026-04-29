<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "24.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);

/*
   each 18-instruction block has three per-digit parameters at fixed offsets:
   - line  4: "div z {DZ}"   -- 1 (push) or 26 (pop)
   - line  5: "add x {AX}"
   - line 15: "add y {AY}"
*/

$AX = $DZ = $AY = [];
for ($i = 0; $i < 14; $i++)
{
    $b = $i * 18;
    $DZ[] = (int)explode(' ', $F[$b + 4])[2];
    $AX[] = (int)explode(' ', $F[$b + 5])[2];
    $AY[] = (int)explode(' ', $F[$b + 15])[2];
}

// match each pop (DZ=26) with its corresponding push (DZ=1) via a stack;
// each pair gives a constraint w[pop] - w[push] = AY[push] + AX[pop]
$max = $min = array_fill(0, 14, 0);
$stack = [];
for ($i = 0; $i < 14; $i++)
{
    if ($DZ[$i] == 1) { $stack[] = $i; continue; }
    $push = array_pop($stack);
    $d = $AY[$push] + $AX[$i];
    if ($d >= 0)
    {
        // w[pop] = w[push] + d, so the larger digit is on the pop side
        $max[$i] = 9; $max[$push] = 9 - $d;
        $min[$push] = 1; $min[$i] = 1 + $d;
    }
    else // $d < 0
    {
        // w[push] = w[pop] - d, so the larger digit is on the push side
        $max[$push] = 9; $max[$i] = 9 + $d;
        $min[$i] = 1; $min[$push] = 1 - $d;
    }
}

$part1 = implode('', $max);
$part2 = implode('', $min);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
