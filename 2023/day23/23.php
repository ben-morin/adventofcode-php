<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$M = file_get_contents("23.input");
$M = explode("\n", trim($M));
$Mh = count($M);
$Mw = strlen($M[0]);

const MOVES = [[0, -1, "^"], [1, 0, ">"], [0, 1, "v"], [-1, 0, "<"]];

$SLOPES = [];
for ($y = 0; $y < $Mh; $y++) for ($c = $x = 0; $x < $Mw; $x++, $c = 0)
{
    if ($M[$y][$x] != ".") continue;
    foreach (MOVES as [$dx, $dy,])
    {
        [$nx, $ny] = [$x + $dx, $y + $dy];
        if ($nx < 0 || $nx >= $Mw || $ny < 0 || $ny >= $Mh) continue;
        if (str_contains("<>^v", $M[$ny][$nx])) $c++;
    }
    if ($c > 2) $SLOPES[] = [$x, $y];
}

$SLOPES[] = $start =  [strpos($M[0], '.'), 0];
$SLOPES[] = $end = [strpos($M[$Mh-1], '.'), $Mh - 1];

function f($part = 1)
{
    global $M, $Mh, $Mw, $SLOPES, $start, $end;

    $PATHS = [];
    foreach ($SLOPES as [$px, $py])
    {
        $PATHS["$px,$py"] = [];
        $Q = [[$px, $py, 0]];
        $C = [];
        while (count($Q))
        {
            [$x, $y, $d] = array_shift($Q);
            if (isset($C["$x,$y"])) continue;
            $C["$x,$y"] = 1;

            if (in_array([$x, $y], $SLOPES) && [$x, $y] != [$px, $py])
            {
                $PATHS["$px,$py"][] = [$x, $y, $d];
                continue;
            }

            foreach (MOVES as [$dx, $dy, $dc])
            {
                [$nx, $ny] = [$x + $dx, $y + $dy];
                if ($nx < 0 || $nx >= $Mw || $ny < 0 || $ny >= $Mh || $M[$ny][$nx] == "#") continue;
                if ($part == 1 && str_contains("<>^v", $M[$y][$x]) && $M[$y][$x] != $dc) continue;
                $Q[] = [$nx, $ny, $d + 1];
            }
        }
    }

    $DFS = function($x, $y, $d = 0, $result = 0, &$C = []) use (&$DFS, $PATHS, $end)
    {
        $key = "$x,$y";
        if (isset($C[$key])) return $result;
        $C[$key] = 1;
        if ([$x, $y] == $end) $result = max($result, $d);
        foreach ($PATHS[$key] as [$px, $py, $pd])
            $result = $DFS($px, $py, $d + $pd, $result, $C);
        unset($C[$key]);
        return $result;
    };
    return $DFS(...$start);
}

$part1 = f(1);
$part2 = f(2);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
