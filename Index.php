<?php

try {
    require_once __DIR__ . '/vendor/autoload.php';
} catch (Exception $e) {
    throw new Exception($e->getMessage());
}

$app = Brideo\PullRequestSlack\App\App::create();
$app->run();
