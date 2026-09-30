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
        <h2 class="section-title">1) Intuisi Metode Biseksi</h2>

        <div class="section-desc">
            Sebelum menghitung iterasi, lihat dulu ide dasarnya:
            cari interval yang nilai fungsinya berlainan tanda, ambil titik tengah,
            lalu pertahankan hanya setengah interval yang masih mengandung perubahan tanda.
        </div>

        <div class="canvas-wrap">
            <canvas id="bisectionIntuitionCanvas"></canvas>
        </div>

        <div class="step-box">
            <div class="step-title" id="intuitionStepTitle">
                <?= $isValid ? 'Langkah 1 — Cari interval yang mengapit akar' : 'Intuisi belum dapat dijalankan' ?>
            </div>

            <div class="step-text" id="intuitionStepText">
                <?= $isValid
                    ? 'Akar dicari pada interval yang membuat f(a) dan f(b) memiliki tanda berbeda.'
                    : 'Masukkan interval yang memenuhi f(a) × f(b) < 0 terlebih dahulu.' ?>
            </div>

            <div class="formula-box" id="intuitionFormula">
                <?= $isValid ? 'f(a) × f(b) < 0' : 'f(a) × f(b) harus < 0' ?>
            </div>
        </div>

        <div class="controls">
            <button class="btn-secondary" onclick="previousIntuitionStep()" <?= $isValid ? '' : 'disabled' ?>>
                ← Sebelumnya
            </button>

            <button class="btn-primary" onclick="nextIntuitionStep()" <?= $isValid ? '' : 'disabled' ?>>
                Berikutnya →
            </button>

            <button class="btn-primary" onclick="playIntuition()" <?= $isValid ? '' : 'disabled' ?>>
                ▶ Play Intuisi
            </button>

            <button class="btn-secondary" onclick="resetIntuition()" <?= $isValid ? '' : 'disabled' ?>>
                ↺ Reset
            </button>
        </div>
    </div>

    <div class="card">
        <h2 class="section-title">2) Animasi Perhitungan Biseksi</h2>

        <div class="section-desc">
            Setelah ide dasarnya terlihat, jalankan iterasi untuk melihat bagaimana
            posisi a, b, dan c berubah menuju akar.
        </div>

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

let intuitionStep = 0;
let intuitionTimer = null;

const intuitionData = hasData() ? data[0] : null;

const intuitionSteps = [
    {
        title: 'Langkah 1 — Cari perubahan tanda',
        text: 'Metode biseksi dimulai dari interval [a, b] dengan f(a) dan f(b) berlainan tanda. Secara geometris, kurva berada pada sisi berbeda dari sumbu-x di kedua ujung interval.',
        formula: 'f(a) × f(b) < 0'
    },
    {
        title: 'Langkah 2 — Ambil titik tengah interval',
        text: 'Karena kita belum tahu posisi akar secara tepat, interval dibagi menjadi dua bagian sama panjang. Titik pembaginya disebut c.',
        formula: 'c = (a + b) / 2'
    },
    {
        title: 'Langkah 3 — Evaluasi f(c)',
        text: 'Nilai c belum tentu merupakan akar. Karena itu kita hitung f(c) dan melihat tandanya terhadap f(a) dan f(b).',
        formula: 'hitung f(c)'
    },
    {
        title: 'Langkah 4 — Pilih setengah interval yang masih mengapit akar',
        text: 'Jika f(a) dan f(c) berlainan tanda, akar berada pada [a, c]. Jika tidak, akar berada pada [c, b]. Setengah interval lainnya dibuang.',
        formula: 'f(a)f(c) < 0 → [a, c]   |   selain itu → [c, b]'
    },
    {
        title: 'Langkah 5 — Ulangi proses pada interval baru',
        text: 'Interval yang dipertahankan menjadi interval pada iterasi berikutnya. Kita kembali mengambil titik tengah baru dan mengulangi pemeriksaan tanda.',
        formula: 'interval baru → titik tengah baru → cek tanda lagi'
    },
    {
        title: 'Langkah 6 — Ketidakpastian terus dibelah dua',
        text: 'Setiap iterasi membagi lebar interval menjadi dua. Karena itu batas error teoritis metode biseksi mengecil secara teratur.',
        formula: 'error ≤ |b - a| / 2'
    }
];

function getIntuitionBounds()
{
    const span = Math.max(Math.abs(maxX - minX), 1);
    const xPadding = span * 0.15;
    const xMin = minX - xPadding;
    const xMax = maxX + xPadding;
    const samples = 180;
    const yValues = [0];

    for (let i = 0; i <= samples; i++) {
        const x = xMin + (i / samples) * (xMax - xMin);
        yValues.push(x * x - 2);
    }

    let yMin = Math.min(...yValues);
    let yMax = Math.max(...yValues);
    let ySpan = yMax - yMin;

    if (ySpan < 1) {
        ySpan = 1;
    }

    yMin -= ySpan * 0.12;
    yMax += ySpan * 0.12;

    return { xMin, xMax, yMin, yMax };
}

function drawIntuitionPoint(ctx, x, y, color, label)
{
    ctx.beginPath();
    ctx.arc(x, y, 6, 0, Math.PI * 2);
    ctx.fillStyle = color;
    ctx.fill();

    if (label) {
        ctx.fillStyle = color;
        ctx.font = 'bold 13px Arial';
        ctx.fillText(label, x + 9, y - 9);
    }
}

function drawIntuitionDashedLine(ctx, x1, y1, x2, y2, color)
{
    ctx.save();
    ctx.setLineDash([6, 5]);
    ctx.strokeStyle = color;
    ctx.lineWidth = 1.2;
    ctx.beginPath();
    ctx.moveTo(x1, y1);
    ctx.lineTo(x2, y2);
    ctx.stroke();
    ctx.restore();
}

function drawBisectionIntuition()
{
    const canvas = document.getElementById('bisectionIntuitionCanvas');

    if (!canvas) {
        return;
    }

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
        ctx.fillText('Intuisi belum tersedia karena interval tidak memenuhi syarat biseksi.', width / 2, height / 2);
        return;
    }

    const item = intuitionData;
    const bounds = getIntuitionBounds();
    const margin = { left: 60, right: 30, top: 30, bottom: 50 };
    const plotWidth = width - margin.left - margin.right;
    const plotHeight = height - margin.top - margin.bottom;

    function mapXIntuition(x)
    {
        return margin.left + ((x - bounds.xMin) / (bounds.xMax - bounds.xMin)) * plotWidth;
    }

    function mapYIntuition(y)
    {
        return margin.top + ((bounds.yMax - y) / (bounds.yMax - bounds.yMin)) * plotHeight;
    }

    const xAxisY = mapYIntuition(0);

    ctx.strokeStyle = '#e2e8f0';
    ctx.lineWidth = 1;

    for (let i = 0; i <= 6; i++) {
        const x = margin.left + (i / 6) * plotWidth;
        ctx.beginPath();
        ctx.moveTo(x, margin.top);
        ctx.lineTo(x, height - margin.bottom);
        ctx.stroke();
    }

    for (let i = 0; i <= 5; i++) {
        const y = margin.top + (i / 5) * plotHeight;
        ctx.beginPath();
        ctx.moveTo(margin.left, y);
        ctx.lineTo(width - margin.right, y);
        ctx.stroke();
    }

    ctx.strokeStyle = '#475569';
    ctx.lineWidth = 1.5;

    if (xAxisY >= margin.top && xAxisY <= height - margin.bottom) {
        ctx.beginPath();
        ctx.moveTo(margin.left, xAxisY);
        ctx.lineTo(width - margin.right, xAxisY);
        ctx.stroke();
    }

    const zeroX = mapXIntuition(0);

    if (zeroX >= margin.left && zeroX <= width - margin.right) {
        ctx.beginPath();
        ctx.moveTo(zeroX, margin.top);
        ctx.lineTo(zeroX, height - margin.bottom);
        ctx.stroke();
    }

    ctx.beginPath();

    let curveStarted = false;

    for (let i = 0; i <= 400; i++) {
        const x = bounds.xMin + (i / 400) * (bounds.xMax - bounds.xMin);
        const y = x * x - 2;
        const px = mapXIntuition(x);
        const py = mapYIntuition(y);

        if (!curveStarted) {
            ctx.moveTo(px, py);
            curveStarted = true;
        } else {
            ctx.lineTo(px, py);
        }
    }

    ctx.strokeStyle = '#7c3aed';
    ctx.lineWidth = 2.5;
    ctx.stroke();

    const ax = mapXIntuition(item.a);
    const bx = mapXIntuition(item.b);
    const cx = mapXIntuition(item.c);
    const ay = mapYIntuition(item.fa);
    const by = mapYIntuition(item.fb);
    const cy = mapYIntuition(item.fc);

    const keepLeft = item.fa * item.fc < 0;
    const keepStart = keepLeft ? ax : cx;
    const keepEnd = keepLeft ? cx : bx;
    const discardStart = keepLeft ? cx : ax;
    const discardEnd = keepLeft ? bx : cx;

    if (intuitionStep >= 0) {
        ctx.fillStyle = 'rgba(37, 99, 235, .08)';
        ctx.fillRect(Math.min(ax, bx), margin.top, Math.abs(bx - ax), plotHeight);

        drawIntuitionDashedLine(ctx, ax, ay, ax, xAxisY, '#ef4444');
        drawIntuitionDashedLine(ctx, bx, by, bx, xAxisY, '#16a34a');

        drawIntuitionPoint(ctx, ax, ay, '#ef4444', 'A');
        drawIntuitionPoint(ctx, bx, by, '#16a34a', 'B');

        ctx.fillStyle = '#ef4444';
        ctx.font = '13px Arial';
        ctx.fillText('a = ' + format(item.a), ax - 18, xAxisY + 20);

        ctx.fillStyle = '#16a34a';
        ctx.fillText('b = ' + format(item.b), bx - 18, xAxisY + 20);
    }

    if (intuitionStep >= 1) {
        ctx.strokeStyle = '#f59e0b';
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.moveTo(cx, margin.top);
        ctx.lineTo(cx, height - margin.bottom);
        ctx.stroke();

        drawIntuitionPoint(ctx, cx, xAxisY, '#f59e0b', 'c');

        ctx.fillStyle = '#d97706';
        ctx.font = 'bold 13px Arial';
        ctx.fillText('c = ' + format(item.c), cx + 8, xAxisY + 22);
    }

    if (intuitionStep >= 2) {
        drawIntuitionDashedLine(ctx, cx, cy, cx, xAxisY, '#f59e0b');
        drawIntuitionPoint(ctx, cx, cy, '#f59e0b', 'f(c)');

        ctx.fillStyle = '#d97706';
        ctx.font = '13px Arial';
        ctx.fillText('f(c) = ' + format(item.fc), cx + 10, cy + 18);
    }

    if (intuitionStep >= 3) {
        ctx.fillStyle = 'rgba(34, 197, 94, .14)';
        ctx.fillRect(Math.min(keepStart, keepEnd), margin.top, Math.abs(keepEnd - keepStart), plotHeight);

        ctx.fillStyle = 'rgba(239, 68, 68, .08)';
        ctx.fillRect(Math.min(discardStart, discardEnd), margin.top, Math.abs(discardEnd - discardStart), plotHeight);

        ctx.strokeStyle = '#16a34a';
        ctx.lineWidth = 5;
        ctx.beginPath();
        ctx.moveTo(keepStart, xAxisY);
        ctx.lineTo(keepEnd, xAxisY);
        ctx.stroke();

        ctx.fillStyle = '#15803d';
        ctx.font = 'bold 13px Arial';
        ctx.fillText(
            keepLeft ? 'interval dipertahankan [a, c]' : 'interval dipertahankan [c, b]',
            (keepStart + keepEnd) / 2 - 75,
            xAxisY - 18
        );
    }

    if (intuitionStep >= 4 && data.length > 1) {
        const next = data[1];
        const nextA = mapXIntuition(next.a);
        const nextB = mapXIntuition(next.b);
        const nextC = mapXIntuition(next.c);

        ctx.save();
        ctx.setLineDash([8, 5]);
        ctx.strokeStyle = '#2563eb';
        ctx.lineWidth = 3;
        ctx.beginPath();
        ctx.moveTo(nextA, xAxisY + 36);
        ctx.lineTo(nextB, xAxisY + 36);
        ctx.stroke();
        ctx.restore();

        ctx.fillStyle = '#2563eb';
        ctx.font = 'bold 13px Arial';
        ctx.fillText('interval iterasi berikutnya', (nextA + nextB) / 2 - 72, xAxisY + 56);

        ctx.beginPath();
        ctx.arc(nextC, xAxisY + 36, 5, 0, Math.PI * 2);
        ctx.fillStyle = '#f59e0b';
        ctx.fill();
    }

    if (intuitionStep >= 5) {
        ctx.fillStyle = '#334155';
        ctx.font = 'bold 14px Arial';
        ctx.fillText(
            'Lebar awal = ' + format(Math.abs(item.b - item.a)) +
            ' → setelah dibelah = ' + format(Math.abs(item.b - item.a) / 2),
            margin.left + 10,
            margin.top + 20
        );
    }

    ctx.fillStyle = '#64748b';
    ctx.font = '12px Arial';
    ctx.fillText('f(x) = x² - 2', width - margin.right - 105, margin.top + 16);

    document.getElementById('intuitionStepTitle').innerHTML = intuitionSteps[intuitionStep].title;
    document.getElementById('intuitionStepText').innerHTML = intuitionSteps[intuitionStep].text;
    document.getElementById('intuitionFormula').innerHTML = intuitionSteps[intuitionStep].formula;
}

function nextIntuitionStep()
{
    if (!hasData()) {
        return;
    }

    if (intuitionStep < intuitionSteps.length - 1) {
        intuitionStep++;
        drawBisectionIntuition();
    }
}

function previousIntuitionStep()
{
    if (!hasData()) {
        return;
    }

    if (intuitionStep > 0) {
        intuitionStep--;
        drawBisectionIntuition();
    }
}

function playIntuition()
{
    if (!hasData()) {
        return;
    }

    clearInterval(intuitionTimer);

    intuitionTimer = setInterval(() => {
        if (intuitionStep >= intuitionSteps.length - 1) {
            clearInterval(intuitionTimer);
            intuitionTimer = null;
            return;
        }

        nextIntuitionStep();
    }, 1800);
}

function resetIntuition()
{
    if (!hasData()) {
        return;
    }

    if (intuitionTimer !== null) {
        clearInterval(intuitionTimer);
        intuitionTimer = null;
    }

    intuitionStep = 0;
    drawBisectionIntuition();
}

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

window.addEventListener('resize', () => {
    drawBisectionIntuition();
    drawErrorChart();
});

drawBisectionIntuition();

if (hasData()) {
    showStep(0);
} else {
    drawErrorChart();
}
</script>

</body>
</html>