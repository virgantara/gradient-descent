<?php

$currentPage = basename($_SERVER['SCRIPT_NAME'] ?? '');

$machineLearningPages = [
    'linear-regression-single.php',
    'linear-regression-multiple.php',
    'neural-network.php',
    'decision-tree.php',
    'random-forest.php',
    'svm.php',
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

                    <a
                        href="neural-network.php"
                        class="<?= $currentPage === 'neural-network.php' ? 'active' : '' ?>"
                        <?= $currentPage === 'neural-network.php' ? 'aria-current="page"' : '' ?>
                    >
                        Neural Network dari Nol
                    </a>

                    <a
                        href="decision-tree.php"
                        class="<?= $currentPage === 'decision-tree.php' ? 'active' : '' ?>"
                        <?= $currentPage === 'decision-tree.php' ? 'aria-current="page"' : '' ?>
                    >
                        Decision Tree
                    </a>

                    <a
                        href="random-forest.php"
                        class="<?= $currentPage === 'random-forest.php' ? 'active' : '' ?>"
                        <?= $currentPage === 'random-forest.php' ? 'aria-current="page"' : '' ?>
                    >
                        Random Forest
                    </a>

                    <a
                        href="svm.php"
                        class="<?= $currentPage === 'svm.php' ? 'active' : '' ?>"
                        <?= $currentPage === 'svm.php' ? 'aria-current="page"' : '' ?>
                    >
                        Support Vector Machine (SVM)
                    </a>
                </div>
            </div>
        </nav>
    </div>
</header>