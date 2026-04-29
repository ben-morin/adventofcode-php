<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$G = file($argv[1] ?? "25.input", FILE_IGNORE_NEW_LINES);
assert($G !== false);

$ROWS = count($G);
$COLS = strlen($G[0]);

$part1 = $part2 = 0;

while (true)
{
    $part1++;
    $moved = false;
    // east phase: '>.' becomes '.>' with wrap...
    for ($r = 0; $r < $ROWS; $r++)
    {
        $row = $G[$r];
        $_row = str_replace('>.', '.>', $row);
        if ($row[0] == '.' && $row[$COLS-1] == '>')
        {
            $_row[0] = '>';
            $_row[$COLS-1] = '.';
        }
        if ($_row !== $row)
        {
            $G[$r] = $_row;
            $moved = true;
        }
    }
    // south phase: column-wise, with wrap...
    for ($c = 0; $c < $COLS; $c++)
    {
        $top = $G[0][$c];
        foreach ($G as $r => $row)
        {
            $_r = ($r + 1) % $ROWS;
            $next = ($_r == 0) ? $top : $G[$_r][$c];
            if ($row[$c] == 'v' && $next == '.')
            {
                $G[$r][$c] = '.';
                $G[$_r][$c] = 'v';
                $moved = true;
            }
        }
    }
    if (!$moved) break;
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
