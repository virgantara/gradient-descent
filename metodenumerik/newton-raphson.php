<?php

function f($x)
{
    return $x ** 2 - 2;
}

function df($x)
{
    return 2 * $x;
}

function hitungNewtonRaphson($x0, $step = 10, $toleransi = 1e-10)
{
    $hasil = [];

    for ($i = 1; $i <= $step; $i++) {
        $fx0 = f($x0);
        $dfx0 = df($x0);

        if (abs($dfx0) < 1e-15) {
            return [
                'error' => 'Newton-Raphson berhenti karena turunan f\'(x) terlalu kecil / nol.',
                'hasil' => $hasil,
            ];
        }

        $x1 = $x0 - ($fx0 / $dfx0);
        $fx1 = f($x1);
        $error = abs($x1 - $x0);

        $hasil[] = [
            'iterasi' => $i,
            'x0' => $x0,
            'x1' => $x1,
            'fx0' => $fx0,
            'dfx0' => $dfx0,
            'fx1' => $fx1,
            'error' => $error,
            'keputusan' => 'Gunakan x₁ sebagai tebakan baru pada iterasi berikutnya.',
        ];

        if (abs($fx1) < $toleransi || $error < $toleransi) {
            break;
        }

        $x0 = $x1;
    }

    return [
        'hasil' => $hasil,
        'error' => null,
    ];
}

$xAwal = isset($_GET['x0']) ? (float) $_GET['x0'] : 2;
$batasIterasi = isset($_GET['iterasi']) ? min(1000, max(1, (int) $_GET['iterasi'])) : 10;

$perhitungan = hitungNewtonRaphson($xAwal, $batasIterasi);
$hasil = $perhitungan['hasil'];
$errorMessage = $perhitungan['error'];

$fxAwal = f($xAwal);
$dfxAwal = df($xAwal);
$x1Awal = abs($dfxAwal) < 1e-15 ? null : $xAwal - ($fxAwal / $dfxAwal);
$fx1Awal = $x1Awal === null ? null : f($x1Awal);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animasi Metode Newton-Raphson</title>
    <link rel="stylesheet" href="assets/metode-akar.css">

    <style>
        .newton-formula-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-top: 14px;
        }

        .newton-formula-item {
            padding: 14px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
        }

        .newton-formula-label {
            margin-bottom: 6px;
            font-size: 13px;
            color: #64748b;
        }

        .newton-formula-value {
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
            .newton-formula-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="page-newton-raphson">

<?php require __DIR__ . '/partials/navbar.php'; ?>

<div class="container">

    <h1>Animasi Metode Newton-Raphson</h1>

    <div class="subtitle">
        Fungsi:
        <strong>f(x) = x² - 2</strong>,
        turunannya:
        <strong>f'(x) = 2x</strong>.
        Tebakan awal:
        <strong>x₀ = <?= htmlspecialchars((string) $xAwal) ?></strong>.
    </div>

    <div class="card">
        <form method="GET" class="input-form">

            <div class="input-group">
                <label for="inputX0">x₀ / tebakan awal</label>
                <input
                    type="number"
                    step="any"
                    id="inputX0"
                    name="x0"
                    value="<?= htmlspecialchars((string) $xAwal) ?>"
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
            Pilih tebakan awal lain agar f'(x₀) tidak nol.
        </div>
    <?php endif; ?>

    <!-- ===================================================== -->
    <!-- 1. INTUISI NEWTON-RAPHSON -->
    <!-- ===================================================== -->
    <div class="card">
        <h2 class="section-title">1) Intuisi Awal Newton-Raphson</h2>

        <div class="section-desc">
            Newton-Raphson memakai <strong>garis singgung</strong> pada kurva.
            Titik potong garis singgung dengan sumbu-x menjadi tebakan akar berikutnya.
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
            <strong>Inti Newton-Raphson:</strong>
            dari sebuah tebakan <strong>x₀</strong>, naik ke kurva pada
            <strong>P₀ = (x₀, f(x₀))</strong>, buat garis singgung,
            lalu ikuti garis tersebut sampai memotong sumbu-x.
            Potongan itu adalah <strong>x₁</strong>.
        </div>
    </div>

    <!-- ===================================================== -->
    <!-- 2. HITUNGAN NEWTON-RAPHSON -->
    <!-- ===================================================== -->
    <div class="card">
        <h2 class="section-title">2) Animasi Perhitungan Newton-Raphson</h2>

        <div class="section-desc">
            Pada setiap iterasi, hitung nilai fungsi dan turunannya di x₀,
            kemudian gunakan rumus Newton-Raphson untuk memperoleh x₁.
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
                <div class="stat-name">x₀</div>
                <div class="stat-value" id="valX0">-</div>
            </div>

            <div class="stat">
                <div class="stat-name">f(x₀)</div>
                <div class="stat-value" id="valFx0">-</div>
            </div>

            <div class="stat">
                <div class="stat-name">f'(x₀)</div>
                <div class="stat-value" id="valDfx0">-</div>
            </div>

            <div class="stat">
                <div class="stat-name">x₁</div>
                <div class="stat-value" id="valX1">-</div>
            </div>
        </div>

        <div class="newton-formula-grid">
            <div class="newton-formula-item">
                <div class="newton-formula-label">Rumus Newton-Raphson</div>
                <div class="newton-formula-value">
                    x₁ = x₀ - f(x₀) / f'(x₀)
                </div>
            </div>

            <div class="newton-formula-item">
                <div class="newton-formula-label">Substitusi Iterasi Aktif</div>
                <div class="newton-formula-value" id="formulaSubstitution">-</div>
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
                        <th>f(x₀)</th>
                        <th>f'(x₀)</th>
                        <th>x₁</th>
                        <th>f(x₁)</th>
                        <th>|x₁-x₀|</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($hasil as $row): ?>
                    <tr id="row-<?= $row['iterasi'] ?>">
                        <td><?= $row['iterasi'] ?></td>
                        <td><?= round($row['x0'], 10) ?></td>
                        <td><?= round($row['fx0'], 10) ?></td>
                        <td><?= round($row['dfx0'], 10) ?></td>
                        <td><?= round($row['x1'], 10) ?></td>
                        <td><?= round($row['fx1'], 10) ?></td>
                        <td><?= round($row['error'], 10) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="note">
            <strong>Pola Newton-Raphson:</strong>
            setelah x₁ diperoleh, nilai tersebut menjadi tebakan pada iterasi berikutnya:
            <strong>x₀ ← x₁</strong>.
            Proses diulang sampai nilai fungsi atau perubahan x cukup kecil.
        </div>
    </div>

</div>

<script>
const newtonData = <?= json_encode($hasil, JSON_NUMERIC_CHECK) ?>;

const initial = {
    x0: <?= json_encode($xAwal, JSON_NUMERIC_CHECK) ?>,
    fx0: <?= json_encode($fxAwal, JSON_NUMERIC_CHECK) ?>,
    dfx0: <?= json_encode($dfxAwal, JSON_NUMERIC_CHECK) ?>,
    x1: <?= json_encode($x1Awal, JSON_NUMERIC_CHECK) ?>,
    fx1: <?= json_encode($fx1Awal, JSON_NUMERIC_CHECK) ?>
};

function f(x) {
    return x * x - 2;
}

function df(x) {
    return 2 * x;
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
    const xs = [initial.x0];
    const ys = [initial.fx0, 0];

    if (initial.x1 !== null) {
        xs.push(initial.x1);
        ys.push(initial.fx1);
    }

    newtonData.forEach(item => {
        xs.push(item.x0, item.x1);
        ys.push(item.fx0, item.fx1, 0);
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

    let xSpan = xMax - xMin;
    let xPadding = Math.max(xSpan * 0.35, 0.75);

    xMin -= xPadding;
    xMax += xPadding;

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

    const leftLabel = formatNum(graphDomain.xMin, 3);
    const rightLabel = formatNum(graphDomain.xMax, 3);

    ctx.fillText(leftLabel, margin.left, height - 14);
    ctx.fillText(
        rightLabel,
        width - margin.right - ctx.measureText(rightLabel).width,
        height - 14
    );
}

function drawTangentLine(ctx, width, height, x0, fx0, slope, color = '#2563eb') {
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
// 1) INTUISI NEWTON-RAPHSON
// ==========================================================
const intuitionSteps = [
    {
        title: 'Langkah 1 — Mulai dari sebuah tebakan x₀',
        text:
            'Pilih satu nilai x₀ sebagai tebakan awal akar. ' +
            'Kemudian hitung nilai fungsi f(x₀).',
        formula:
            'P₀ = (x₀, f(x₀))'
    },
    {
        title: 'Langkah 2 — Lihat kemiringan kurva di x₀',
        text:
            'Kemiringan garis singgung pada titik P₀ diberikan oleh turunan fungsi.',
        formula:
            'kemiringan = f\'(x₀)'
    },
    {
        title: 'Langkah 3 — Bentuk garis singgung di P₀',
        text:
            'Newton-Raphson mengganti kurva di sekitar P₀ dengan sebuah garis singgung.',
        formula:
            'y - f(x₀) = f\'(x₀)(x - x₀)'
    },
    {
        title: 'Langkah 4 — Cari titik potong garis singgung dengan sumbu-x',
        text:
            'Titik potong garis singgung dengan sumbu-x menjadi tebakan baru x₁.',
        formula:
            'Pada x = x₁, y = 0'
    },
    {
        title: 'Langkah 5 — Substitusikan y = 0 dan x = x₁',
        text:
            'Masukkan kondisi titik potong sumbu-x ke persamaan garis singgung.',
        formula:
            '0 - f(x₀) = f\'(x₀)(x₁ - x₀)'
    },
    {
        title: 'Langkah 6 — Susun ulang untuk mendapatkan x₁',
        text:
            'Dari persamaan tersebut diperoleh rumus Newton-Raphson.',
        formula:
            'x₁ = x₀ - f(x₀) / f\'(x₀)'
    },
    {
        title: 'Langkah 7 — Kembali ke kurva pada x₁',
        text:
            'x₁ berasal dari garis singgung. Sekarang evaluasi fungsi asli pada x₁.',
        formula:
            'P₁ = (x₁, f(x₁))'
    },
    {
        title: 'Langkah 8 — Ulangi proses',
        text:
            'Gunakan x₁ sebagai tebakan baru, bentuk garis singgung baru, ' +
            'dan ulangi sampai mendekati akar.',
        formula:
            'x₀ ← x₁'
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

    drawPoint(ctx, p0x, p0y, '#ef4444', 'P₀');
    drawDashedVertical(ctx, p0x, p0y, xAxisY);

    ctx.fillStyle = '#334155';
    ctx.font = '13px Arial';
    ctx.fillText(
        `x₀ = ${formatNum(initial.x0)}`,
        p0x - 24,
        Math.min(height - margin.bottom + 24, xAxisY + 22)
    );

    if (intuitionIndex >= 1) {
        ctx.fillStyle = '#0f766e';
        ctx.font = 'bold 13px Arial';
        ctx.fillText(
            `f'(x₀) = ${formatNum(initial.dfx0)}`,
            p0x + 12,
            p0y + 26
        );
    }

    if (intuitionIndex >= 2 && initial.x1 !== null) {
        drawTangentLine(
            ctx,
            width,
            height,
            initial.x0,
            initial.fx0,
            initial.dfx0
        );

        ctx.fillStyle = '#2563eb';
        ctx.font = '13px Arial';
        ctx.fillText(
            'garis singgung',
            margin.left + 16,
            margin.top + 38
        );
    }

    if (intuitionIndex >= 3 && initial.x1 !== null) {
        const x1AxisX = mapX(initial.x1, width);

        drawPoint(ctx, x1AxisX, xAxisY, '#f59e0b', 'x₁');

        ctx.fillStyle = '#f59e0b';
        ctx.fillText(
            `x₁ = ${formatNum(initial.x1)}`,
            x1AxisX + 8,
            xAxisY + 22
        );
    }

    if (intuitionIndex >= 6 && initial.x1 !== null) {
        const p1x = mapX(initial.x1, width);
        const p1y = mapY(initial.fx1, height);

        drawDashedVertical(ctx, p1x, xAxisY, p1y, '#f59e0b');
        drawPoint(ctx, p1x, p1y, '#8b5cf6', 'P₁');
    }

    if (intuitionIndex >= 7 && initial.x1 !== null) {
        const p1x = mapX(initial.x1, width);
        const p1y = mapY(initial.fx1, height);

        drawArrow(
            ctx,
            mapX(initial.x1, width),
            xAxisY,
            p1x,
            p1y,
            '#0f766e'
        );

        ctx.fillStyle = '#0f766e';
        ctx.font = 'bold 13px Arial';
        ctx.fillText(
            'x₁ menjadi tebakan berikutnya',
            margin.left + 16,
            margin.top + 58
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
// 2) ANIMASI ITERASI NEWTON-RAPHSON
// ==========================================================
let iterIndex = 0;
let iterTimer = null;

function drawIteration(index = 0) {
    if (newtonData.length === 0) {
        const { ctx, width, height } = setupCanvas('iterationCanvas');
        drawBaseGraph(ctx, width, height);

        document.getElementById('iterTitle').innerHTML = 'Iterasi tidak tersedia';
        document.getElementById('iterText').innerHTML =
            'Perhitungan Newton-Raphson belum dapat dilakukan untuk tebakan awal ini.';
        document.getElementById('decisionBox').innerHTML =
            '<strong>Silakan pilih x₀ lain.</strong>';

        return;
    }

    if (index < 0 || index >= newtonData.length) {
        return;
    }

    iterIndex = index;

    const item = newtonData[index];
    const { ctx, width, height } = setupCanvas('iterationCanvas');

    drawBaseGraph(ctx, width, height);

    const xAxisY = mapY(0, height);

    const p0x = mapX(item.x0, width);
    const p0y = mapY(item.fx0, height);

    const x1AxisX = mapX(item.x1, width);
    const p1y = mapY(item.fx1, height);

    drawTangentLine(
        ctx,
        width,
        height,
        item.x0,
        item.fx0,
        item.dfx0
    );

    drawPoint(ctx, p0x, p0y, '#ef4444', 'P₀');
    drawPoint(ctx, x1AxisX, xAxisY, '#f59e0b', 'x₁');
    drawPoint(ctx, x1AxisX, p1y, '#8b5cf6', 'P₁');

    drawDashedVertical(ctx, p0x, p0y, xAxisY);
    drawDashedVertical(ctx, x1AxisX, xAxisY, p1y, '#f59e0b');

    ctx.fillStyle = '#334155';
    ctx.font = '13px Arial';

    ctx.fillText(
        `x₀=${formatNum(item.x0)}`,
        p0x - 22,
        xAxisY + 20
    );

    ctx.fillStyle = '#f59e0b';

    ctx.fillText(
        `x₁=${formatNum(item.x1)}`,
        x1AxisX - 22,
        xAxisY + 38
    );

    document.getElementById('iterTitle').innerHTML =
        `Iterasi ${item.iterasi}`;

    document.getElementById('iterText').innerHTML =
        `Pada x₀ = <strong>${formatNum(item.x0)}</strong>, ` +
        `nilai f(x₀) = <strong>${formatNum(item.fx0)}</strong> ` +
        `dan f'(x₀) = <strong>${formatNum(item.dfx0)}</strong>. ` +
        `Garis singgung memotong sumbu-x di ` +
        `<strong>x₁ = ${formatNum(item.x1)}</strong>.`;

    document.getElementById('valX0').innerHTML =
        formatNum(item.x0);

    document.getElementById('valFx0').innerHTML =
        formatNum(item.fx0);

    document.getElementById('valDfx0').innerHTML =
        formatNum(item.dfx0);

    document.getElementById('valX1').innerHTML =
        `${formatNum(item.x1)}<br>` +
        `<small>f(x₁) = ${formatNum(item.fx1)}</small>`;

    document.getElementById('formulaSubstitution').innerHTML =
        `x₁ = ${formatNum(item.x0)} - ` +
        `(${formatNum(item.fx0)} / ${formatNum(item.dfx0)})` +
        ` = ${formatNum(item.x1)}`;

    document.getElementById('decisionBox').innerHTML =
        `<strong>Evaluasi:</strong> ` +
        `f(${formatNum(item.x1)}) = ${formatNum(item.fx1)}<br><br>` +
        `<strong>Error:</strong> ` +
        `|x₁ - x₀| = ${formatNum(item.error, 10)}<br><br>` +
        `<strong>Langkah berikutnya:</strong> ` +
        `x₀ ← ${formatNum(item.x1)}.`;

    document
        .querySelectorAll('tbody tr')
        .forEach(row => row.classList.remove('active-row'));

    const activeRow = document.getElementById('row-' + item.iterasi);

    if (activeRow) {
        activeRow.classList.add('active-row');

    }
}

function nextIter() {
    if (iterIndex < newtonData.length - 1) {
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
        if (iterIndex >= newtonData.length - 1) {
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