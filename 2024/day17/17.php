<?php

memory_reset_peak_usage();
$start_time = microtime(true);
const DEBUG = false;

$F = file_get_contents($argv[1] ?? "17.input");
preg_match_all("/\d+/", $F, $P);

$P = array_map("intval", $P[0]);
$A = array_shift($P);
$B = array_shift($P);
$C = array_shift($P);

$part1 = $part2 = 0;

function run($a, $b, $c, array $P): array
{
    $combo = function($operand) use(&$a, &$b, &$c)
    {
        return match ($operand) { 0, 1, 2, 3 => $operand, 4 => $a, 5 => $b, 6 => $c, default => -1 };
    };

    $i = 0;
    $out = [];
    while ($i < count($P))
    {
        $op = $P[$i];
        $operand = $P[$i + 1];
        switch ($op)
        {
            case 0: $a = intdiv($a, pow(2, $combo($operand))); break;
            case 1: $b = $b ^ $operand; break;
            case 2: $b = $combo($operand) % 8; break;
            case 3: if ($a == 0) break; $i = $operand; continue 2;
            case 4: $b = $b ^ $c; break;
            case 5: $out[] = $combo($operand) % 8; break;
            case 6: $b = intdiv($a, pow(2, $combo($operand))); break;
            case 7: $c = intdiv($a, pow(2, $combo($operand))); break;
        }
        $i += 2;
    }
    return $out;
}

function find($a = 0, $n = 1): false|int
{
    global $P;
    if ($n > count($P)) return $a;
    for ($i = 0; $i < 8; $i++)
    {
        $_a = $a << 3 | $i;
        $out = run($_a, 0, 0, $P);
        if ($out == array_slice($P, -$n))
        {
            if (DEBUG)
            {
                echo str_pad($_a, 16, " ", STR_PAD_LEFT), " = ";
                $s = decbin($_a);
                $s = str_pad($s, 16 * 3, "0", STR_PAD_LEFT);
                echo implode(" ", str_split($s, 3)) . " = ";
                echo str_pad(json_encode($out), 33, " ", STR_PAD_LEFT), "\n";
            }
            if (($result = find($_a, $n + 1)) !== false)
                return $result;
        }
    }
    return false;
}

$part1 = implode(",", run($A, $B, $C, $P));
$part2 = find();

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
