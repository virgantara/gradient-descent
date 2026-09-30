<?php

function f($x)
{
    return $x ** 2 - 2;
}

function hitungRegulaFalsi($a, $b, $step = 10, $toleransi = 1e-10)
{
    $hasil = [];
    $cSebelumnya = null;

    $fa = f($a);
    $fb = f($b);

    if ($fa * $fb > 0) {
        return [
            'error' => 'Interval tidak mengapit akar.'
        ];
    }

    for ($i = 1; $i <= $step; $i++) {
        $fa = f($a);
        $fb = f($b);

        if (abs($fb - $fa) < 1e-15) {
            break;
        }

        // Rumus regula falsi
        $c = (($a * $fb) - ($b * $fa)) / ($fb - $fa);
        $fc = f($c);

        $hasil[] = [
            'iterasi' => $i,
            'a' => $a,
            'b' => $b,
            'c' => $c,
            'fa' => $fa,
            'fb' => $fb,
            'fc' => $fc,
            'delta_c' => $cSebelumnya === null ? null : abs($c - $cSebelumnya),
            'lebar_interval' => abs($b - $a),
            'keputusan' => ($fa * $fc < 0)
                ? 'Akar berada di [a, c], sehingga b digeser ke c.'
                : 'Akar berada di [c, b], sehingga a digeser ke c.',
        ];

        if (abs($fc) < $toleransi) {
            break;
        }

        if ($fa * $fc < 0) {
            $b = $c;
        } else {
            $a = $c;
        }

        $cSebelumnya = $c;
    }

    return $hasil;
}

$aAwal = isset($_GET['a']) ? (float) $_GET['a'] : 1;
$bAwal = isset($_GET['b']) ? (float) $_GET['b'] : 2;
$batasIterasi = isset($_GET['iterasi']) ? max(1, (int) $_GET['iterasi']) : 10;

$hasil = hitungRegulaFalsi($aAwal, $bAwal, $batasIterasi);

if (isset($hasil['error'])) {
    die($hasil['error']);
}

// Untuk animasi intuisi awal
$faAwal = f($aAwal);
$fbAwal = f($bAwal);
$cAwal  = (($aAwal * $fbAwal) - ($bAwal * $faAwal)) / ($fbAwal - $faAwal);
$fcAwal = f($cAwal);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Animasi Regula Falsi</title>
    <link rel="stylesheet" href="assets/metode-akar.css">
</head>
<body class="page-regula-falsi">

<?php require __DIR__ . '/partials/navbar.php'; ?>
<div class="container">

    <h1>Animasi Metode Regula Falsi</h1>
    <div class="subtitle">
        Contoh fungsi: <strong>f(x) = x² - 2</strong>, interval awal <strong>[<?= $aAwal ?>, <?= $bAwal ?>]</strong>
    </div>
    <div class="card">
    <form method="GET" class="input-form">
        <div class="input-group">
            <label for="inputA">Nilai a</label>
            <input
                type="number"
                step="any"
                id="inputA"
                name="a"
                value="<?= htmlspecialchars($aAwal) ?>"
                required
            >
        </div>

        <div class="input-group">
            <label for="inputB">Nilai b</label>
            <input
                type="number"
                step="any"
                id="inputB"
                name="b"
                value="<?= htmlspecialchars($bAwal) ?>"
                required
            >
        </div>

        <div class="input-group">
            <label for="inputIterasi">Batas Iterasi</label>
            <input
                type="number"
                min="1"
                max="1000"
                id="inputIterasi"
                name="iterasi"
                value="<?= $batasIterasi ?>"
                required
            >
        </div>

        <div class="input-action">
            <button type="submit" class="btn-primary">
                Hitung & Tampilkan Animasi
            </button>
        </div>
    </form>
</div>
    <!-- ===================================================== -->
    <!-- 1. INTUISI RUMUS -->
    <!-- ===================================================== -->
    <div class="card">
        <h2 class="section-title">1) Animasi Intuisi Munculnya Rumus Regula Falsi</h2>
        <div class="section-desc">
            Bagian ini menjelaskan kenapa rumus regula falsi muncul dari garis secan yang menghubungkan dua titik pada kurva.
        </div>

        <div class="canvas-wrap">
            <canvas id="intuitionCanvas"></canvas>
        </div>

        <div class="step-box">
            <div class="step-title" id="intuitionStepTitle"></div>
            <div class="step-text" id="intuitionStepText"></div>
            <div class="formula-box" id="intuitionFormula"></div>
        </div>

        <div class="controls">
            <button class="btn-secondary" onclick="prevIntuition()">← Sebelumnya</button>
            <button class="btn-primary" onclick="nextIntuition()">Berikutnya →</button>
            <button class="btn-primary" onclick="playIntuition()">▶ Play</button>
            <button class="btn-secondary" onclick="resetIntuition()">Reset</button>
        </div>
    </div>

    <!-- ===================================================== -->
    <!-- 2. HITUNGAN ITERASI -->
    <!-- ===================================================== -->
    <div class="card">
        <h2 class="section-title">2) Animasi Perhitungan Regula Falsi</h2>
        <div class="section-desc">
            Setelah rumus dipahami, berikut animasi bagaimana nilai <strong>c</strong> dihitung pada setiap iterasi, lalu dipakai untuk memperbarui interval.
        </div>

        <div class="canvas-wrap">
            <canvas id="iterationCanvas"></canvas>
        </div>

        <div class="step-box">
            <div class="step-title" id="iterTitle">Iterasi 1</div>
            <div class="step-text" id="iterText"></div>
        </div>

        <div class="stats-grid">
            <div class="stat">
                <div class="stat-name">a dan f(a)</div>
                <div class="stat-value" id="valA"></div>
            </div>
            <div class="stat">
                <div class="stat-name">b dan f(b)</div>
                <div class="stat-value" id="valB"></div>
            </div>
            <div class="stat">
                <div class="stat-name">c dan f(c)</div>
                <div class="stat-value" id="valC"></div>
            </div>
            <div class="stat">
                <div class="stat-name">|Δc|</div>
                <div class="stat-value" id="valDeltaC"></div>
            </div>
        </div>

        <div class="decision" id="decisionBox"></div>

        <div class="controls">
            <button class="btn-secondary" onclick="prevIter()">← Sebelumnya</button>
            <button class="btn-primary" onclick="nextIter()">Berikutnya →</button>
            <button class="btn-primary" onclick="playIter()">▶ Play</button>
            <button class="btn-secondary" onclick="pauseIter()">⏸ Pause</button>
            <button class="btn-secondary" onclick="resetIter()">Reset</button>
        </div>

        <div class="table-wrap">
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
                    <th>|Δc|</th>
                    <th>Lebar Interval</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($hasil as $row): ?>
                    <tr id="row-<?= $row['iterasi'] ?>">
                        <td><?= $row['iterasi'] ?></td>
                        <td><?= round($row['a'], 10) ?></td>
                        <td><?= round($row['b'], 10) ?></td>
                        <td><?= round($row['c'], 10) ?></td>
                        <td><?= round($row['fa'], 10) ?></td>
                        <td><?= round($row['fb'], 10) ?></td>
                        <td><?= round($row['fc'], 10) ?></td>
                        <td><?= $row['delta_c'] === null ? '-' : round($row['delta_c'], 10) ?></td>
                        <td><?= round($row['lebar_interval'], 10) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="note">
            <strong>Catatan penting:</strong> pada regula falsi, nilai <strong>c</strong> bukan titik tengah seperti biseksi.
            Nilai <strong>c</strong> adalah titik potong sumbu‑x dari <strong>garis secan</strong> yang menghubungkan
            <strong>(a, f(a))</strong> dan <strong>(b, f(b))</strong>.
            Karena itu kita tetap harus mengecek <strong>f(c)</strong> pada fungsi asli.
        </div>
    </div>

</div>

<script>
const rfData = <?= json_encode($hasil, JSON_NUMERIC_CHECK) ?>;

const initial = {
    a: <?= json_encode($aAwal, JSON_NUMERIC_CHECK) ?>,
    b: <?= json_encode($bAwal, JSON_NUMERIC_CHECK) ?>,
    fa: <?= json_encode($faAwal, JSON_NUMERIC_CHECK) ?>,
    fb: <?= json_encode($fbAwal, JSON_NUMERIC_CHECK) ?>,
    c: <?= json_encode($cAwal, JSON_NUMERIC_CHECK) ?>,
    fc: <?= json_encode($fcAwal, JSON_NUMERIC_CHECK) ?>
};

function f(x) {
    return x * x - 2;
}

function formatNum(value, digits = 8) {
    if (value === null || value === undefined) return '-';
    return Number(value)
        .toFixed(digits)
        .replace(/0+$/, '')
        .replace(/\.$/, '');
}

function drawPoint(ctx, x, y, color, label = null) {
    ctx.beginPath();
    ctx.arc(x, y, 6, 0, Math.PI * 2);
    ctx.fillStyle = color;
    ctx.fill();

    if (label) {
        ctx.fillStyle = color;
        ctx.font = 'bold 14px Arial';
        ctx.fillText(label, x + 8, y - 8);
    }
}

function setupCanvas(canvasId) {
    const canvas = document.getElementById(canvasId);
    const rect = canvas.parentElement.getBoundingClientRect();
    const dpr = window.devicePixelRatio || 1;

    canvas.width = rect.width * dpr;
    canvas.height = rect.height * dpr;

    const ctx = canvas.getContext('2d');
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

    return { canvas, ctx, width: rect.width, height: rect.height };
}

const graphDomain = {
    xMin: 0.5,
    xMax: 2.2,
    yMin: -1.7,
    yMax: 2.8
};

const margin = { top: 28, right: 26, bottom: 42, left: 52 };

function mapX(x, width) {
    const plotW = width - margin.left - margin.right;
    return margin.left + ((x - graphDomain.xMin) / (graphDomain.xMax - graphDomain.xMin)) * plotW;
}

function mapY(y, height) {
    const plotH = height - margin.top - margin.bottom;
    return margin.top + ((graphDomain.yMax - y) / (graphDomain.yMax - graphDomain.yMin)) * plotH;
}

function drawBaseGraph(ctx, width, height) {
    ctx.clearRect(0, 0, width, height);

    // background
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, width, height);

    // grid
    ctx.strokeStyle = '#e2e8f0';
    ctx.lineWidth = 1;

    for (let i = 0; i <= 6; i++) {
        const x = margin.left + i * ((width - margin.left - margin.right) / 6);
        ctx.beginPath();
        ctx.moveTo(x, margin.top);
        ctx.lineTo(x, height - margin.bottom);
        ctx.stroke();
    }

    for (let i = 0; i <= 5; i++) {
        const y = margin.top + i * ((height - margin.top - margin.bottom) / 5);
        ctx.beginPath();
        ctx.moveTo(margin.left, y);
        ctx.lineTo(width - margin.right, y);
        ctx.stroke();
    }

    // axes
    ctx.strokeStyle = '#475569';
    ctx.lineWidth = 1.5;

    const xAxisY = mapY(0, height);
    const yAxisX = mapX(0, width); // mungkin di luar domain, tidak masalah

    // x axis
    ctx.beginPath();
    ctx.moveTo(margin.left, xAxisY);
    ctx.lineTo(width - margin.right, xAxisY);
    ctx.stroke();

    // y axis kalau masuk area
    if (yAxisX >= margin.left && yAxisX <= width - margin.right) {
        ctx.beginPath();
        ctx.moveTo(yAxisX, margin.top);
        ctx.lineTo(yAxisX, height - margin.bottom);
        ctx.stroke();
    }

    // curve
    ctx.beginPath();
    let first = true;
    for (let px = 0; px <= 600; px++) {
        const x = graphDomain.xMin + (px / 600) * (graphDomain.xMax - graphDomain.xMin);
        const y = f(x);
        const cx = mapX(x, width);
        const cy = mapY(y, height);

        if (first) {
            ctx.moveTo(cx, cy);
            first = false;
        } else {
            ctx.lineTo(cx, cy);
        }
    }
    ctx.strokeStyle = '#7c3aed';
    ctx.lineWidth = 2.5;
    ctx.stroke();

    // axis labels
    ctx.fillStyle = '#475569';
    ctx.font = '12px Arial';
    ctx.fillText('x', width - margin.right + 6, xAxisY - 6);
    ctx.fillText('y', margin.left - 14, margin.top - 8);

    ctx.fillStyle = '#64748b';
    ctx.fillText('f(x) = x² - 2', width - 140, margin.top + 16);
}

function drawDashedVertical(ctx, x, y1, y2, color = '#94a3b8') {
    ctx.save();
    ctx.setLineDash([6, 4]);
    ctx.strokeStyle = color;
    ctx.lineWidth = 1.2;
    ctx.beginPath();
    ctx.moveTo(x, y1);
    ctx.lineTo(x, y2);
    ctx.stroke();
    ctx.restore();
}

// ==========================================================
// 1) INTUITION ANIMATION
// ==========================================================
const intuitionSteps = [
    {
        title: 'Langkah 1 — Ambil dua titik yang mengapit akar',
        text: 'Pilih dua titik A(a, f(a)) dan B(b, f(b)) dengan tanda f(a) dan f(b) berlawanan. Karena tandanya berbeda, akar fungsi asli berada di antara a dan b.',
        formula: 'A = (a, f(a)),  B = (b, f(b))'
    },
    {
        title: 'Langkah 2 — Hubungkan A dan B dengan garis lurus',
        text: 'Kurva fungsi asli sekarang kita dekati dengan garis lurus yang menghubungkan A dan B. Garis ini disebut garis secan.',
        formula: 'Gunakan garis secan melalui A dan B'
    },
    {
        title: 'Langkah 3 — Ambil titik potong garis secan dengan sumbu-x',
        text: 'Titik potong garis secan dengan sumbu-x diberi nama c. Nilai c inilah yang dipakai sebagai pendekatan akar pada metode regula falsi.',
        formula: 'c = titik potong garis secan dengan y = 0'
    },
    {
        title: 'Langkah 4 — Tulis persamaan garis secan',
        text: 'Karena garis melalui A dan B, kita bisa memakai persamaan garis bentuk titik-kemiringan.',
        formula: 'y - f(a) = ((f(b) - f(a)) / (b - a)) (x - a)'
    },
    {
        title: 'Langkah 5 — Karena c berada di sumbu-x, set y = 0 dan x = c',
        text: 'Substitusikan y = 0 karena titik c terletak pada sumbu-x.',
        formula: '0 - f(a) = ((f(b) - f(a)) / (b - a)) (c - a)'
    },
    {
        title: 'Langkah 6 — Susun ulang untuk mendapatkan c',
        text: 'Dari persamaan tadi, kita isolasi c agar bisa langsung dihitung.',
        formula: 'c = a - f(a) (b - a) / (f(b) - f(a))'
    },
    {
        title: 'Langkah 7 — Bentuk ekuivalen yang umum dipakai',
        text: 'Bentuk berikut ekuivalen dan sangat sering dipakai di implementasi program.',
        formula: 'c = (a f(b) - b f(a)) / (f(b) - f(a))'
    }
];

let intuitionIndex = 0;
let intuitionTimer = null;

function drawIntuition() {
    const { ctx, width, height } = setupCanvas('intuitionCanvas');
    drawBaseGraph(ctx, width, height);

    const ax = mapX(initial.a, width);
    const ay = mapY(initial.fa, height);

    const bx = mapX(initial.b, width);
    const by = mapY(initial.fb, height);

    const cx = mapX(initial.c, width);
    const cAxisY = mapY(0, height);
    const cCurveY = mapY(initial.fc, height);

    // show A and B
    drawPoint(ctx, ax, ay, '#ef4444', 'A');
    drawPoint(ctx, bx, by, '#16a34a', 'B');

    ctx.fillStyle = '#334155';
    ctx.font = '13px Arial';
    ctx.fillText(`a = ${formatNum(initial.a)}`, ax - 18, cAxisY + 20);
    ctx.fillText(`b = ${formatNum(initial.b)}`, bx - 18, cAxisY + 20);

    drawDashedVertical(ctx, ax, ay, cAxisY, '#cbd5e1');
    drawDashedVertical(ctx, bx, by, cAxisY, '#cbd5e1');

    if (intuitionIndex >= 1) {
        ctx.strokeStyle = '#2563eb';
        ctx.lineWidth = 2.5;
        ctx.beginPath();
        ctx.moveTo(ax, ay);
        ctx.lineTo(bx, by);
        ctx.stroke();

        ctx.fillStyle = '#2563eb';
        ctx.fillText('garis secan', (ax + bx) / 2 - 28, (ay + by) / 2 - 16);
    }

    if (intuitionIndex >= 2) {
        drawPoint(ctx, cx, cAxisY, '#f59e0b', 'c');
        drawDashedVertical(ctx, cx, cAxisY, cCurveY, '#f59e0b');

        ctx.fillStyle = '#f59e0b';
        ctx.fillText(`c = ${formatNum(initial.c)}`, cx - 16, cAxisY + 20);
    }

    if (intuitionIndex >= 2) {
        drawPoint(ctx, cx, cCurveY, '#8b5cf6', 'P');
        ctx.fillStyle = '#8b5cf6';
        ctx.fillText(`P(c, f(c))`, cx + 10, cCurveY + 16);
    }

    // highlight formula region
    document.getElementById('intuitionStepTitle').innerHTML = intuitionSteps[intuitionIndex].title;
    document.getElementById('intuitionStepText').innerHTML = intuitionSteps[intuitionIndex].text;
    document.getElementById('intuitionFormula').innerHTML = intuitionSteps[intuitionIndex].formula;
}

function nextIntuition() {
    if (intuitionIndex < intuitionSteps.length - 1) {
        intuitionIndex++;
        drawIntuition();
    }
}

function prevIntuition() {
    if (intuitionIndex > 0) {
        intuitionIndex--;
        drawIntuition();
    }
}

function playIntuition() {
    clearInterval(intuitionTimer);
    intuitionTimer = setInterval(() => {
        if (intuitionIndex >= intuitionSteps.length - 1) {
            clearInterval(intuitionTimer);
            return;
        }
        nextIntuition();
    }, 1800);
}

function resetIntuition() {
    clearInterval(intuitionTimer);
    intuitionIndex = 0;
    drawIntuition();
}

// ==========================================================
// 2) ITERATION ANIMATION
// ==========================================================
let iterIndex = 0;
let iterTimer = null;

function drawIteration(index = 0) {
    if (index < 0 || index >= rfData.length) return;
    iterIndex = index;

    const item = rfData[index];

    const { ctx, width, height } = setupCanvas('iterationCanvas');
    drawBaseGraph(ctx, width, height);

    const ax = mapX(item.a, width);
    const ay = mapY(item.fa, height);

    const bx = mapX(item.b, width);
    const by = mapY(item.fb, height);

    const cx = mapX(item.c, width);
    const cAxisY = mapY(0, height);
    const cCurveY = mapY(item.fc, height);

    // secant line
    ctx.strokeStyle = '#2563eb';
    ctx.lineWidth = 2.5;
    ctx.beginPath();
    ctx.moveTo(ax, ay);
    ctx.lineTo(bx, by);
    ctx.stroke();

    // points A, B on curve
    drawPoint(ctx, ax, ay, '#ef4444', 'A');
    drawPoint(ctx, bx, by, '#16a34a', 'B');

    // c on x-axis (root of secant)
    drawPoint(ctx, cx, cAxisY, '#f59e0b', 'c');

    // actual point on curve at x=c
    drawPoint(ctx, cx, cCurveY, '#8b5cf6', 'f(c)');

    drawDashedVertical(ctx, cx, cAxisY, cCurveY, '#f59e0b');
    drawDashedVertical(ctx, ax, ay, mapY(0, height), '#cbd5e1');
    drawDashedVertical(ctx, bx, by, mapY(0, height), '#cbd5e1');

    // x labels
    ctx.fillStyle = '#334155';
    ctx.font = '13px Arial';
    ctx.fillText(`a=${formatNum(item.a)}`, ax - 18, mapY(0, height) + 18);
    ctx.fillText(`b=${formatNum(item.b)}`, bx - 18, mapY(0, height) + 18);
    ctx.fillText(`c=${formatNum(item.c)}`, cx - 18, mapY(0, height) + 34);

    // explanation
    document.getElementById('iterTitle').innerHTML = `Iterasi ${item.iterasi}`;
    document.getElementById('iterText').innerHTML =
        `Hitung titik potong garis secan dengan sumbu-x menggunakan rumus regula falsi, lalu evaluasi nilai fungsi asli di x = c.`;

    document.getElementById('valA').innerHTML =
        `${formatNum(item.a)}<br><small>f(a) = ${formatNum(item.fa)}</small>`;

    document.getElementById('valB').innerHTML =
        `${formatNum(item.b)}<br><small>f(b) = ${formatNum(item.fb)}</small>`;

    document.getElementById('valC').innerHTML =
        `${formatNum(item.c)}<br><small>f(c) = ${formatNum(item.fc)}</small>`;

    document.getElementById('valDeltaC').innerHTML =
        item.delta_c === null ? 'Belum ada' : formatNum(item.delta_c, 10);

    const rumusText =
        `c = (a·f(b) - b·f(a)) / (f(b) - f(a)) = (${formatNum(item.a)} × ${formatNum(item.fb)} - ${formatNum(item.b)} × ${formatNum(item.fa)}) / (${formatNum(item.fb)} - ${formatNum(item.fa)}) = ${formatNum(item.c)}`;

    document.getElementById('decisionBox').innerHTML =
        `<strong>Rumus:</strong> ${rumusText}<br><br>` +
        `<strong>Keputusan:</strong> ${item.keputusan}`;

    // highlight table row
    document.querySelectorAll('tbody tr').forEach(row => row.classList.remove('active-row'));
    const row = document.getElementById('row-' + item.iterasi);
    if (row) row.classList.add('active-row');
}

function nextIter() {
    if (iterIndex < rfData.length - 1) {
        drawIteration(iterIndex + 1);
    }
}

function prevIter() {
    if (iterIndex > 0) {
        drawIteration(iterIndex - 1);
    }
}

function playIter() {
    clearInterval(iterTimer);
    iterTimer = setInterval(() => {
        if (iterIndex >= rfData.length - 1) {
            clearInterval(iterTimer);
            return;
        }
        nextIter();
    }, 1800);
}

function pauseIter() {
    clearInterval(iterTimer);
}

function resetIter() {
    clearInterval(iterTimer);
    drawIteration(0);
}

window.addEventListener('resize', () => {
    drawIntuition();
    drawIteration(iterIndex);
});

// first render
drawIntuition();
drawIteration(0);
</script>
</body>
</html>