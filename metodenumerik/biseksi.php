<?php

function f($x)
{
    return $x ** 2 - 2;
}

function hitungBiseksi($a, $b, $step = 10, $batas_selisih = 1e-10)
{
    $hasil = [];

    $fa = f($a);
    $fb = f($b);

    if ($fa * $fb > 0) {
        return [
            'error' => 'Interval tidak mengapit akar'
        ];
    }

    for ($i = 1; $i <= $step; $i++) {

        $c = ($a + $b) / 2;

        $fa = f($a);
        $fb = f($b);
        $fc = f($c);

        // Simpan kondisi SEBELUM interval diperbarui
        $hasil[] = [
            'iterasi' => $i,
            'a' => $a,
            'b' => $b,
            'c' => $c,
            'fa' => $fa,
            'fb' => $fb,
            'fc' => $fc,
        ];

        if (abs($fc) < $batas_selisih) {
            break;
        }

        if ($fa * $fc < 0) {
            $b = $c;
        } else {
            $a = $c;
        }
    }

    return $hasil;
}

$a = 1;
$b = 2;

$hasil = hitungBiseksi($a, $b, 10);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Animasi Metode Biseksi</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8fafc;
            margin: 0;
            padding: 40px;
            color: #1e293b;
        }

        .container {
            max-width: 1000px;
            margin: auto;
        }

        h1 {
            margin-bottom: 5px;
        }

        .subtitle {
            color: #64748b;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: 14px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,.08);
            margin-bottom: 25px;
        }

        .iteration-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .number-line-wrapper {
            position: relative;
            height: 150px;
            margin: 20px 25px;
        }

        .number-line {
            position: absolute;
            top: 70px;
            left: 0;
            right: 0;
            height: 5px;
            background: #cbd5e1;
            border-radius: 5px;
        }

        .active-interval {
            position: absolute;
            top: 70px;
            height: 5px;
            background: #2563eb;
            transition:
                left 0.8s ease,
                width 0.8s ease;
        }

        .point {
            position: absolute;
            top: 55px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            transform: translateX(-50%);
            transition: left 0.8s ease;
        }

        .point-a {
            background: #ef4444;
        }

        .point-b {
            background: #22c55e;
        }

        .point-c {
            background: #f59e0b;
            width: 24px;
            height: 24px;
            top: 53px;
            box-shadow: 0 0 0 6px rgba(245,158,11,.2);
        }

        .label {
            position: absolute;
            transform: translateX(-50%);
            font-weight: bold;
            transition: left 0.8s ease;
            white-space: nowrap;
        }

        .label-a {
            top: 90px;
            color: #ef4444;
        }

        .label-b {
            top: 90px;
            color: #16a34a;
        }

        .label-c {
            top: 20px;
            color: #d97706;
        }

        .axis-label {
            position: absolute;
            top: 95px;
            transform: translateX(-50%);
            color: #64748b;
            font-size: 13px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .info {
            background: #f1f5f9;
            padding: 15px;
            border-radius: 10px;
        }

        .info .name {
            font-size: 13px;
            color: #64748b;
        }

        .info .value {
            margin-top: 5px;
            font-size: 18px;
            font-weight: bold;
        }

        .decision {
            margin-top: 20px;
            padding: 15px;
            border-radius: 10px;
            background: #eff6ff;
            color: #1d4ed8;
            font-weight: bold;
        }

        .controls {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        button {
            border: none;
            padding: 11px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            font-weight: bold;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #334155;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border-bottom: 1px solid #e2e8f0;
            padding: 10px;
            text-align: right;
        }

        th:first-child,
        td:first-child {
            text-align: center;
        }

        th {
            background: #f8fafc;
        }

        .active-row {
            background: #fef3c7;
        }

        @media(max-width: 700px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Metode Biseksi</h1>

    <div class="subtitle">
        Fungsi:
        <strong>
            f(x) = x² - 2
        </strong>
    </div>

    <div class="card">

        <div
            class="iteration-title"
            id="iterationTitle"
        >
            Iterasi 1
        </div>

        <div class="number-line-wrapper">

            <div class="number-line"></div>

            <div
                id="activeInterval"
                class="active-interval"
            ></div>

            <div
                id="pointA"
                class="point point-a"
            ></div>

            <div
                id="pointB"
                class="point point-b"
            ></div>

            <div
                id="pointC"
                class="point point-c"
            ></div>

            <div
                id="labelA"
                class="label label-a"
            ></div>

            <div
                id="labelB"
                class="label label-b"
            ></div>

            <div
                id="labelC"
                class="label label-c"
            ></div>

            <div
                class="axis-label"
                style="left:0%"
            >
                <?= $a ?>
            </div>

            <div
                class="axis-label"
                style="left:100%"
            >
                <?= $b ?>
            </div>

        </div>

        <div class="info-grid">

            <div class="info">
                <div class="name">a</div>
                <div
                    class="value"
                    id="valueA"
                ></div>
            </div>

            <div class="info">
                <div class="name">c = (a + b) / 2</div>
                <div
                    class="value"
                    id="valueC"
                ></div>
            </div>

            <div class="info">
                <div class="name">b</div>
                <div
                    class="value"
                    id="valueB"
                ></div>
            </div>

        </div>

        <div
            class="decision"
            id="decision"
        ></div>

        <div class="controls">

            <button
                class="btn-secondary"
                onclick="previousStep()"
            >
                ← Sebelumnya
            </button>

            <button
                class="btn-primary"
                onclick="nextStep()"
            >
                Berikutnya →
            </button>

            <button
                class="btn-primary"
                onclick="playAnimation()"
            >
                ▶ Play
            </button>

            <button
                class="btn-secondary"
                onclick="resetAnimation()"
            >
                Reset
            </button>

        </div>

    </div>


    <div class="card">

        <h2>Tabel Iterasi</h2>

        <table>

            <thead>
            <tr>
                <th>Iterasi</th>
                <th>a</th>
                <th>b</th>
                <th>c</th>
                <th>f(a)</th>
                <th>f(b)</th>
                <th>f(c)</th>
            </tr>
            </thead>

            <tbody>

            <?php foreach ($hasil as $row): ?>

                <tr id="row-<?= $row['iterasi'] ?>">

                    <td>
                        <?= $row['iterasi'] ?>
                    </td>

                    <td>
                        <?= round($row['a'], 8) ?>
                    </td>

                    <td>
                        <?= round($row['b'], 8) ?>
                    </td>

                    <td>
                        <?= round($row['c'], 8) ?>
                    </td>

                    <td>
                        <?= round($row['fa'], 8) ?>
                    </td>

                    <td>
                        <?= round($row['fb'], 8) ?>
                    </td>

                    <td>
                        <?= round($row['fc'], 8) ?>
                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>


<script>

const data = <?= json_encode($hasil) ?>;

const minX = <?= $a ?>;
const maxX = <?= $b ?>;

let currentStep = 0;
let timer = null;

function getPosition(x) {

    return ((x - minX) / (maxX - minX)) * 100;

}


function showStep(index) {

    if (index < 0 || index >= data.length) {
        return;
    }

    currentStep = index;

    const item = data[index];

    const posA = getPosition(item.a);
    const posB = getPosition(item.b);
    const posC = getPosition(item.c);


    document.getElementById('pointA').style.left =
        posA + '%';

    document.getElementById('pointB').style.left =
        posB + '%';

    document.getElementById('pointC').style.left =
        posC + '%';

    const labelA =
        document.getElementById('labelA');

    const labelB =
        document.getElementById('labelB');

    const labelC =
        document.getElementById('labelC');


    labelA.style.left =
        posA + '%';

    labelB.style.left =
        posB + '%';

    labelC.style.left =
        posC + '%';


    labelA.innerHTML =
        'a = ' + format(item.a);

    labelB.innerHTML =
        'b = ' + format(item.b);

    labelC.innerHTML =
        'c = ' + format(item.c);


    document.getElementById(
        'activeInterval'
    ).style.left = posA + '%';

    document.getElementById(
        'activeInterval'
    ).style.width =
        (posB - posA) + '%';


    document.getElementById(
        'iterationTitle'
    ).innerHTML =
        'Iterasi ' + item.iterasi;


    document.getElementById(
        'valueA'
    ).innerHTML =
        format(item.a) +
        '<br><small>f(a) = ' +
        format(item.fa) +
        '</small>';


    document.getElementById(
        'valueB'
    ).innerHTML =
        format(item.b) +
        '<br><small>f(b) = ' +
        format(item.fb) +
        '</small>';


    document.getElementById(
        'valueC'
    ).innerHTML =
        format(item.c) +
        '<br><small>f(c) = ' +
        format(item.fc) +
        '</small>';

    let decision = '';

    if (Math.abs(item.fc) < 1e-10) {

        decision =
            'f(c) mendekati 0. Akar ditemukan pada c = ' +
            format(item.c);

    }

    else if (item.fa * item.fc < 0) {

        decision =
            'f(a) × f(c) < 0, sehingga akar berada di [a, c]. ' +
            'Maka b digeser ke posisi c.';

    }

    else {

        decision =
            'f(c) × f(b) < 0, sehingga akar berada di [c, b]. ' +
            'Maka a digeser ke posisi c.';

    }


    document.getElementById(
        'decision'
    ).innerHTML = decision;

    document
        .querySelectorAll('tbody tr')
        .forEach(row => {

            row.classList.remove(
                'active-row'
            );

        });


    const activeRow =
        document.getElementById(
            'row-' + item.iterasi
        );


    if (activeRow) {

        activeRow.classList.add(
            'active-row'
        );

    }

}


function nextStep() {

    if (
        currentStep <
        data.length - 1
    ) {

        showStep(
            currentStep + 1
        );

    }

}


function previousStep() {

    if (currentStep > 0) {

        showStep(
            currentStep - 1
        );

    }

}


function playAnimation() {

    clearInterval(timer);

    timer = setInterval(() => {

        if (
            currentStep >=
            data.length - 1
        ) {

            clearInterval(timer);

            return;

        }

        nextStep();

    }, 1500);

}


function resetAnimation() {

    clearInterval(timer);

    currentStep = 0;

    showStep(0);

}


function format(value) {

    return Number(value)
        .toFixed(8)
        .replace(
            /0+$/,
            ''
        )
        .replace(
            /\.$/,
            ''
        );

}


showStep(0);

</script>

</body>
</html>