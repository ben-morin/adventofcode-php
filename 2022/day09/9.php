<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "9.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);

const X = 0;
const Y = 1;

function f($n = 2)
{
    global $F;

    $K = array_fill(0, $n, [X,Y]);
    $t_pos = [];

    foreach ($F as $s)
    {
        [$d, $c] = explode(' ', trim($s));
        for ($i = 0; $i < $c; $i++)
        {
            // move head...
            switch ($d)
            {
                case 'R': $K[0][X] += 1; break;
                case 'U': $K[0][Y] += 1; break;
                case 'L': $K[0][X] -= 1; break;
                case 'D': $K[0][Y] -= 1; break;
            }
            // move tails...
            for ($j = 1; $j < count($K); $j++)
            {
                $H = $K[$j - 1];
                $T = &$K[$j];
                if (abs($H[X] - $T[X]) > 1 && $H[Y] == $T[Y])
                    $T[X] += ($H[X] > $T[X]) ? 1 : -1;
                elseif (abs($H[Y] - $T[Y]) > 1 && $H[X] == $T[X])
                    $T[Y] += ($H[Y] > $T[Y]) ? 1 : -1;
                elseif (abs($H[X] - $T[X]) + abs($H[Y] - $T[Y]) > 2)
                {
                    $T[X] += ($H[X] > $T[X]) ? 1 : -1;
                    $T[Y] += ($H[Y] > $T[Y]) ? 1 : -1;
                }
            }
            $t_pos[json_encode($K[array_key_last($K)])] = 1;
        }
    };
    return count($t_pos);
}

$part1 = f();
$part2 = f(10);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
