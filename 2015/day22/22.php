<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = trim(file_get_contents($argv[1] ?? "22.input"));

// boss stats...
preg_match_all('/(\d+)/', $F, $m);
$B = $m[0];

// player stats...
$P = [50, 500];

// cost, dmg, heal, turns, val...
$SPELLS = [
    "m" => [53,4,0,0,0],  "d" => [73,2,2,0,0],
    "s" => [113,0,0,6,7], "p" => [173,0,0,6,3], "r"=>[229,0,0,5,101]
];

// state keys...
$STATE_KEYS = ["total", "mana", "p_hp", "b_hp", "s_turns", "p_turns", "r_turns"];

function effects(&$_s): int
{
    global $SPELLS;
    $p_arm = 0;
    if ($_s["s_turns"] > 0) { $p_arm = $SPELLS["s"][4]; $_s["s_turns"]--; }
    if ($_s["p_turns"] > 0) { $_s["b_hp"] -= $SPELLS["p"][4]; $_s["p_turns"]--; }
    if ($_s["r_turns"] > 0) { $_s["mana"] += $SPELLS["r"][4]; $_s["r_turns"]--; }
    return $p_arm;
}

function f($b_stats, $p_stats, $part2 = false)
{
    global $SPELLS, $STATE_KEYS;

    [$b_hp, $b_dmg] = $b_stats;
    [$p_hp, $p_mana] = $p_stats;
    $MIN_MANA = min(array_column($SPELLS, 0));

    $Q = new SplMinHeap();
    $Q->insert([0, $p_mana, $p_hp, $b_hp, 0, 0, 0]);
    $V = [];

    while ($Q->count())
    {
        $state = array_combine($STATE_KEYS, $Q->extract());
       if ($V[$v_key = json_encode(array_values($state))] ?? 0) continue;
        $V[$v_key] = 1;

        // hard mode...
        if ($part2 && --$state["p_hp"] <= 0) continue; // player dead

        // player...
        effects($state);
        if ($state["b_hp"] <= 0) return $state["total"]; // boss dead
        if ($state["mana"] < $MIN_MANA) continue; // player dead

        foreach ($SPELLS as $spell => [$cost, $dmg, $heal, $turns, $val])
        {
            $_s = $state;
            if ($_s["mana"] < $cost) continue; // not enough mana

            // spell state changes...
            $_s["total"] += $cost;
            $_s["mana"] -= $cost;
            $_s["b_hp"] -= $dmg;  // "m|d"
            $_s["p_hp"] += $heal; // "d"
            if ($_s["b_hp"] <= 0) return $_s["total"]; // boss dead
            if ($turns) // "s|p|r"
            {
                if ($_s["{$spell}_turns"]) continue; // already active
                $_s["{$spell}_turns"] = $turns; // activate effect
            }

            // boss...
            $p_arm = effects($_s);
            if ($_s["b_hp"] <= 0) return $_s["total"]; // boss dead
            $_s["p_hp"] -= max(1, $b_dmg - $p_arm);
            if ($_s["p_hp"] <= 0) continue; // player dead

            // push new state...
            $Q->insert(array_values($_s));
        }
    }
    assert(false, "No solution found");
}

$part1 = f($B, $P);
$part2 = f($B, $P, true);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
