<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen($argv[1] ?? "23.input", "r");

$E = [];
for ($r = 0; $line = trim(fgets($_fp)); $r++)
    for ($c = 0; $c < strlen($line); $c++)
        if ($line[$c] == '#')
            $E["$r,$c"] = 0;
fclose($_fp);

$MOVES = [
    /* N */ [[-1,-1], [-1, 0], [-1, 1]],
    /* S */ [[ 1,-1], [ 1, 0], [ 1, 1]],
    /* W */ [[-1,-1], [ 0,-1], [ 1,-1]],
    /* E */ [[-1, 1], [ 0, 1], [ 1, 1]]
];

$part1 = $part2 = $round = 0;

while (true)
{
    $part2 = ++$round;
    $M = [];
    foreach ($E as $elf => $_)
    {
        // elf position...
        [$r, $c] = array_map("intval", explode(",", $elf));

        // check neighbors...
        $_stay = true;
        foreach ([-1, 0, 1] as $_r) foreach ([-1, 0, 1] as $_c) if ($_r || $_c)
        {
            if (!isset($E[sprintf("%s,%s", $r + $_r, $c + $_c)])) continue;
            $_stay = false;
            break 2;
        }
        if ($_stay) continue;

        // propose moves...
        foreach ($MOVES as $mv)
        {
            if (isset($E[sprintf("%s,%s", $r + $mv[0][0], $c + $mv[0][1])])) continue;
            if (isset($E[$k = sprintf("%s,%s", $r + $mv[1][0], $c + $mv[1][1])])) continue;
            if (isset($E[sprintf("%s,%s", $r + $mv[2][0], $c + $mv[2][1])])) continue;
            $M[$k][] = $elf;
            break;
        }
    }

    // execute moves if uncontested...
    $_moved = false;
    foreach ($M as $to => $from) if (count($from) == 1)
    {
        $_moved = true;
        unset($E[$from[0]]);
        $E[$to] = 0;
    }
    if (!$_moved) break;

    // part1...
    if ($round == 10)
    {
        [$r1, $r2, $c1, $c2, $count] = [INF, -INF, INF, -INF, 0];
        foreach (array_keys($E) as $v)
        {
            [$r, $c] = explode(",", $v);
            $r1 = min($r1, $r);
            $r2 = max($r2, $r);
            $c1 = min($c1, $c);
            $c2 = max($c2, $c);
            $count++;
        }
        $part1 = (($r2 - $r1 + 1) * ($c2 - $c1 + 1)) - $count;
    }

    // rotate move list...
    $MOVES[] = array_shift($MOVES);
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: " . round(microtime(true) - $start_time, 4) . " seconds\n";
echo "   Peak memory: " . round(memory_get_peak_usage() / pow(2, 20), 4), " MiB\n\n";
