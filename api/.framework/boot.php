<?php

use Dotenv\Dotenv;
use Minicli\App;
use PHPExperts\ConsolePainter\ConsolePainter;

$app = new App([
    'app_path' => [
        __DIR__ . '/src/Command',
    ],
    'theme' => '\Dracula',
    'debug' => false,
]);

$p = new ConsolePainter();

// Load environment variables from the .env file
$ensureDotEnv = function () {
    if (!file_exists(__DIR__ . '/../.env')) {
        if (file_exists('.env.dist')) {
            system('cp .env.dist .env');
        }

        file_put_contents('.env', '');
    }
};

$makeAppKey = function () {
    $appKey = env('APP_KEY');
    if (empty($appKey)) {
        $appKey = hash('sha256', bin2hex(random_bytes(8)));
        file_put_contents('.env', "\nAPP_KEY=$appKey\n", FILE_APPEND);
        putenv("APP_KEY=$appKey");
        $_ENV['APP_KEY'] = $appKey;
    }
};

$ensureDotEnv();

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$makeAppKey();

// Validate the environment variables
$validateEnv = function () use ($p) {
    $missing = false;
    foreach (['APP_KEY'] as $e) {
        if (empty($_ENV[$e])) {
            echo $p->bold()->red("ERROR: ")->lightCyan($e)->text(" is not set in ")->lightPurple('.env')->text(" or environment variable\n");
            $missing = true;
        }
    }

    if ($missing === true) {
        exit(1);
    }
};

$validateEnv();
