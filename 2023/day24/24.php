<?php

memory_reset_peak_usage();
$start_time = microtime(true);

const DEBUG = false;

$_fp = fopen( $filename = ($argv[1] ?? "24.input"), "r");

$P = [];
while (!feof($_fp) && $line = trim(fgets($_fp)))
{
    [$l, $r] = explode(" @ ", $line);
    $l = array_map("gmp_init", explode(", ", $l));
    $r = array_map("gmp_init", explode(", ", $r));
    $P[] = [...$l, ...$r];
}
fclose($_fp);

// https://en.wikipedia.org/wiki/Line–line_intersection

function intersect($p1, $p2): array|false
{
    // initial position, velocity and second point of each line...
    [$x1, $y1, $vx1, $vy1] = $p1;
    [$x2, $y2] = [$x1 + $vx1, $y1 + $vy1];
    [$x3, $y3, $vx3, $vy3] = $p2;
    [$x4, $y4] = [$x3 + $vx3, $y3 + $vy3];
    // calculate the intersection point...
    $d = ($x1 - $x2) * ($y3 - $y4) - ($y1 - $y2) * ($x3 - $x4);
    if ($d == 0) return false; // ... parallel
    $x = (($x1 * $y2 - $y1 * $x2) * ($x3 - $x4) - ($x1 - $x2) * ($x3 * $y4 - $y3 * $x4)) / $d;
    $y = (($x1 * $y2 - $y1 * $x2) * ($y3 - $y4) - ($y1 - $y2) * ($x3 * $y4 - $y3 * $x4)) / $d;
    // check if the intersection is valid...
    if (gmp_sign($x - $x1) != gmp_sign($vx1)) return false;
    if (gmp_sign($x - $x3) != gmp_sign($vx3)) return false;
    // return the intersection...
    return [$x, $y];
}

enum Projection: int { case X = 0; case Y = 1; case Z = 2; }

function project($p, Projection $projection = Projection::Z, $da = 0, $db = 0): array
{
    // removing the projected dimension and velocity...
    return match($projection)
    {
        Projection::X => [$p[1], $p[2], $p[4] + $da, $p[5] + $db],
        Projection::Y => [$p[0], $p[2], $p[3] + $da, $p[5] + $db],
        Projection::Z => [$p[0], $p[1], $p[3] + $da, $p[4] + $db],
    };
}

function f(Projection $projection, $range = 300, $matches = 3): array|false
{
    global $P;

    // try [-range to range] velocities for each dimension...
    for ($da = -$range; $da <= $range; $da++) for ($db = -$range; $db <= $range; $db++)
    {
        $result = [];
        for ($i = 0; $i < count($P); $i++) for ($j = $i + 1; $j < count($P); $j++)
        {
            // project points and make relative to proposed rock's velocity...
            $p1 = project($P[$i], $projection, $da, $db);
            $p2 = project($P[$j], $projection, $da, $db);
            if (!$intersect = intersect($p1, $p2)) continue;
            // record intersection in set...
            $key = join(",", $intersect);
            $result[$key] ??= 0; $result[$key]++;
            // break if we've found minimum matches...
            if (array_sum($result) >= $matches) break 2;
        }
        // if we've found minimum matches and there is only 1 intersection...
        if (array_sum($result) >= $matches)
            if (count($result = array_unique(array_keys($result))) == 1)
                return [...explode(",", $result[0]), -$da, -$db];
    }
    assert(false, "No result for projection {$projection->name}.");
}

$part1 = $part2 = 0;
for ($c = count($P), $i = 0; $i < $c; $i++) for ($j = $i+1; $j < $c; $j++)
{
    if (!$intersect = intersect(project($P[$i]), project($P[$j]))) continue;
    [$x, $y] = $intersect;
    // check if the intersection is within window...
    // if (7 <= $x && $x <= 27 && 7 <= $y && $y <= 27) $part1++;
    if (2e14 <= $x && $x <= 4e14 && 2e14 <= $y && $y <= 4e14) $part1++;
}

// adjust these higher if asserts fail...
const RANGE = 300, MATCHES = 3;

[$y1, $z1, $vy1, $vz1] = f(Projection::X, RANGE, MATCHES); // YZ
[$x1, $z2, $vx1, $vz2] = f(Projection::Y, RANGE, MATCHES); // XZ
[$x2, $y2, $vx2, $vy2] = f(Projection::Z, RANGE, MATCHES); // XY

assert([$x1, $y1, $z1, $vx1, $vy1, $vz1] == [$x2, $y2, $z2, $vx2, $vy2, $vz2], "Projections differ.");
$part2 = $x1 + $y1 + $z1;

echo "part 1: {$part1}\n";
echo "part 2: {$part2}", DEBUG ? " -> [$x1, $y1, $z1 @ $vx1, $vy1, $vz1]\n" : "\n";

echo "Execution time: ".round(microtime(true) - $start_time, 4)." seconds\n";
echo "   Peak memory: ".round(memory_get_peak_usage()/pow(2, 20), 4), " MiB\n\n";
