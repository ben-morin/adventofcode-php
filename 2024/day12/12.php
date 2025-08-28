<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$G = file($argv[1] ?? "12.input", FILE_IGNORE_NEW_LINES);
assert($G !== false);

$ROWS = count($G);
$COLS = strlen($G[0]);
const DIR = [[-1,0],[0,1],[1,0],[0,-1]];

$part1 = $part2 = 0;

function fill($start, &$V)
{
    global $G, $ROWS, $COLS;

    $Q = [$start];
    $A = $P = $S = 0;
    $P2 = [];

    while ($Q)
    {
        [$r, $c] = array_shift($Q);
        if (isset($V[$key = "$r,$c"])) continue;
        $V[$key] = 1;
        $A++;
        foreach (DIR as [$dr,$dc])
        {
            [$_r, $_c] = [$r + $dr, $c + $dc];
            if ($_r < 0 || $_r >= $ROWS || $_c < 0 || $_c >= $COLS || $G[$_r][$_c] != $G[$r][$c])
            {
                $P++;
                $P2["$dr,$dc"]["$r,$c"] = 1;
            }
            else $Q[] = [$_r, $_c];
        }
    }

    foreach ($P2 as $_p)
    {
        $V2 = [];
        foreach ($_p as $pk => $_) if (!isset($V2[$pk]))
        {
            $S++;
            $Q = [explode(",", $pk)];
            while ($Q)
            {
                [$r, $c] = array_shift($Q);
                if (isset($V2[$key = "$r,$c"])) continue;
                $V2[$key] = 1;
                foreach (DIR as [$dr,$dc])
                {
                    [$_r, $_c] = [$r + $dr, $c + $dc];
                    if (isset($_p["$_r,$_c"])) $Q[] = [$_r, $_c];
                }
            }
        }
    }
    return [$A * $P, $A * $S];
}

$V = [];
for($r = 0; $r < $ROWS; $r++) for ($c = 0; $c < $COLS; $c++) if (!isset($V["$r,$c"]))
{
    $ap = fill([$r, $c], $V);
    $part1 += $ap[0];
    $part2 += $ap[1];
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
