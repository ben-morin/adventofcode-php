<?php

memory_reset_peak_usage();
$start_time = microtime(true);

define("JET", trim(file_get_contents($argv[1] ?? "17.input")));

const ROCKS = [
    [[0,0], [1,0], [2,0], [3,0]],
    [[1,0], [0,1], /*[1,1],*/ [2,1], [1,2]],
    [[0,0], [1,0], [2,0], [2,1], [2,2]],
    [[0,0], [0,1], [0,2], [0,3]],
    [[0,0], [1,0], [0,1], [1,1]]
];

$G = $V = [];
for ($i = 0; $i < 7; $i++) $G["$i,0"] = 1;

$part1 = $part2 = $j = 0;

for ($n = 0; $n < 1e12; $n++)
{
    $h = array_reduce(array_keys($G),
        fn($carry, $key) => max($carry, (int)explode(",", $key)[1]), 0);

    if ($n == 2022)
    {
        $part1 = $h;
        if ($part2) break;
    }

    $x = 2;
    $y = $h + 4;
    $r = $n % 5;

    // jet and rock cycle detection...
    $key = "$j,$r";
    if (!$part2 && isset($V[$key]))
    {
        $_n = $n - $V[$key][0];
        $_h = $h - $V[$key][1];
        if ((1e12 - $n) % $_n == 0)
        {
            $h += intdiv(1e12 - $n, $_n) * $_h;
            $part2 = $h;
            if ($part1) break;
        }
    }
    $V[$key] = [$n, $h];

    // drop loop...
    while (true)
    {
        // jet move...
        $dx = (JET[$j] === ">") ? 1 : -1;
        $j = ($j + 1) % strlen(JET);
        $_move = true;
        foreach (ROCKS[$r] as $p)
        {
            $_x = $x + $p[0] + $dx;
            $_y = $y + $p[1];
            if ($_x < 0 || $_x >= 7 || isset($G["$_x,$_y"])) { $_move = false; break; }
        }
        if ($_move) $x += $dx;
        // check stop...
        foreach (ROCKS[$r] as $p)
        {
            $_x = $x + $p[0];
            $_y = $y + $p[1] - 1;
            if (isset($G["$_x,$_y"])) break 2;
        }
        // drop...
        $y--;
    }
    // settle rock...
    foreach (ROCKS[$r] as $pt)
    {
        $_x = $x + $pt[0];
        $_y = $y + $pt[1];
        $G["$_x,$_y"] = 1;
    }
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
