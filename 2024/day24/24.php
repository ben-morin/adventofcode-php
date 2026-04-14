<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = explode("\n\n", trim(file_get_contents($argv[1] ?? "24.input")));

$part1 = $part2 = 0;
$W = $G = $G2 = [];

foreach (explode("\n", trim($F[0])) as $line)
    $W[substr($line, 0, 3)] = (int)substr($line, -1);

foreach (explode("\n", trim($F[1])) as $line)
{
    $line = explode(" ", $line);
    $W[$line[0]] ??= null;
    $W[$line[2]] ??= null;
    $W[$line[4]] ??= null;
    $G[$line[4]] = [$line[0], $line[1], $line[2]];
    $G2["$line[0],$line[1],$line[2]"] = $line[4];
}

function p1($a, $gate, $b)
{
    global $W, $G;
    if (is_null($W[$a])) $W[$a] = p1(...$G[$a]);
    if (is_null($W[$b])) $W[$b] = p1(...$G[$b]);
    if ($gate == "AND")
        $out = $W[$a] & $W[$b];
    else if ($gate == "OR")
        $out = $W[$a] | $W[$b];
    else
        $out = $W[$a] ^ $W[$b];
    assert ($out == 0 || $out == 1);
    return $out;
}

for ($bits = "", $i = 45; $i >= 0; $i--)
    $bits .= p1(...$G["z".substr("0".$i, -2)]);
$part1 = bindec($bits);

/*
    (X)----─+-─:::::::::
            |  :: XOR ::--(M)-----+----─:::::::::
    (Y)--+--|--:::::::::          |     :: XOR ::-----------------(Z)
         |  |                     |  +--:::::::::
         |  +--:::::::::          |  |
         |     :: AND ::--(N)--+  +--|--:::::::::
         +----─:::::::::       |     |  :: AND ::--(R)--::::::::
  (Cin)------------------------|-----+--:::::::::       :: OR ::--(Cout)
                               +------------------------::::::::
*/

function f($a, $gate, $b)
{
    global $G2;
    if (isset($G2[$key = "$a,$gate,$b"])) return $G2[$key];
    if (isset($G2[$key = "$b,$gate,$a"])) return $G2[$key];
    return false;
}

for ($wires = [], $c_in = $i = 0; $i < 45; $i++, $c_in = $c_out)
{
    $key = substr("0{$i}", -2);
    // Cout => Cin
    if ($i == 0)
    {
        $c_out = f("x$key", "AND", "y$key");
        assert($c_out);
        continue;
    }
    // X XOR Y => M
    $m = f("x$key", "XOR", "y$key");
    // X AND Y => N
    $n = f("x$key", "AND", "y$key");
    // C-in AND M => R
    $r = f($c_in, "AND", $m);

    if (!$r)
    {
        [$n, $m] = [$m, $n];
        array_push($wires, $m, $n) ;
        $r = f($c_in, "AND", $m);
    }

    // C-in XOR M => Z
    $z = f($c_in, "XOR", $m);

    if ($m[0] == 'z')
    {
        [$m, $z] = [$z, $m];
        array_push($wires, $m, $z) ;
    }

    if ($n[0] == 'z')
    {
        [$n, $z] = [$z, $n];
        array_push($wires, $n, $z) ;
    }

    if ($r[0] == 'z')
    {
        [$r, $z] = [$z, $r];
        array_push($wires, $r, $z) ;
    }

    // R OR N => C-out
    $c_out = f($r, "OR", $n);

    if ($c_out && $z && $c_out[0] == 'z' && $c_out !== "z45")
    {
        [$c_out, $z] = [$z, $c_out];
        array_push($wires, $c_out, $z) ;
    }
}
sort($wires);
$part2 = implode(",", $wires);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
