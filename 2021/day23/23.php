<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "23.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);

const ROOM_X = [2, 4, 6, 8];
const HALLWAY = [0, 1, 3, 5, 7, 9, 10];
const COST = ['A' => 1, 'B' => 10, 'C' => 100, 'D' => 1000];
const TARGET = ['A' => 0, 'B' => 1, 'C' => 2, 'D' => 3];
const TYPE = ['A', 'B', 'C', 'D'];

// state encoding: 11 hallway positions + 4*depth room positions
// rooms are stored top-to-bottom: positions 11..11+depth-1 are room 0, etc.
// part 1 state goal: ...........AABBCCDD
// part 2 state goal: ...........AAAABBBBCCCCDDDD

$S1 = $S2 = [];
foreach ([1,2] as $part)
{
    $depth = count($F) - 3;
    $state = str_repeat('.', 11);
    for ($r = 0; $r < count(ROOM_X); $r++) for ($d = 0; $d < $depth; $d++)
        $state .= $F[2 + $d][3 + $r * 2];
    ${"S{$part}"} = [$state, $depth];
    // add extra rooms for part 2...
    array_splice($F, 3, 0, ["  #D#C#B#A#", "  #D#B#A#C#"]);
}

function moves($state, $depth)
{
    // hallway to target room...
    $result = [];
    for ($hx = 0; $hx < 11; $hx++)
    {
        $A = $state[$hx];
        if ($A == '.') continue;
        $R = TARGET[$A];
        $rs = 11 + $R * $depth;
        $rx = ROOM_X[$R];

        // target room must have a slot and be empty
        // or contain only correct type...
        $slot = -1;
        for ($d = 0; $d < $depth; $d++)
        {
            $c = $state[$rs + $d];
            if ($c == '.') $slot = $d;
            else if ($c != $A) continue 2;
        }
        if ($slot == -1) continue;

        // hallway path [hx, rx) must be clear...
        $lo = min($hx, $rx); $hi = max($hx, $rx);
        for ($i = $lo; $i <= $hi; $i++) if ($i != $hx)
            if ($state[$i] != '.') continue 2;

        $cost = (abs($hx - $rx) + $slot + 1) * COST[$A];
        $_state = $state;
        $_state[$hx] = '.';
        $_state[$rs + $slot] = $A;
        $result[] = [$_state, $cost];
    }
    if ($result) return $result;

    // room to hallway, not explored if we found any hallway to target room...
    for ($R = 0; $R < 4; $R++)
    {
        $target = TYPE[$R];
        $rs = 11 + $R * $depth;

        // find top amphipod in this room...
        $top = -1;
        for ($d = 0; $d < $depth; $d++)
            if ($state[$rs + $d] != '.') { $top = $d; break; }
        if ($top == -1) continue;

        // if [top, bottom] is correct type, don't move anyone out...
        $settled = true;
        for ($d = $top; $d < $depth; $d++)
            if ($state[$rs + $d] != $target) { $settled = false; break; }
        if ($settled) continue;

        $A = $state[$rs + $top];
        $rx = ROOM_X[$R];
        $base_cost = $top + 1; // steps to climb out of room

        foreach (HALLWAY as $hx)
        {
            // hallway path (hx, rx) must be clear...
            $lo = min($rx, $hx); $hi = max($rx, $hx);
            $clear = true;
            for ($i = $lo; $i <= $hi; $i++)
                if ($state[$i] != '.') { $clear = false; break; }
            if (!$clear) continue;

            $cost = ($base_cost + abs($hx - $rx)) * COST[$A];
            $_state = $state;
            $_state[$rs + $top] = '.';
            $_state[$hx] = $A;
            $result[] = [$_state, $cost];
        }
    }
    return $result;
}

function solve($state, $depth)
{
    $goal = str_repeat('.', 11);
    foreach (TYPE as $t) $goal .= str_repeat($t, $depth);

    $D = [$state => 0];
    $Q = new SplPriorityQueue();
    $Q->insert([$state, 0], 0);

    while (!$Q->isEmpty())
    {
        [$state, $cost] = $Q->extract();
        if ($state === $goal) return $cost;
        if ($cost > $D[$state]) continue;
        foreach (moves($state, $depth) as [$_state, $move_cost])
        {
            $_cost = $cost + $move_cost;
            if (!isset($D[$_state]) || $_cost < $D[$_state])
            {
                $D[$_state] = $_cost;
                $Q->insert([$_state, $_cost], -$_cost);
            }
        }
    }
    return -1;
}

$part1 = solve(...$S1);
$part2 = solve(...$S2);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
