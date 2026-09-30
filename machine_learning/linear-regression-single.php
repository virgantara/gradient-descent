<?php

require_once __DIR__ . '/train.php';

$initialM = isset($_GET['m']) && is_numeric($_GET['m']) ? (float) $_GET['m'] : 0.0;
$initialC = isset($_GET['c']) && is_numeric($_GET['c']) ? (float) $_GET['c'] : 0.0;
$lr = isset($_GET['lr']) && is_numeric($_GET['lr']) ? (float) $_GET['lr'] : 0.01;
$epoch = isset($_GET['epoch']) ? max(1, min(10000, (int) $_GET['epoch'])) : 1000;

$points = [
    [0.5, 1.5],
    [2.5, 2.0],
    [3.0, 3.0],
];

$startTime = microtime(true);
$model = train($points, $lr, $epoch, $initialM, $initialC);
$executionTime = microtime(true) - $startTime;

$errorMessage = $model['error'] ?? null;
$states = $model['states'] ?? [];
$finalState = !empty($states) ? $states[count($states) - 1] : null;

$pageTitle = 'Regresi Linier Single Variabel';
$pageClass = 'page-linear-regression-single';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/navbar.php';
require __DIR__ . '/contents/linear-regression-single-content.php';
require __DIR__ . '/includes/footer.php';