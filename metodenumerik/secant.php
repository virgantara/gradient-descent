<?php

function f($x)
{
    return $x ** 2 - 2;
}

function hitungSecant($x0, $x1, $step = 10, $toleransi = 1e-10)
{
    $hasil = [];

    for ($i = 1; $i <= $step; $i++) {
        $fx0 = f($x0);
        $fx1 = f($x1);
        $penyebut = $fx1 - $fx0;

        if (abs($penyebut) < 1e-15) {
            return [
                'error' => 'Metode Secant berhenti karena f(x₁) - f(x₀) terlalu kecil / nol.',
                'hasil' => $hasil,
            ];
        }

        $x2 = $x1 - ($fx1 * ($x1 - $x0)) / $penyebut;
        $fx2 = f($x2);
        $error = abs($x2 - $x1);

        $hasil[] = [
            'iterasi' => $i,
            'x0' => $x0,
            'x1' => $x1,
            'x2' => $x2,
            'fx0' => $fx0,
            'fx1' => $fx1,
            'fx2' => $fx2,
            'error' => $error,
            'keputusan' => "Geser pasangan titik: x₀ ← x₁ dan x₁ ← x₂.",
        ];

        if (abs($fx2) < $toleransi || $error < $toleransi) {
            break;
        }

        $x0 = $x1;
        $x1 = $x2;
    }

    return [
        'hasil' => $hasil,
        'error' => null,
    ];
}

$aAwal = isset($_GET['a']) ? (float) $_GET['a'] : 1;
$bAwal = isset($_GET['b']) ? (float) $_GET['b'] : 2;
$batasIterasi = isset($_GET['iterasi']) ? min(1000, max(1, (int) $_GET['iterasi'])) : 10;

$perhitungan = hitungSecant($aAwal, $bAwal, $batasIterasi);
$hasil = $perhitungan['hasil'];
$errorMessage = $perhitungan['error'];

$fx0Awal = f($aAwal);
$fx1Awal = f($bAwal);
$penyebutAwal = $fx1Awal - $fx0Awal;
$x2Awal = abs($penyebutAwal) < 1e-15
    ? null
    : $bAwal - ($fx1Awal * ($bAwal - $aAwal)) / $penyebutAwal;
$fx2Awal = $x2Awal === null ? null : f($x2Awal);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animasi Metode Secant</title>
    <link rel="stylesheet" href="assets/metode-akar.css">

    <style>
        .secant-formula-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-top: 14px;
        }

        .secant-formula-item {
            padding: 14px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
        }

        .secant-formula-label {
            margin-bottom: 6px;
            font-size: 13px;
            color: #64748b;
        }

        .secant-formula-value {
            font-family: "Courier New", monospace;
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        .warning-box {
            margin-bottom: 18px;
            padding: 14px 16px;
            border: 1px solid #fecaca;
            border-radius: 12px;
            background: #fef2f2;
            color: #991b1b;
        }

        .method-difference {
            margin-top: 14px;
            padding: 14px 16px;
            border-left: 4px solid #2563eb;
            border-radius: 8px;
            background: #eff6ff;
            color: #1e3a8a;
            line-height: 1.6;
        }

        @media (max-width: 700px) {
            .secant-formula-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="page-secant">

<?php require __DIR__ . '/partials/navbar.php'; ?>

<div class="container">

    <h1>Animasi Metode Secant</h1>

    <div class="subtitle">
        Fungsi:
        <strong>f(x) = x² - 2</strong>.
        Tebakan awal:
        <strong>x₀ = <?= htmlspecialchars((string) $aAwal) ?></strong>
        dan
        <strong>x₁ = <?= htmlspecialchars((string) $bAwal) ?></strong>.
    </div>

    <div class="card">
        <form method="GET" class="input-form">

            <div class="input-group">
                <label for="inputA">x₀ / tebakan pertama</label>
                <input
                    type="number"
                    step="any"
                    id="inputA"
                    name="a"
                    value="<?= htmlspecialchars((string) $aAwal) ?>"
                    required
                >
            </div>

            <div class="input-group">
                <label for="inputB">x₁ / tebakan kedua</label>
                <input
                    type="number"
                    step="any"
                    id="inputB"
                    name="b"
                    value="<?= htmlspecialchars((string) $bAwal) ?>"
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

    <?php if ($errorMessage): ?>
        <div class="warning-box">
            <strong>Perhatian:</strong>
            <?= htmlspecialchars($errorMessage) ?>
            Gunakan dua tebakan awal yang menghasilkan nilai fungsi berbeda.
        </div>
    <?php endif; ?>

    <!-- ===================================================== -->
    <!-- 1. INTUISI METODE SECANT -->
    <!-- ===================================================== -->
    <div class="card">
        <h2 class="section-title">1) Intuisi Awal Metode Secant</h2>

        <div class="section-desc">
            Secant mencoba mendekati akar tanpa menghitung turunan.
            Dua titik pada kurva dihubungkan dengan garis lurus,
            lalu titik potong garis tersebut dengan sumbu-x menjadi tebakan akar berikutnya.
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
            <button class="btn-secondary" type="button" onclick="prevIntuition()">← Sebelumnya</button>
            <button class="btn-primary" type="button" onclick="nextIntuition()">Berikutnya →</button>
            <button class="btn-primary" type="button" onclick="playIntuition()">▶ Play</button>
            <button class="btn-secondary" type="button" onclick="pauseIntuition()">⏸ Pause</button>
            <button class="btn-secondary" type="button" onclick="resetIntuition()">Reset</button>
        </div>

        <div class="method-difference">
            <strong>Bedanya dengan Regula Falsi:</strong>
            Secant tidak wajib mempertahankan akar tetap di antara dua titik.
            Setelah x₂ diperoleh, pasangan berikutnya langsung menjadi
            <strong>(x₁, x₂)</strong>.
        </div>
    </div>

    <!-- ===================================================== -->
    <!-- 2. HITUNGAN SECANT -->
    <!-- ===================================================== -->
    <div class="card">
        <h2 class="section-title">2) Animasi Perhitungan Secant</h2>

        <div class="section-desc">
            Setiap iterasi membentuk garis secant melalui
            <strong>(x₀, f(x₀))</strong> dan <strong>(x₁, f(x₁))</strong>.
            Potongannya dengan sumbu-x menghasilkan <strong>x₂</strong>.
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
                <div class="stat-name">x₀ dan f(x₀)</div>
                <div class="stat-value" id="valX0">-</div>
            </div>

            <div class="stat">
                <div class="stat-name">x₁ dan f(x₁)</div>
                <div class="stat-value" id="valX1">-</div>
            </div>

            <div class="stat">
                <div class="stat-name">x₂ dan f(x₂)</div>
                <div class="stat-value" id="valX2">-</div>
            </div>

            <div class="stat">
                <div class="stat-name">|x₂ - x₁|</div>
                <div class="stat-value" id="valError">-</div>
            </div>
        </div>

        <div class="secant-formula-grid">
            <div class="secant-formula-item">
                <div class="secant-formula-label">Rumus Secant</div>
                <div class="secant-formula-value" id="formulaGeneral">
                    x₂ = x₁ - f(x₁)(x₁ - x₀) / (f(x₁) - f(x₀))
                </div>
            </div>

            <div class="secant-formula-item">
                <div class="secant-formula-label">Substitusi Iterasi Aktif</div>
                <div class="secant-formula-value" id="formulaSubstitution">-</div>
            </div>
        </div>

        <div class="decision" id="decisionBox"></div>

        <div class="controls">
            <button class="btn-secondary" type="button" onclick="prevIter()">← Sebelumnya</button>
            <button class="btn-primary" type="button" onclick="nextIter()">Berikutnya →</button>
            <button class="btn-primary" type="button" onclick="playIter()">▶ Play</button>
            <button class="btn-secondary" type="button" onclick="pauseIter()">⏸ Pause</button>
            <button class="btn-secondary" type="button" onclick="resetIter()">Reset</button>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Iterasi</th>
                        <th>x₀</th>
                        <th>x₁</th>
                        <th>x₂</th>
                        <th>f(x₀)</th>
                        <th>f(x₁)</th>
                        <th>f(x₂)</th>
                        <th>|x₂-x₁|</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($hasil as $row): ?>
                    <tr id="row-<?= $row['iterasi'] ?>">
                        <td><?= $row['iterasi'] ?></td>
                        <td><?= round($row['x0'], 10) ?></td>
                        <td><?= round($row['x1'], 10) ?></td>
                        <td><?= round($row['x2'], 10) ?></td>
                        <td><?= round($row['fx0'], 10) ?></td>
                        <td><?= round($row['fx1'], 10) ?></td>
                        <td><?= round($row['fx2'], 10) ?></td>
                        <td><?= round($row['error'], 10) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="note">
            <strong>Pola perpindahan Secant:</strong>
            x₀ lama dibuang, x₁ menjadi x₀ baru, dan x₂ menjadi x₁ baru.
            Jadi urutannya adalah
            <strong>(x₀, x₁) → (x₁, x₂) → (x₂, x₃) → ...</strong>
        </div>
    </div>

</div>

<script>
const secantData = <?= json_encode($hasil, JSON_NUMERIC_CHECK) ?>;

const initial = {
    x0: <?= json_encode($aAwal, JSON_NUMERIC_CHECK) ?>,
    x1: <?= json_encode($bAwal, JSON_NUMERIC_CHECK) ?>,
    fx0: <?= json_encode($fx0Awal, JSON_NUMERIC_CHECK) ?>,
    fx1: <?= json_encode($fx1Awal, JSON_NUMERIC_CHECK) ?>,
    x2: <?= json_encode($x2Awal, JSON_NUMERIC_CHECK) ?>,
    fx2: <?= json_encode($fx2Awal, JSON_NUMERIC_CHECK) ?>
};

function f(x) {
    return x * x - 2;
}

function formatNum(value, digits = 8) {
    if (value === null || value === undefined || !Number.isFinite(Number(value))) {
        return '-';
    }

    const n = Number(value);

    if (Math.abs(n) >= 1e6 || (Math.abs(n) > 0 && Math.abs(n) < 1e-6)) {
        return n.toExponential(5);
    }

    return n
        .toFixed(digits)
        .replace(/0+$/, '')
        .replace(/\.$/, '');
}

function setupCanvas(canvasId) {
    const canvas = document.getElementById(canvasId);
    const rect = canvas.parentElement.getBoundingClientRect();
    const dpr = window.devicePixelRatio || 1;

    canvas.width = rect.width * dpr;
    canvas.height = rect.height * dpr;

    const ctx = canvas.getContext('2d');
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

    return {
        canvas,
        ctx,
        width: rect.width,
        height: rect.height
    };
}

const margin = {
    top: 30,
    right: 30,
    bottom: 48,
    left: 64
};

function collectGraphValues() {
    const xs = [initial.x0, initial.x1];
    const ys = [initial.fx0, initial.fx1, 0];

    if (initial.x2 !== null) {
        xs.push(initial.x2);
        ys.push(initial.fx2);
    }

    secantData.forEach(item => {
        xs.push(item.x0, item.x1, item.x2);
        ys.push(item.fx0, item.fx1, item.fx2);
    });

    return {
        xs: xs.filter(Number.isFinite),
        ys: ys.filter(Number.isFinite)
    };
}

function createGraphDomain() {
    const values = collectGraphValues();

    let xMin = Math.min(...values.xs);
    let xMax = Math.max(...values.xs);

    if (xMin === xMax) {
        xMin -= 1;
        xMax += 1;
    }

    const xSpan = xMax - xMin;
    const xPadding = Math.max(xSpan * 0.18, 0.5);

    xMin -= xPadding;
    xMax += xPadding;

    // Sample fungsi supaya puncak kurva juga ikut terlihat.
    const sampledY = [];

    for (let i = 0; i <= 300; i++) {
        const x = xMin + (i / 300) * (xMax - xMin);
        const y = f(x);

        if (Number.isFinite(y)) {
            sampledY.push(y);
        }
    }

    const allY = [...values.ys, ...sampledY];

    let yMin = Math.min(...allY);
    let yMax = Math.max(...allY);

    if (yMin === yMax) {
        yMin -= 1;
        yMax += 1;
    }

    const ySpan = yMax - yMin;
    const yPadding = Math.max(ySpan * 0.12, 0.5);

    yMin -= yPadding;
    yMax += yPadding;

    return { xMin, xMax, yMin, yMax };
}

let graphDomain = createGraphDomain();

function mapX(x, width) {
    const plotW = width - margin.left - margin.right;

    return margin.left
        + ((x - graphDomain.xMin) / (graphDomain.xMax - graphDomain.xMin)) * plotW;
}

function mapY(y, height) {
    const plotH = height - margin.top - margin.bottom;

    return margin.top
        + ((graphDomain.yMax - y) / (graphDomain.yMax - graphDomain.yMin)) * plotH;
}

function drawPoint(ctx, x, y, color, label = null, radius = 6) {
    if (!Number.isFinite(x) || !Number.isFinite(y)) {
        return;
    }

    ctx.beginPath();
    ctx.arc(x, y, radius, 0, Math.PI * 2);
    ctx.fillStyle = color;
    ctx.fill();

    if (label) {
        ctx.fillStyle = color;
        ctx.font = 'bold 14px Arial';
        ctx.fillText(label, x + 9, y - 9);
    }
}

function drawDashedVertical(ctx, x, y1, y2, color = '#94a3b8') {
    if (![x, y1, y2].every(Number.isFinite)) {
        return;
    }

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

function drawArrow(ctx, x1, y1, x2, y2, color = '#0f766e') {
    const headLength = 8;
    const angle = Math.atan2(y2 - y1, x2 - x1);

    ctx.save();
    ctx.strokeStyle = color;
    ctx.fillStyle = color;
    ctx.lineWidth = 2;

    ctx.beginPath();
    ctx.moveTo(x1, y1);
    ctx.lineTo(x2, y2);
    ctx.stroke();

    ctx.beginPath();
    ctx.moveTo(x2, y2);
    ctx.lineTo(
        x2 - headLength * Math.cos(angle - Math.PI / 6),
        y2 - headLength * Math.sin(angle - Math.PI / 6)
    );
    ctx.lineTo(
        x2 - headLength * Math.cos(angle + Math.PI / 6),
        y2 - headLength * Math.sin(angle + Math.PI / 6)
    );
    ctx.closePath();
    ctx.fill();

    ctx.restore();
}

function drawBaseGraph(ctx, width, height) {
    ctx.clearRect(0, 0, width, height);

    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, width, height);

    const plotW = width - margin.left - margin.right;
    const plotH = height - margin.top - margin.bottom;

    // Grid.
    ctx.strokeStyle = '#e2e8f0';
    ctx.lineWidth = 1;

    for (let i = 0; i <= 8; i++) {
        const x = margin.left + i * (plotW / 8);

        ctx.beginPath();
        ctx.moveTo(x, margin.top);
        ctx.lineTo(x, height - margin.bottom);
        ctx.stroke();
    }

    for (let i = 0; i <= 6; i++) {
        const y = margin.top + i * (plotH / 6);

        ctx.beginPath();
        ctx.moveTo(margin.left, y);
        ctx.lineTo(width - margin.right, y);
        ctx.stroke();
    }

    // Sumbu x.
    const xAxisY = mapY(0, height);

    if (xAxisY >= margin.top && xAxisY <= height - margin.bottom) {
        ctx.strokeStyle = '#475569';
        ctx.lineWidth = 1.6;

        ctx.beginPath();
        ctx.moveTo(margin.left, xAxisY);
        ctx.lineTo(width - margin.right, xAxisY);
        ctx.stroke();

        ctx.fillStyle = '#475569';
        ctx.font = '12px Arial';
        ctx.fillText('x', width - margin.right + 8, xAxisY - 6);
    }

    // Sumbu y jika x = 0 ada di domain.
    const yAxisX = mapX(0, width);

    if (yAxisX >= margin.left && yAxisX <= width - margin.right) {
        ctx.strokeStyle = '#475569';
        ctx.lineWidth = 1.6;

        ctx.beginPath();
        ctx.moveTo(yAxisX, margin.top);
        ctx.lineTo(yAxisX, height - margin.bottom);
        ctx.stroke();

        ctx.fillStyle = '#475569';
        ctx.fillText('y', yAxisX + 8, margin.top + 12);
    }

    // Kurva fungsi.
    ctx.beginPath();
    let started = false;

    for (let i = 0; i <= 700; i++) {
        const x = graphDomain.xMin
            + (i / 700) * (graphDomain.xMax - graphDomain.xMin);

        const y = f(x);
        const px = mapX(x, width);
        const py = mapY(y, height);

        if (!Number.isFinite(px) || !Number.isFinite(py)) {
            continue;
        }

        if (!started) {
            ctx.moveTo(px, py);
            started = true;
        } else {
            ctx.lineTo(px, py);
        }
    }

    ctx.strokeStyle = '#7c3aed';
    ctx.lineWidth = 2.5;
    ctx.stroke();

    ctx.fillStyle = '#64748b';
    ctx.font = '12px Arial';
    ctx.fillText('f(x) = x² - 2', width - 145, margin.top + 16);

    // Label rentang x.
    ctx.fillStyle = '#64748b';
    ctx.fillText(formatNum(graphDomain.xMin, 3), margin.left, height - 14);

    const rightLabel = formatNum(graphDomain.xMax, 3);
    ctx.fillText(
        rightLabel,
        width - margin.right - ctx.measureText(rightLabel).width,
        height - 14
    );
}

function drawSecantLine(ctx, width, height, x0, fx0, x1, fx1, color = '#2563eb') {
    const dx = x1 - x0;

    if (Math.abs(dx) < 1e-15) {
        return;
    }

    const slope = (fx1 - fx0) / dx;
    const leftX = graphDomain.xMin;
    const rightX = graphDomain.xMax;
    const leftY = fx0 + slope * (leftX - x0);
    const rightY = fx0 + slope * (rightX - x0);

    ctx.save();

    ctx.beginPath();
    ctx.rect(
        margin.left,
        margin.top,
        width - margin.left - margin.right,
        height - margin.top - margin.bottom
    );
    ctx.clip();

    ctx.strokeStyle = color;
    ctx.lineWidth = 2.5;

    ctx.beginPath();
    ctx.moveTo(mapX(leftX, width), mapY(leftY, height));
    ctx.lineTo(mapX(rightX, width), mapY(rightY, height));
    ctx.stroke();

    ctx.restore();
}

// ==========================================================
// 1) INTUISI SECANT
// ==========================================================
const intuitionSteps = [
    {
        title: 'Langkah 1 — Ambil dua tebakan awal',
        text:
            'Mulai dari dua nilai x₀ dan x₁. Berbeda dengan Regula Falsi, ' +
            'keduanya tidak wajib mengapit akar.',
        formula:
            'P₀ = (x₀, f(x₀)),   P₁ = (x₁, f(x₁))'
    },
    {
        title: 'Langkah 2 — Hubungkan dua titik dengan garis secant',
        text:
            'Daripada memakai garis singgung seperti Newton-Raphson, ' +
            'Secant memakai garis lurus melalui P₀ dan P₁.',
        formula:
            'm ≈ (f(x₁) - f(x₀)) / (x₁ - x₀)'
    },
    {
        title: 'Langkah 3 — Cari titik potong garis dengan sumbu-x',
        text:
            'Garis secant dipanjangkan sampai memotong sumbu-x. ' +
            'Titik potong tersebut kita beri nama x₂.',
        formula:
            'Pada titik x₂ berlaku y = 0'
    },
    {
        title: 'Langkah 4 — Gunakan persamaan garis',
        text:
            'Persamaan garis melalui titik (x₁, f(x₁)) menggunakan kemiringan secant.',
        formula:
            'y - f(x₁) = [(f(x₁)-f(x₀))/(x₁-x₀)] (x-x₁)'
    },
    {
        title: 'Langkah 5 — Masukkan y = 0 dan x = x₂',
        text:
            'Karena x₂ terletak pada sumbu-x, nilai y pada garis secant adalah nol.',
        formula:
            '-f(x₁) = [(f(x₁)-f(x₀))/(x₁-x₀)] (x₂-x₁)'
    },
    {
        title: 'Langkah 6 — Diperoleh rumus Secant',
        text:
            'Susun ulang persamaan untuk memperoleh tebakan baru.',
        formula:
            'x₂ = x₁ - f(x₁)(x₁-x₀)/(f(x₁)-f(x₀))'
    },
    {
        title: 'Langkah 7 — Evaluasi fungsi asli di x₂',
        text:
            'x₂ berasal dari garis lurus pendekatan. Karena itu kita kembali ' +
            'ke fungsi asli dan menghitung f(x₂).',
        formula:
            'P₂ = (x₂, f(x₂))'
    },
    {
        title: 'Langkah 8 — Geser pasangan titik',
        text:
            'Secant tidak memilih interval berdasarkan tanda. ' +
            'Pasangan berikutnya langsung menjadi x₀ ← x₁ dan x₁ ← x₂.',
        formula:
            '(x₀, x₁) ← (x₁, x₂)'
    }
];

let intuitionIndex = 0;
let intuitionTimer = null;

function drawIntuition() {
    const { ctx, width, height } = setupCanvas('intuitionCanvas');
    drawBaseGraph(ctx, width, height);

    const xAxisY = mapY(0, height);

    const p0x = mapX(initial.x0, width);
    const p0y = mapY(initial.fx0, height);

    const p1x = mapX(initial.x1, width);
    const p1y = mapY(initial.fx1, height);

    drawPoint(ctx, p0x, p0y, '#ef4444', 'P₀');
    drawPoint(ctx, p1x, p1y, '#16a34a', 'P₁');

    drawDashedVertical(ctx, p0x, p0y, xAxisY);
    drawDashedVertical(ctx, p1x, p1y, xAxisY);

    ctx.fillStyle = '#334155';
    ctx.font = '13px Arial';

    ctx.fillText(
        `x₀ = ${formatNum(initial.x0)}`,
        p0x - 24,
        Math.min(height - margin.bottom + 23, xAxisY + 22)
    );

    ctx.fillText(
        `x₁ = ${formatNum(initial.x1)}`,
        p1x - 24,
        Math.min(height - margin.bottom + 39, xAxisY + 38)
    );

    if (intuitionIndex >= 1 && initial.x2 !== null) {
        drawSecantLine(
            ctx,
            width,
            height,
            initial.x0,
            initial.fx0,
            initial.x1,
            initial.fx1
        );

        ctx.fillStyle = '#2563eb';
        ctx.fillText(
            'garis secant',
            (p0x + p1x) / 2 + 8,
            (p0y + p1y) / 2 - 12
        );
    }

    if (intuitionIndex >= 2 && initial.x2 !== null) {
        const x2AxisX = mapX(initial.x2, width);

        drawPoint(ctx, x2AxisX, xAxisY, '#f59e0b', 'x₂');

        ctx.fillStyle = '#f59e0b';
        ctx.fillText(
            `x₂ = ${formatNum(initial.x2)}`,
            x2AxisX + 8,
            xAxisY + 22
        );
    }

    if (intuitionIndex >= 6 && initial.x2 !== null) {
        const p2x = mapX(initial.x2, width);
        const p2y = mapY(initial.fx2, height);

        drawDashedVertical(ctx, p2x, xAxisY, p2y, '#f59e0b');
        drawPoint(ctx, p2x, p2y, '#8b5cf6', 'P₂');
    }

    if (intuitionIndex >= 7 && initial.x2 !== null) {
        const p2x = mapX(initial.x2, width);
        const p2y = mapY(initial.fx2, height);

        drawArrow(
            ctx,
            p1x,
            p1y,
            p2x,
            p2y,
            '#0f766e'
        );

        ctx.fillStyle = '#0f766e';
        ctx.font = 'bold 13px Arial';
        ctx.fillText(
            'pasangan berikutnya: (x₁, x₂)',
            margin.left + 14,
            margin.top + 38
        );
    }

    const step = intuitionSteps[intuitionIndex];

    document.getElementById('intuitionStepTitle').innerHTML = step.title;
    document.getElementById('intuitionStepText').innerHTML = step.text;
    document.getElementById('intuitionFormula').innerHTML = step.formula;
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
    }, 1700);
}

function pauseIntuition() {
    clearInterval(intuitionTimer);
}

function resetIntuition() {
    clearInterval(intuitionTimer);
    intuitionIndex = 0;
    drawIntuition();
}

// ==========================================================
// 2) ANIMASI ITERASI SECANT
// ==========================================================
let iterIndex = 0;
let iterTimer = null;

function drawIteration(index = 0) {
    if (secantData.length === 0) {
        const { ctx, width, height } = setupCanvas('iterationCanvas');
        drawBaseGraph(ctx, width, height);

        document.getElementById('iterTitle').innerHTML = 'Iterasi tidak tersedia';
        document.getElementById('iterText').innerHTML =
            'Perhitungan Secant belum dapat dilakukan untuk tebakan awal ini.';
        document.getElementById('decisionBox').innerHTML =
            '<strong>Silakan ubah x₀ atau x₁.</strong>';

        return;
    }

    if (index < 0 || index >= secantData.length) {
        return;
    }

    iterIndex = index;

    const item = secantData[index];
    const { ctx, width, height } = setupCanvas('iterationCanvas');

    drawBaseGraph(ctx, width, height);

    const xAxisY = mapY(0, height);

    const p0x = mapX(item.x0, width);
    const p0y = mapY(item.fx0, height);

    const p1x = mapX(item.x1, width);
    const p1y = mapY(item.fx1, height);

    const x2AxisX = mapX(item.x2, width);
    const p2x = x2AxisX;
    const p2y = mapY(item.fx2, height);

    drawSecantLine(
        ctx,
        width,
        height,
        item.x0,
        item.fx0,
        item.x1,
        item.fx1
    );

    drawPoint(ctx, p0x, p0y, '#ef4444', 'P₀');
    drawPoint(ctx, p1x, p1y, '#16a34a', 'P₁');
    drawPoint(ctx, x2AxisX, xAxisY, '#f59e0b', 'x₂');
    drawPoint(ctx, p2x, p2y, '#8b5cf6', 'P₂');

    drawDashedVertical(ctx, p0x, p0y, xAxisY);
    drawDashedVertical(ctx, p1x, p1y, xAxisY);
    drawDashedVertical(ctx, p2x, xAxisY, p2y, '#f59e0b');

    ctx.fillStyle = '#334155';
    ctx.font = '13px Arial';

    ctx.fillText(
        `x₀=${formatNum(item.x0)}`,
        p0x - 22,
        xAxisY + 20
    );

    ctx.fillText(
        `x₁=${formatNum(item.x1)}`,
        p1x - 22,
        xAxisY + 36
    );

    ctx.fillStyle = '#f59e0b';
    ctx.fillText(
        `x₂=${formatNum(item.x2)}`,
        x2AxisX - 22,
        xAxisY + 52
    );

    document.getElementById('iterTitle').innerHTML =
        `Iterasi ${item.iterasi}`;

    document.getElementById('iterText').innerHTML =
        `Bentuk garis secant melalui P₀ dan P₁. ` +
        `Titik potong garis dengan sumbu-x menghasilkan ` +
        `<strong>x₂ = ${formatNum(item.x2)}</strong>. ` +
        `Kemudian hitung f(x₂) pada fungsi asli.`;

    document.getElementById('valX0').innerHTML =
        `${formatNum(item.x0)}<br>` +
        `<small>f(x₀) = ${formatNum(item.fx0)}</small>`;

    document.getElementById('valX1').innerHTML =
        `${formatNum(item.x1)}<br>` +
        `<small>f(x₁) = ${formatNum(item.fx1)}</small>`;

    document.getElementById('valX2').innerHTML =
        `${formatNum(item.x2)}<br>` +
        `<small>f(x₂) = ${formatNum(item.fx2)}</small>`;

    document.getElementById('valError').innerHTML =
        formatNum(item.error, 10);

    document.getElementById('formulaSubstitution').innerHTML =
        `x₂ = ${formatNum(item.x1)} - ` +
        `(${formatNum(item.fx1)})` +
        `(${formatNum(item.x1)} - ${formatNum(item.x0)}) / ` +
        `(${formatNum(item.fx1)} - ${formatNum(item.fx0)})` +
        ` = ${formatNum(item.x2)}`;

    document.getElementById('decisionBox').innerHTML =
        `<strong>Evaluasi:</strong> ` +
        `f(${formatNum(item.x2)}) = ${formatNum(item.fx2)}<br><br>` +
        `<strong>Langkah berikutnya:</strong> ` +
        `x₀ ← ${formatNum(item.x1)} dan ` +
        `x₁ ← ${formatNum(item.x2)}.`;

    document
        .querySelectorAll('tbody tr')
        .forEach(row => row.classList.remove('active-row'));

    const activeRow = document.getElementById('row-' + item.iterasi);

    if (activeRow) {
        activeRow.classList.add('active-row');
        
    }
}

function nextIter() {
    if (iterIndex < secantData.length - 1) {
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
        if (iterIndex >= secantData.length - 1) {
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
    graphDomain = createGraphDomain();
    drawIntuition();
    drawIteration(iterIndex);
});

drawIntuition();
drawIteration(0);
</script>

</body>
</html>