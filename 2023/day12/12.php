<?php

memory_reset_peak_usage();
$start_time = microtime(true);

const DEBUG = false;

$lines = file_get_contents($argv[1] ?? "12.input");
$lines = explode("\n", trim($lines));

function move(string $S, array $G, &$cache = [])
{
    $cache_key = md5("$S:".implode(",",$G));
    if (isset($cache[$cache_key]))
    {
        if (DEBUG) echo "HIT: {$cache_key} = {$cache[$cache_key]}\n";
        return $cache[$cache_key];
    }

    // no more springs valid only if no more groups...
    if ($S == "") return (int)(count($G) == 0);

    // no more groups valid only if no damaged springs left...
    if (count($G) == 0) return (int)!str_contains($S, "#");

    $result = 0;

    // if operational, we can skip it and move on...
    if (str_contains(".?", $S[0]))
        $result += move(substr($S, 1), $G, $cache);

    // if damaged...
    if (str_contains("#?", $S[0]))
        // and enough springs left for the group...
        if (strlen($S) >= $G[0])
            // and the next group-length of springs are not operational (composed of #? chars)...
            if (!str_contains(substr($S, 0, $G[0]), "."))
                // and the spring after the group is not damaged...
                if (($S[$G[0]] ?? ".") != "#")
                    // move past the group and the trailing operational spring...
                    $result += move(substr($S, $G[0] + 1), array_slice($G, 1), $cache);

    return $cache[$cache_key] = $result;
}

$part1 = $part2 = 0;

foreach ([1, 2] as $part) foreach ($lines as $line)
{
    [$springs, $groups] = explode(" ", $line);
    $groups = explode(',', $groups);

    if ($part == 2)
    {
        $springs = implode("?", array_fill(0,5, $springs));
        $groups = array_merge(...array_fill(0, 5, $groups));
    }

    $result = move($springs, $groups);

    if ($part == 1) $part1 += $result; else $part2 += $result;
}

echo "part 1: {$part1}\n";
echo "part 2: {$part2}\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
