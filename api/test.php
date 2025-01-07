<?php
require __DIR__ . '/vendor/autoload.php';

use PHPExperts\ConsolePainter\ConsolePainter;

$p = new ConsolePainter();

echo $p->bold()->green("ERROR: ")->lightCyan('PHP_VERSION')->text(" is not set in ")->lightPurple('.env')->text(" or environment variable\n");
