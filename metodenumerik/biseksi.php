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
    <title>Animasi Metode Biseksi</title>
    <link rel="stylesheet" href="assets/metode-akar.css">
</head>

<body class="page-biseksi">

<?php require __DIR__ . '/partials/navbar.php'; ?>

<div class="container">
    <h1>Animasi Metode Biseksi</h1>
    <form method="GET" class="input-form">
    <div class="input-group">
        <label for="a">Batas kiri (a)</label>
        <input
            type="number"
            step="any"
            id="a"
            name="a"
            value="<?= htmlspecialchars((string) $a, ENT_QUOTES, 'UTF-8') ?>"
            required
        >
    </div>

    <div class="input-group">
        <label for="b">Batas kanan (b)</label>
        <input
            type="number"
            step="any"
            id="b"
            name="b"
            value="<?= htmlspecialchars((string) $b, ENT_QUOTES, 'UTF-8') ?>"
            required
        >
    </div>

    <div class="input-group">
        <label for="step">Batas Iterasi</label>
        <input
            type="number"
            id="step"
            name="step"
            min="1"
            max="100"
            value="<?= $step ?>"
            required
        >
    </div>

    <div class="input-action">
        <button type="submit" class="btn-primary">
            Hitung
        </button>
    </div>
</form>
    <div class="subtitle">
    Fungsi:
    <strong>f(x) = x² - 2</strong>

    dengan interval awal
    <strong>[<?= $a ?>, <?= $b ?>]</strong>

    dan maksimum
    <strong><?= $step ?> iterasi</strong>
</div>

    <?php if (!$isValid): ?>
        <div class="alert alert-error">
            <strong>Interval belum memenuhi syarat biseksi.</strong><br>
            <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="card">

        <div class="iteration-title" id="iterationTitle">
            <?= $isValid ? 'Iterasi 1' : 'Animasi tidak tersedia' ?>
        </div>

        <div class="number-line-wrapper">
            <div class="number-line"></div>
            <div id="activeInterval" class="active-interval"></div>

            <div id="pointA" class="point point-a"></div>
            <div id="pointB" class="point point-b"></div>
            <div id="pointC" class="point point-c"></div>

            <div id="labelA" class="label label-a"></div>
            <div id="labelB" class="label label-b"></div>
            <div id="labelC" class="label label-c"></div>

            <div class="axis-label" style="left: 0%"><?= $a ?></div>
            <div class="axis-label" style="left: 100%"><?= $b ?></div>
        </div>

        <div class="info-grid">
            <div class="info">
                <div class="name">Batas kiri (a)</div>
                <div class="value" id="valueA">-</div>
            </div>

            <div class="info">
                <div class="name">Titik tengah c = (a+b)/2</div>
                <div class="value" id="valueC">-</div>
            </div>

            <div class="info">
                <div class="name">Batas kanan (b)</div>
                <div class="value" id="valueB">-</div>
            </div>
        </div>

        <div class="error-grid">
            <div class="error-box">
                <div class="error-name">Error interval <strong>|b-a| / 2</strong></div>
                <div class="error-value" id="errorInterval">-</div>
            </div>

            <div class="error-box">
                <div class="error-name">Perubahan pendekatan <strong>|cᵢ - cᵢ₋₁|</strong></div>
                <div class="error-value" id="errorC">-</div>
            </div>
        </div>

        <div class="decision" id="decision">
            <?= $isValid ? '' : 'Pilih interval dengan f(a) dan f(b) berlainan tanda untuk menjalankan animasi.' ?>
        </div>

        <div class="controls">
            <button class="btn-secondary" onclick="previousStep()" <?= $isValid ? '' : 'disabled' ?>>← Sebelumnya</button>
            <button class="btn-primary" onclick="nextStep()" <?= $isValid ? '' : 'disabled' ?>>Berikutnya →</button>
            <button class="btn-primary" onclick="playAnimation()" <?= $isValid ? '' : 'disabled' ?>>▶ Play</button>
            <button class="btn-secondary" onclick="pauseAnimation()" <?= $isValid ? '' : 'disabled' ?>>⏸ Pause</button>
            <button class="btn-secondary" onclick="resetAnimation()" <?= $isValid ? '' : 'disabled' ?>>↺ Reset</button>
        </div>
    </div>

    <div class="card">
        <h2>Kurva Konvergensi Error</h2>

        <div class="chart-description">
            Grafik menunjukkan bagaimana error semakin kecil pada setiap iterasi metode biseksi.
        </div>

        <div class="chart-container">
            <canvas id="errorChart"></canvas>
        </div>

        <div class="legend">
            <div class="legend-item">
                <div class="legend-line legend-interval"></div>
                Error interval: |b-a| / 2
            </div>

            <div class="legend-item">
                <div class="legend-line legend-c"></div>
                Perubahan c: |cᵢ-cᵢ₋₁|
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Tabel Iterasi</h2>

        <div class="table-wrapper">
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
                    <th>Error Interval</th>
                    <th>|Δc|</th>
                </tr>
                </thead>

                <tbody>
                <?php if (empty($hasil)): ?>
                    <tr class="empty-row">
                        <td colspan="9">Tidak ada iterasi karena interval belum memenuhi syarat awal biseksi.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($hasil as $row): ?>
                        <tr id="row-<?= $row['iterasi'] ?>">
                            <td><?= $row['iterasi'] ?></td>
                            <td><?= round($row['a'], 8) ?></td>
                            <td><?= round($row['b'], 8) ?></td>
                            <td><?= round($row['c'], 8) ?></td>
                            <td><?= round($row['fa'], 8) ?></td>
                            <td><?= round($row['fb'], 8) ?></td>
                            <td><?= round($row['fc'], 8) ?></td>
                            <td><?= round($row['error_interval'], 10) ?></td>
                            <td><?= $row['error_c'] === null ? '-' : round($row['error_c'], 10) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
const data = <?= json_encode($hasil, JSON_NUMERIC_CHECK) ?>;
const isValid = <?= $isValid ? 'true' : 'false' ?>;
const minX = <?= json_encode($a, JSON_NUMERIC_CHECK) ?>;
const maxX = <?= json_encode($b, JSON_NUMERIC_CHECK) ?>;
let currentStep = 0;
let timer = null;

function hasData()
{
    return isValid && Array.isArray(data) && data.length > 0;
}

function getPosition(x)
{
    if (maxX === minX) {
        return 0;
    }

    return ((x - minX) / (maxX - minX)) * 100;
}

function format(value)
{
    if (value === null || value === undefined) {
        return '-';
    }

    return Number(value).toFixed(10).replace(/0+$/, '').replace(/\.$/, '');
}

function showStep(index)
{
    if (!hasData() || index < 0 || index >= data.length) {
        return;
    }

    currentStep = index;

    const item = data[index];
    const posA = getPosition(item.a);
    const posB = getPosition(item.b);
    const posC = getPosition(item.c);
    const pointA = document.getElementById('pointA');
    const pointB = document.getElementById('pointB');
    const pointC = document.getElementById('pointC');
    const labelA = document.getElementById('labelA');
    const labelB = document.getElementById('labelB');
    const labelC = document.getElementById('labelC');
    const activeInterval = document.getElementById('activeInterval');

    pointA.style.left = posA + '%';
    pointB.style.left = posB + '%';
    pointC.style.left = posC + '%';

    labelA.style.left = posA + '%';
    labelB.style.left = posB + '%';
    labelC.style.left = posC + '%';

    labelA.innerHTML = 'a = ' + format(item.a);
    labelB.innerHTML = 'b = ' + format(item.b);
    labelC.innerHTML = 'c = ' + format(item.c);

    activeInterval.style.left = posA + '%';
    activeInterval.style.width = (posB - posA) + '%';

    document.getElementById('iterationTitle').innerHTML = 'Iterasi ' + item.iterasi;
    document.getElementById('valueA').innerHTML = format(item.a) + '<br><small>f(a) = ' + format(item.fa) + '</small>';
    document.getElementById('valueB').innerHTML = format(item.b) + '<br><small>f(b) = ' + format(item.fb) + '</small>';
    document.getElementById('valueC').innerHTML = format(item.c) + '<br><small>f(c) = ' + format(item.fc) + '</small>';
    document.getElementById('errorInterval').innerHTML = format(item.error_interval);
    document.getElementById('errorC').innerHTML = item.error_c === null ? 'Belum tersedia' : format(item.error_c);

    let decision = '';

    if (Math.abs(item.fc) < 1e-10) {
        decision = 'f(c) sudah sangat dekat dengan 0. Akar ditemukan di sekitar c = ' + format(item.c);
    } else if (item.fa * item.fc < 0) {
        decision = 'f(a) × f(c) < 0 → akar berada pada interval [a, c]. Pada iterasi berikutnya, b digeser ke posisi c.';
    } else {
        decision = 'f(c) × f(b) < 0 → akar berada pada interval [c, b]. Pada iterasi berikutnya, a digeser ke posisi c.';
    }

    document.getElementById('decision').innerHTML = decision;

    document.querySelectorAll('tbody tr').forEach(row => row.classList.remove('active-row'));

    const activeRow = document.getElementById('row-' + item.iterasi);

    if (activeRow) {
        activeRow.classList.add('active-row');
    }

    drawErrorChart();
}

function nextStep()
{
    if (!hasData()) {
        return;
    }

    if (currentStep < data.length - 1) {
        showStep(currentStep + 1);
    }
}

function previousStep()
{
    if (!hasData()) {
        return;
    }

    if (currentStep > 0) {
        showStep(currentStep - 1);
    }
}

function playAnimation()
{
    if (!hasData()) {
        return;
    }

    clearInterval(timer);

    timer = setInterval(() => {
        if (currentStep >= data.length - 1) {
            clearInterval(timer);
            timer = null;
            return;
        }

        nextStep();
    }, 1500);
}

function pauseAnimation()
{
    if (timer !== null) {
        clearInterval(timer);
        timer = null;
    }
}

function resetAnimation()
{
    if (!hasData()) {
        return;
    }

    pauseAnimation();
    currentStep = 0;
    showStep(0);
}

function drawErrorChart()
{
    const canvas = document.getElementById('errorChart');
    const container = canvas.parentElement;
    const dpr = window.devicePixelRatio || 1;
    const width = container.clientWidth;
    const height = container.clientHeight;

    canvas.width = width * dpr;
    canvas.height = height * dpr;

    const ctx = canvas.getContext('2d');

    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    ctx.clearRect(0, 0, width, height);
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, width, height);

    if (!hasData()) {
        ctx.fillStyle = '#64748b';
        ctx.font = '14px Arial';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText('Grafik belum tersedia karena interval tidak memenuhi syarat biseksi.', width / 2, height / 2);
        return;
    }

    const visibleData = data.slice(0, currentStep + 1);
    const margin = { left: 70, right: 30, top: 25, bottom: 50 };
    const plotWidth = width - margin.left - margin.right;
    const plotHeight = height - margin.top - margin.bottom;
    const allErrors = [];

    data.forEach(item => {
        allErrors.push(Number(item.error_interval));

        if (item.error_c !== null) {
            allErrors.push(Number(item.error_c));
        }
    });

    const maxError = Math.max(...allErrors, 1e-12);

    function getX(index)
    {
        if (data.length === 1) {
            return margin.left + plotWidth / 2;
        }

        return margin.left + (index / (data.length - 1)) * plotWidth;
    }

    function getY(value)
    {
        return margin.top + (1 - value / maxError) * plotHeight;
    }

    const gridCount = 5;

    ctx.font = '12px Arial';
    ctx.textAlign = 'right';
    ctx.textBaseline = 'middle';

    for (let i = 0; i <= gridCount; i++) {
        const ratio = i / gridCount;
        const y = margin.top + ratio * plotHeight;
        const value = maxError * (1 - ratio);

        ctx.beginPath();
        ctx.strokeStyle = '#e2e8f0';
        ctx.lineWidth = 1;
        ctx.moveTo(margin.left, y);
        ctx.lineTo(width - margin.right, y);
        ctx.stroke();

        ctx.fillStyle = '#64748b';
        ctx.fillText(value.toFixed(4), margin.left - 10, y);
    }

    ctx.strokeStyle = '#64748b';
    ctx.lineWidth = 1.5;

    ctx.beginPath();
    ctx.moveTo(margin.left, margin.top);
    ctx.lineTo(margin.left, height - margin.bottom);
    ctx.stroke();

    ctx.beginPath();
    ctx.moveTo(margin.left, height - margin.bottom);
    ctx.lineTo(width - margin.right, height - margin.bottom);
    ctx.stroke();

    ctx.textAlign = 'center';
    ctx.textBaseline = 'top';

    data.forEach((item, index) => {
        const x = getX(index);

        ctx.fillStyle = '#64748b';
        ctx.fillText(item.iterasi, x, height - margin.bottom + 10);
    });

    ctx.fillStyle = '#334155';
    ctx.font = '13px Arial';
    ctx.textAlign = 'center';
    ctx.fillText('Iterasi', margin.left + plotWidth / 2, height - 15);

    ctx.save();
    ctx.translate(18, margin.top + plotHeight / 2);
    ctx.rotate(-Math.PI / 2);
    ctx.fillText('Error', 0, 0);
    ctx.restore();

    function drawSeries(key, color)
    {
        let started = false;

        ctx.beginPath();

        visibleData.forEach((item, index) => {
            const value = item[key];

            if (value === null || value === undefined) {
                return;
            }

            const x = getX(index);
            const y = getY(Number(value));

            if (!started) {
                ctx.moveTo(x, y);
                started = true;
            } else {
                ctx.lineTo(x, y);
            }
        });

        ctx.strokeStyle = color;
        ctx.lineWidth = 3;
        ctx.lineJoin = 'round';
        ctx.lineCap = 'round';
        ctx.stroke();

        visibleData.forEach((item, index) => {
            const value = item[key];

            if (value === null || value === undefined) {
                return;
            }

            const x = getX(index);
            const y = getY(Number(value));

            ctx.beginPath();
            ctx.arc(x, y, 5, 0, Math.PI * 2);
            ctx.fillStyle = color;
            ctx.fill();

            if (index === currentStep) {
                ctx.beginPath();
                ctx.arc(x, y, 9, 0, Math.PI * 2);
                ctx.strokeStyle = color;
                ctx.lineWidth = 2;
                ctx.stroke();
            }
        });
    }

    drawSeries('error_interval', '#2563eb');
    drawSeries('error_c', '#f59e0b');
}

window.addEventListener('resize', drawErrorChart);

if (hasData()) {
    showStep(0);
} else {
    drawErrorChart();
}
</script>

</body>
</html>