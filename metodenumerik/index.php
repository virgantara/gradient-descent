<?php

function f($x)
{
    return $x ** 2 - 2;
}

function hitungBiseksi($a, $b, $step = 10, $batasSelisih = 1e-10)
{
    $hasil = [];
    $cSebelumnya = null;
    $fa = f($a);
    $fb = f($b);

    if ($a >= $b) {
        return [
            'success' => false,
            'message' => 'Nilai a harus lebih kecil dari b.',
            'iterations' => [],
        ];
    }

    if (abs($fa) < $batasSelisih) {
        return [
            'success' => true,
            'message' => null,
            'iterations' => [[
                'iterasi' => 0,
                'a' => $a,
                'b' => $b,
                'c' => $a,
                'fa' => $fa,
                'fb' => $fb,
                'fc' => $fa,
                'error_interval' => 0,
                'error_c' => null,
            ]],
        ];
    }

    if (abs($fb) < $batasSelisih) {
        return [
            'success' => true,
            'message' => null,
            'iterations' => [[
                'iterasi' => 0,
                'a' => $a,
                'b' => $b,
                'c' => $b,
                'fa' => $fa,
                'fb' => $fb,
                'fc' => $fb,
                'error_interval' => 0,
                'error_c' => null,
            ]],
        ];
    }

    if ($fa * $fb > 0) {
        return [
            'success' => false,
            'message' => sprintf(
                'Interval [%.10g, %.10g] tidak memenuhi syarat awal biseksi karena f(a) = %.10g dan f(b) = %.10g bertanda sama. Interval mungkin tetap mengandung akar, tetapi metode biseksi klasik membutuhkan f(a) × f(b) < 0.',
                $a,
                $b,
                $fa,
                $fb
            ),
            'iterations' => [],
        ];
    }

    for ($i = 1; $i <= $step; $i++) {
        $c = ($a + $b) / 2;
        $fa = f($a);
        $fb = f($b);
        $fc = f($c);
        $errorInterval = abs($b - $a) / 2;
        $errorC = $cSebelumnya === null ? null : abs($c - $cSebelumnya);

        $hasil[] = [
            'iterasi' => $i,
            'a' => $a,
            'b' => $b,
            'c' => $c,
            'fa' => $fa,
            'fb' => $fb,
            'fc' => $fc,
            'error_interval' => $errorInterval,
            'error_c' => $errorC,
        ];

        if (abs($fc) < $batasSelisih) {
            break;
        }

        if ($fa * $fc < 0) {
            $b = $c;
        } else {
            $a = $c;
        }

        $cSebelumnya = $c;
    }

    return [
        'success' => true,
        'message' => null,
        'iterations' => $hasil,
    ];
}

$a = isset($_GET['a']) && is_numeric($_GET['a']) ? (float) $_GET['a'] : 0;
$b = isset($_GET['b']) && is_numeric($_GET['b']) ? (float) $_GET['b'] : 2;
$step = isset($_GET['step']) ? max(1, min(100, (int) $_GET['step'])) : 10;

$hasilBiseksi = hitungBiseksi($a, $b, $step);
$isValid = $hasilBiseksi['success'];
$errorMessage = $hasilBiseksi['message'];
$hasil = $hasilBiseksi['iterations'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Animasi Teknik-Teknik Metode Numerik</title>
    <link rel="stylesheet" href="assets/metode-akar.css">
</head>

<body class="page-biseksi">

<?php require __DIR__ . '/partials/navbar.php'; ?>

<div class="container">
    <h1>Animasi Teknik-Teknik Metode Numerik</h1>
</div>   
</body>
</html>