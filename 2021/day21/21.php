<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "21.input", FILE_IGNORE_NEW_LINES);
$P1 = (int)substr(trim($F[0]), -2);
$P2 = (int)substr(trim($F[1]), -2);

// part 1: deterministic 100-sided die, win at 1000...
$pos = [$P1, $P2];
$score = [0, 0];
$rolls = $die = $turn = 0;
while ($score[0] < 1000 && $score[1] < 1000)
{
    $sum = 0;
    for ($i = 0; $i < 3; $i++) { $die = ($die + 1) % 100; $sum += $die; }
    $rolls += 3;
    $pos[$turn] = ($pos[$turn] + $sum - 1) % 10 + 1;
    $score[$turn] += $pos[$turn];
    $turn = 1 - $turn;
}
$part1 = min($score) * $rolls;

// part 2: quantum d3 × 3 roll-sum distribution...
const D3 = [[3,1],[4,3],[5,6],[6,7],[7,6],[8,3],[9,1]];

// current player, other player, current score, other score...
function wins($cp, $op, $cs, $os)
{
    static $_cache = [];
    $key = ($cp << 14) | ($op << 10) | ($cs << 5) | $os;
    if (isset($_cache[$key])) return $_cache[$key];
    $result = [0, 0]; // ...[cp, op] wins
    foreach (D3 as [$roll, $mult])
    {
        $_cp = ($cp + $roll - 1) % 10 + 1;
        $_cs = $cs + $_cp;
        // if current player wins this roll...
        if ($_cs >= 21) { $result[0] += $mult; continue; }
        // otherwise, give other player a turn...
        [$_op, $_cp] = wins($op, $_cp, $os, $_cs);
        $result[0] += $_cp * $mult; // our wins from other's turn
        $result[1] += $_op * $mult; // other's wins from other's turn
    }
    return $_cache[$key] = $result;
}

$part2 = max(wins($P1, $P2, 0, 0));

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
