<?php

$currentPage = basename($_SERVER['SCRIPT_NAME'] ?? '');

$machineLearningPages = [
    'linear-regression-single.php',
    'linear-regression-multiple.php',
];

$isMachineLearningActive = in_array($currentPage, $machineLearningPages, true);

?>
<header class="site-header">
    <div class="site-header-inner">
        <a href="index.php" class="brand">
            <span class="brand-title">Numerical & ML Lab</span>
            <span class="brand-subtitle">Interactive Learning</span>
        </a>

        <nav class="navbar" aria-label="Navigasi utama">
            <a
                href="index.php"
                class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>"
                <?= $currentPage === 'index.php' ? 'aria-current="page"' : '' ?>
            >
                Home
            </a>

            <div class="nav-dropdown <?= $isMachineLearningActive ? 'active' : '' ?>">
                <button class="nav-dropdown-toggle" type="button" aria-haspopup="true">
                    Machine Learning
                    <span class="caret">▾</span>
                </button>

                <div class="nav-dropdown-menu">
                    <a
                        href="linear-regression-single.php"
                        class="<?= $currentPage === 'linear-regression-single.php' ? 'active' : '' ?>"
                        <?= $currentPage === 'linear-regression-single.php' ? 'aria-current="page"' : '' ?>
                    >
                        Regresi Linier Single Variabel
                    </a>

                    <a
                        href="linear-regression-multiple.php"
                        class="<?= $currentPage === 'linear-regression-multiple.php' ? 'active' : '' ?>"
                        <?= $currentPage === 'linear-regression-multiple.php' ? 'aria-current="page"' : '' ?>
                    >
                        Regresi Linier Multiple Variabel
                    </a>
                </div>
            </div>
        </nav>
    </div>
</header>
