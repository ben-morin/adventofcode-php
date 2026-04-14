<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen( $argv[1] ?? "19.input", "r");

$BP = [];
$part1 = 0;
while (!feof($_fp) && $line = trim(fgets($_fp)))
{
    preg_match_all('/\d+/', $line, $m);
    $bp = array_map('intval', $m[0]);
    $part1 += $bp[0] * calc($bp, 24);
    $BP[] = $bp;
}
fclose($_fp);

$part2 = 1;
for ($i = 0; $i < min(count($BP), 3); $i++)
    $part2 *= calc($BP[$i], 32);

function calc($bp, $time)
{
    [, $cost_o, $cost_c, $cost_bo, $cost_bc, $cost_go, $cost_gb] = $bp;
    $max_geodes = 0;
    $max_ore_cost = max($cost_o, $cost_c, $cost_bo, $cost_go);

    $Q = [[0, 0, 0, 0, 1, 0, 0, 0, $time]];
    $visited = [];

    while ($Q)
    {
        // next state...
        $state = array_shift($Q);
        [$o, $c, $b, $g, $ro, $rc, $rb, $rg, $t] = $state;

        // remember best state...
        $max_geodes = max($max_geodes, $g);

        // done...
        if (!$t) continue;

        // don't visit more than once...
        if (array_key_exists($key = json_encode($state), $visited)) continue;
        $visited[$key] = true;

        // build geode if we have the ore and obsidian, always...
        if ($o >= $cost_go && $b >= $cost_gb)
        {
            $Q[] = [$o-$cost_go+$ro, $c+$rc, $b-$cost_gb+$rb, $g+$rg, $ro, $rc, $rb, $rg+1, $t-1];
            continue; // best move, do nothing else...
        }

        // build obsidian robot if we have the ore, clay, and we need more to build geode robots...
        if ($o >= $cost_bo && $c >= $cost_bc && $rb < $cost_gb)
        {
            $Q[] = [$o-$cost_bo+$ro, $c-$cost_bc+$rc, $b+$rb, $g+$rg, $ro, $rc, $rb+1, $rg, $t-1];
            continue; // best move, do nothing else...
        }

        // build clay robot if we have the ore, and we need more to build obsidian robots...
        if ($o >= $cost_c && $rc < $cost_bc)
            $Q[] = [$o-$cost_c+$ro, $c+$rc, $b+$rb, $g+$rg, $ro, $rc+1, $rb, $rg, $t-1];

        // build ore robot if we have the ore, and we need more ore to build robots...
        if ($o >= $cost_o && $ro < $max_ore_cost)
            $Q[] = [$o-$cost_o+$ro, $c+$rc, $b+$rb, $g+$rg, $ro+1, $rc, $rb, $rg, $t-1];

        // build nothing, just collect resources if we need more ore to build robots...
        if ($o <= $max_ore_cost)
            $Q[] = [$o+$ro, $c+$rc, $b+$rb, $g+$rg, $ro, $rc, $rb, $rg, $t-1];
    }

    return $max_geodes;
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
