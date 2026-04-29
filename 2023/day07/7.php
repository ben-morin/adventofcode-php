<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen( $argv[1] ?? "7.input", "r");
$part1 = $part2 = 0;

const FIVE_OF_A_KIND = 6, FOUR_OF_A_KIND = 5, FULL_HOUSE = 4, THREE_OF_A_KIND = 3;
const TWO_PAIR = 2, ONE_PAIR = 1, HIGH_CARD = 0;

$hands = [];
while (!feof($_fp))
{
    $line = trim(fgets($_fp));
    [$cards, $bid] = explode(" ", $line);
    $cards = str_split($cards);
    $cards = array_map(fn($c) => match ($c)
    {
        "T" => 10, "J" => 11, "Q" => 12, "K" => 13, "A" => 14, default => (int)$c
    }, $cards);
    $hands[] = [hand_type($cards), $cards, (int)$bid];
}
fclose($_fp);

function hand_type($cards, $jokers = false): int
{
    $count = array_count_values($cards);
    arsort($count);
    [$count_keys, $count_values] = [array_keys($count), array_values($count)];
    $has_joker = isset($count[1]);

    $type = match($count_values[0])
    {
        5 => FIVE_OF_A_KIND,
        4 => $has_joker ? FIVE_OF_A_KIND : FOUR_OF_A_KIND,
        3 => $count_values[1] == 2 ? FULL_HOUSE : THREE_OF_A_KIND,
        2 => $count_values[1] == 2 ? TWO_PAIR : ONE_PAIR,
        default => HIGH_CARD
    };

    if ($jokers && $has_joker) $type = match ($type)
    {
        FIVE_OF_A_KIND, FOUR_OF_A_KIND, FULL_HOUSE => FIVE_OF_A_KIND,
        THREE_OF_A_KIND => FOUR_OF_A_KIND,
        TWO_PAIR => ($count_keys[0] == 1 || $count_keys[1] == 1) ? FOUR_OF_A_KIND : FULL_HOUSE,
        ONE_PAIR => THREE_OF_A_KIND,
        default => ONE_PAIR
    };

    return $type;
}

sort($hands);
foreach ($hands as $rank => $hand)
{
    $part1 += $hand[2] * ($rank + 1);
    $hands[$rank][1] = array_map(fn($card) => $card == 11 ? 1 : $card, $hand[1]);
    $hands[$rank][0] = hand_type($hands[$rank][1], true);
}

sort($hands);
foreach ($hands as $rank => $hand) $part2 += $hand[2] * ($rank + 1);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
