<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$cards = explode("\n\n", file_get_contents($argv[1] ?? "4.input"));

$bingo = explode(',', array_shift($cards));
$cards = array_map(fn($c) => array_map(
    fn($r) => preg_split('/\s+/', trim($r)),
    explode("\n", trim($c))
), $cards);

$part1 = $part2 = 0;
$win = array_fill(0, 5, '*');

foreach ($bingo as $call)
{
    // mark...
    array_walk_recursive($cards, function(&$v) use ($call) {
        if ($v === $call) $v = '*';
    });

    // check for winners...
    foreach ($cards as $c => $card) for ($i = 0; $i < 5; $i++)
    {
        if ($card[$i] === $win || array_column($card, $i) === $win)
        {
            $sum = array_sum(array_filter(array_merge(...$card), fn($v) => $v !== '*'));
            $score = $sum * $call;
            if (!$part1) $part1 = $score;
            $part2 = $score;
            unset($cards[$c]);
            break;
        }
    }
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
