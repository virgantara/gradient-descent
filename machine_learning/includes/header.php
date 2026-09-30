<?php

$pageTitle = $pageTitle ?? 'Machine Learning';
$pageClass = $pageClass ?? '';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="assets/app.css">
</head>
<body class="<?= htmlspecialchars($pageClass, ENT_QUOTES, 'UTF-8') ?>">
