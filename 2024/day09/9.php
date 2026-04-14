<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$D = file_get_contents($argv[1] ?? "9.input");

$part1 = $part2 = 0;

// 16 bits id, 4 bits len...
const BLANK = (1 << 16) - 1;

$C = [];
for ($i = 0; $i < strlen($D); $i++)
    if ($i % 2 == 0)
        $C[] = (intdiv($i, 2) << 4) | $D[$i];
    else if ((int)$D[$i])
        $C[] = (BLANK << 4) | $D[$i];

function f($C, $P2 = false)
{
    $left = array_fill(0, 10, 1);
    $right = count($C) - 1;
    $target = ($C[$right] >> 4) & 0xFFFF;

    while (0 < $right && $target > 0)
    {
        for ($r = $right; $r > 1; $r--)
        {
            [$fid, $flen] = [($C[$r] >> 4) & 0xFFFF, $C[$r] & 0xF];
            if ($fid == $target) { $right = $r; break; }
        }
        $need = $P2 ? $flen : 1;
        for ($l = $left[$need]; $l < $right; $l++)
        {
            [$bid, $blen] = [($C[$l] >> 4) & 0xFFFF, $C[$l] & 0xF];
            if ($bid == BLANK && $blen >= $need)
            {
                $left[$need] = $l;
                if ($P2 || $blen >= $flen)
                {
                    $C[$right] = (BLANK << 4) | $flen;
                    $blen -= $flen;
                    if ($blen == 0) { $C[$l] = ($fid << 4) | $flen; break; }
                    $C[$l] = (BLANK << 4) | $blen;
                    array_splice($C, $l, 0, ($fid << 4) | $flen);
                    $right++;
                    break;
                }
                $C[$l] = ($fid << 4) | $blen;
                $flen -= $blen;
                $C[$right] = ($fid << 4) | $flen;
                continue 2;
            }
        }
        $target--;
        $right--;
    }

    $result = 0;
    for ($i = $pos = 0; $i < count($C); $i++)
    {
        [$fid, $flen] = [($C[$i] >> 4) & 0xFFFF, $C[$i] & 0xF];
        for ($j = 0; $j < $flen; $j++)
            $result += $pos++ * ($fid == BLANK ? 0 : $fid);
    }
    return $result;
}

$part1 = f($C);
$part2 = f($C, true);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
