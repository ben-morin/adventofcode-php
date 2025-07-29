adventofcode-php
=
My solutions to adventofcode.com puzzles in php.

running
-
Run with `php` and default input file, `1.input`:
```
[~/adventofcode-php/2015/day01]% php 1.php
part 1: 280
part 2: 1797
Execution time: 0.0013 seconds
Peak memory: 0.3964 MiB
```
Run with `php` and alternate input file, e.g. `1.example`:
```
[~/adventofcode-php/2015/day01]% php 1.php 1.example
part 1: 3
part 2: 1
Execution time: 0.0011 seconds
Peak memory: 0.3935 MiB
```
Run with `docker`:
```
[~/adventofcode-php/2015/day01]% docker run --rm -it -v "$PWD":/usr/src/myapp -w /usr/src/myapp php:latest php 1.php 
part 1: 280
part 2: 1797
Execution time: 0.0011 seconds
Peak memory: 0.3895 MiB
```
Each solution should run and display part 1 and part 2 solutions as well as execution time and memory usage.

Disable xdebug and enable`enter code here` opcache
-
You can get faster performance when running some solutions if you disable the `xdebug` extension (if you have it installed).  Note that the official php docker image doesn't have debug enabled:
```
[~/adventofcode-php/2015/day20]% php 20.php
part 1: 831600
part 2: 884520
Execution time: 19.8338 seconds
Peak memory: 0.3979 MiB
```
```
[~/adventofcode-php/2015/day20]% XDEBUG_MODE=off php 20.php
part 1: 831600
part 2: 884520
Execution time: 6.9901 seconds
Peak memory: 0.3939 MiB
```
Additionally you can enable the `opcache` extension and optionally enable `opcache.jit` to further tweak performance for some solutions.
```
[~/adventofcode-php/2015/day20]% XDEBUG_MODE=off php -d opcache.enable_cli=1 -d opcache.jit=on -d opcache.jit_buffer_size=128M 20.php
part 1: 831600
part 2: 884520
Execution time: 1.4617 seconds
Peak memory: 0.4099 MiB
```
```
[~/adventofcode-php/2015/day20]% docker run --rm -it -v "$PWD":/usr/src/myapp -w /usr/src/myapp php:latest php -d opcache.enable_cli=1 -d opcache.jit=on -d opcache.jit_buffer_size=128M 20.php
part 1: 831600
part 2: 884520
Execution time: 1.5146 seconds
Peak memory: 0.3911 MiB
```
links
-
Advent of Code: https://adventofcode.com 
github: [ben-morin/adventofcode-php](https://github.com/ben-morin/adventofcode-php)

