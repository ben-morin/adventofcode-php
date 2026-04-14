<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$P = file($argv[1] ?? "23.input", FILE_IGNORE_NEW_LINES);
assert($P !== false);

for($i = 0; $i < count($P); $i++)
{
    $ins = explode(", ", str_replace("jmp", "jmp x,", $P[$i]));
    $P[$i] = [...explode(" ", $ins[0]), (int)($ins[1] ?? 0)];
}

function f($P, $a = 0)
{
    $b = $ip = 0;
    while ($ip < count($P))
    {
        [$op, $reg, $arg] = $P[$ip];
        switch($op)
        {
            case "hlf": $$reg >>= 1; break;
            case "tpl": $$reg *= 3; break;
            case "inc": $$reg++; break;
            case "jie": if ($$reg % 2) $arg = 1; break;
            case "jio": if ($$reg != 1) $arg = 1; break;
        }
        $ip += ($op[0] == "j" ? $arg : 1);
    }
    return $b;
}

$part1 = f($P);
$part2 = f($P, 1);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
