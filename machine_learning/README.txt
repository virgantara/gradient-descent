STRUKTUR

machine_learning_modular/
├── assets/
│   └── app.css
├── contents/
│   ├── home-content.php
│   ├── linear-regression-single-content.php
│   └── linear-regression-multiple-content.php
├── includes/
│   ├── header.php
│   ├── navbar.php
│   └── footer.php
├── index.php
├── linear-regression-single.php
├── linear-regression-multiple.php
└── train.php

POLA HALAMAN BARU

<?php
$pageTitle = 'Judul Halaman';
$pageClass = 'page-custom';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/navbar.php';
require __DIR__ . '/contents/custom-content.php';
require __DIR__ . '/includes/footer.php';

Navbar membaca nama file aktif dari $_SERVER['SCRIPT_NAME'].
Untuk menambah submenu Machine Learning, cukup tambahkan nama file dan link di includes/navbar.php.
