<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$F = file($argv[1] ?? "4.input", FILE_IGNORE_NEW_LINES);
assert($F !== false);

$part1 = $part2 = 0;

$L = ['M', 'A', 'S'];
$D1 = [[0,1],[1,1],[1,0],[1,-1],[0,-1],[-1,-1],[-1,0],[-1,1]];
$D2 = [[1,1],[1,-1],[-1,-1],[-1,1]];

for ($r = 0; $r < count($F); $r++) for ($c = 0; $c < strlen($F[$r]); $c++)
{
    if ($F[$r][$c] == "X") foreach ($D1 as [$dr, $dc])
    {
        foreach ($L as $i => $l)
        {
            [$_r, $_c] = [$r + ($i + 1) * $dr, $c + ($i + 1) * $dc];
            if ($_r < 0 || $_r >= count($F) || $_c < 0 || $_c >= strlen($F[0])) continue 2;
            if ($F[$_r][$_c] !== $l) continue 2;
        }
        $part1++;
    }
    if ($F[$r][$c] == "A")
    {
        $ms = "";
        foreach ($D2 as [$dr, $dc])
        {
            [$_r, $_c] = [$r + $dr, $c + $dc];
            if ($_r < 0 || $_r >= count($F) || $_c < 0 || $_c >= strlen($F[0])) continue 2;
            $ms .= $F[$_r][$_c];
        }
        if (str_contains("MSSM,MMSS,SSMM,SMMS", $ms)) $part2++;
    }
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
