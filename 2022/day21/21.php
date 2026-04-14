<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen($argv[1] ?? "21.input", "r");

$M = [];
while ($line = trim(fgets($_fp)))
{
    [$k, $v] = explode(': ', $line);
    $v = explode(' ', $v);
    if (count($v) == 1) $v = (int)$v[0];
    $M[$k] = $v;
}
fclose($_fp);

function f1($monkey = 'root', &$human = false)
{
    global $M;

    if (is_int($M[$monkey]))
    {
        $human = ($monkey == 'humn');
        return $M[$monkey];
    }

    [$ma, $op, $mb] = $M[$monkey];
    $a = f1($ma, $ha);
    $b = f1($mb, $hb);
    $human = $ha || $hb;

    return match ($op)
    {
        '+' => $a + $b,
        '-' => $a - $b,
        '*' => $a * $b,
        '/' => intdiv($a, $b)
    };
}

function f2($monkey = 'root', $yell = 0)
{
    global $M;

    if ($monkey == 'humn') return $yell;

    [$ma, $op, $mb] = $M[$monkey];
    $a = f1($ma, $ha);
    $b = f1($mb, $hb);

    if ($ha || $hb)
    {
        $_m = ($ha ? $ma : $mb);
        $_y = ($ha ? $b : $a);
        $_s = ($ha ? 1 : -1);

        if ($monkey == 'root') return f2($_m, $_y);

        return match ($op)
        {
            '+' => f2($_m, $yell - $_y),
            '-' => f2($_m, ($yell * $_s) + $_y),
            '*' => f2($_m, intdiv($yell, $_y)),
            '/' => f2($_m, pow($yell, $_s) * $_y)
        };
    }
}

$part1 = f1();
$part2 = f2();

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
