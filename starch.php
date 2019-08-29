#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

$app = new Symfony\Component\Console\Application('Starch');
$app->add(new App\Commands\CreateProject);
$app->run();
