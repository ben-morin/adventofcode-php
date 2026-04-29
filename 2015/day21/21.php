<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = trim(file_get_contents($argv[1] ?? "21.input"));

// boss stats...
preg_match_all('/(\d+)/', $F, $m);
[$b_hp, $b_dmg, $b_arm] = $m[0];

$W = json_decode("[[8,4,0],[10,5,0],[25,6,0],[40,7,0],[74,8,0]]");
$A = json_decode("[[0,0,0],[13,0,1],[31,0,2],[53,0,3],[75,0,4],[102,0,5]]");
$R = json_decode("[[0,0,0],[0,0,0],[25,1,0],[50,2,0],[100,3,0],[20,0,1],[40,0,2],[80,0,3]]");

$part1 = INF;
$part2 = -INF;

foreach ($W as $_w) foreach ($A as $_a)
    for ($i = 0; $i < count($R) - 1; $i++)
        for ($j = $i + 1; $j < count($R); $j++)
        {
            $boss = $b_hp;
            $player = 100;
            $inv = [$_w, $_a, $R[$i], $R[$j]];
            $cost = array_sum(array_column($inv, 0));
            $p_dmg = array_sum(array_column($inv, 1));
            $p_arm = array_sum(array_column($inv, 2));
            while (true)
            {
                // player...
                $boss -= max(1, $p_dmg - $b_arm);
                if ($boss <= 0) { $part1 = min($part1, $cost); break; }  // player wins
                // boss...
                $player -= max(1, $b_dmg - $p_arm);
                if ($player <= 0) { $part2 = max($part2, $cost); break; } // boss wins
            }
        }

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
