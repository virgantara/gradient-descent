<?php

$currentPage = basename($_SERVER['SCRIPT_NAME'] ?? '');

$methodPages = [
    'biseksi.php' => 'Biseksi',
    'regula-falsi.php' => 'Regula Falsi',
    'secant.php' => 'Secant',
    'newton-raphson.php' => 'Newton-Raphson',
    'fixed-point.php' => 'Fixed Point',
];

?>
<header class="site-header">
    <div class="site-header-inner">
        <a class="site-brand" href="biseksi.php">
            <span class="site-brand-title">Metode Akar Persamaan</span>
            <span class="site-brand-subtitle">Visualisasi Metode Numerik</span>
        </a>

        <nav class="method-nav" aria-label="Navigasi metode akar persamaan">
            <?php foreach ($methodPages as $file => $label): ?>
                <?php $isActive = $currentPage === $file; ?>
                <a
                    href="<?= htmlspecialchars($file, ENT_QUOTES, 'UTF-8') ?>"
                    class="<?= $isActive ? 'active' : '' ?>"
                    <?= $isActive ? 'aria-current="page"' : '' ?>
                >
                    <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>
</header>