#!/usr/bin/env php
<?php
date_default_timezone_set('Europe/Kyiv');

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$shortOptions = "t:p::r::";
$longOptions = ["target:", "projects::", "restore::"];

$options = getopt($shortOptions, $longOptions);

$targetEnv = $options['target'] ?? ($options['t'] ?? '');
$targetServices = $options['projects'] ?? ($options['p'] ?? null);
$restore = $options['restore'] ?? ($options['r'] ?? false);

$config = require(__DIR__ . '/config/params.php');

$service = new App\EnvManager($config);
$service->run($targetEnv, $targetServices, $restore);

