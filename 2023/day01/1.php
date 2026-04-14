<?php

memory_reset_peak_usage();
$start_time = microtime(true);

$_fp = fopen( $argv[1] ?? "1.input", "r");

$part1 = $part2 = 0;

while (!feof($_fp))
{
    $line = trim(fgets($_fp));
    foreach ([&$part1, &$part2] as &$part)
    {
        if (preg_match('/(?:(\d).*)?(\d)/', $line, $m))
        {
            if ($m[1] == "") $m[1] = $m[2];
            $part += ((int)($m[1]) * 10) + (int)$m[2];
        }
        // fix line for part2...
        $line = preg_replace
        (
            ["/one/","/two/","/three/","/four/","/five/","/six/","/seven/","/eight/","/nine/"],
            ["o1e","t2o","t3e","f4r","f5e","s6x","s7n","e8t","n9e"],
            $line
        );
    }
    unset($part);
}
fclose($_fp);

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
