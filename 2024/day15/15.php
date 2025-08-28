<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$G = file_get_contents($argv[1] ?? "15.input");
[$G, $M] = explode("\n\n", $G);

$G = explode("\n", $G);
for ($i = 0; $i < count($G); $i++) if (str_contains($G[$i], "@"))
{
    [$r, $c] = [$i, strpos($G[$i], "@")];
    break;
}
$M = str_replace("\n", "", $M);
const DIR = ['^' => [-1,0], '>' => [0,1], 'v' => [1,0], '<' => [0,-1]];

$part1 = $part2 = 0;

function run($G, $start, $M)
{
    $ROWS = count($G);
    $COLS = strlen($G[0]);
    [$r, $c] = $start;
    for ($i = 0; $i < strlen($M); $i++)
    {
        [$dr, $dc] = DIR[$M[$i]];
        [$_r, $_c] = [$r + $dr, $c + $dc];
        switch ($G[$_r][$_c])
        {
            case '#': continue 2;
            case '.':
            case '@':
                [$r, $c] = [$_r, $_c]; continue 2;
            case 'O':
            case '[':
            case ']':
                $Q = [[$r, $c]];
                $V = [];
                while ($Q)
                {
                    [$_r, $_c] = array_shift($Q);
                    if (isset($V[$key = "$_r,$_c"])) continue;
                    $V[$key] = 1;
                    [$nr, $nc] = [$_r + $dr, $_c + $dc];
                    switch ($t = $G[$nr][$nc])
                    {
                        case '#': continue 4;
                        case 'O': $Q[] = [$nr, $nc]; break;
                        case '[':
                        case ']':
                            $Q[] = [$nr, $nc];
                            $Q[] = [$nr, $nc + ($t == '[' ? 1 : -1)];
                    }
                }
                while ($V) foreach (array_keys($V) as $key)
                {
                    [$_r, $_c] = explode(',', $key);
                    [$nr, $nc] = [$_r + $dr, $_c + $dc];
                    if (!isset($V["$nr,$nc"]))
                    {
                        $G[$nr][$nc] = $G[$_r][$_c];
                        $G[$_r][$_c] = '.';
                        unset($V["$_r,$_c"]);
                    }
                }
                [$r, $c] = [$r + $dr, $c + $dc];
        }
    }
    $gps = 0;
    for ($r = 0; $r < $ROWS; $r++) for ($c = 0; $c < $COLS; $c++)
        if (in_array($G[$r][$c], ['[', 'O'])) $gps += 100 * $r + $c;
    return $gps;
}

$part1 = run($G, [$r, $c], $M);

for ($i = 0; $i < count($G); $i++)
{
    $row = $G[$i];
    $row = str_replace(['#', 'O', '.','@'], ['##', '[]', '..','@.'], $row);
    $G[$i] = $row;
};

$part2 = run($G, [$r, $c * 2], $M);


echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
