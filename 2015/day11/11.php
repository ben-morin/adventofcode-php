<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$P = trim(file_get_contents($argv[1] ?? "11.input"));

function ilo($s)
{
    while (preg_match("/[iol]/", $s, $m, PREG_OFFSET_CAPTURE ))
    {
        $pos = (int)$m[0][1];
        $s[$pos] = chr(ord($s[$pos]) + 1);
        for ($i = $pos + 1; $i < strlen($s); $i++) $s[$i] = 'a';
    }
    return $s;
}

function inc($s, $pos = null)
{
    if ($pos == null) $pos = strlen($s) - 1;
    assert($pos >= 0 && $pos < strlen($s));
    $n = ord($s[$pos]) + 1;
    if ($n > ord('z'))
    {
        $s[$pos] = 'a';
        return inc($s, $pos - 1);
    }
    $s[$pos] = chr($n);
    return $s;
}

function password($s)
{
    $s = ilo($s);
    // abcxxyzz
    $n = ord($s[3]);
    if ($n > ord('x'))
    {
        $s = substr($s, 0, 3) . "aabcc";
        return inc($s, 2);
    }
    foreach ([ord('i'), ord('l'), ord('o')] as $i)
        if ($n >= $i - 2 && $n <= $i)
            $n = $i + 1;
    $s[3] = $s[4] = chr($n);
    $s[5] = chr($n +1);
    $s[6] = $s[7] = chr($n + 2);
    return $s;
}

$part1 = password($P);
$part2 = password(inc($part1, 3));

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
