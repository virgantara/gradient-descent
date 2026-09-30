<?php

function f($x)
{
    return $x ** 2 - 2;
}

function g($x, $lambda = 0.25)
{
    return $x - ($lambda * f($x));
}

function dg($x, $lambda = 0.25)
{
    return 1 - (2 * $lambda * $x);
}

function hitungFixedPoint($x0, $lambda = 0.25, $step = 10, $toleransi = 1e-10)
{
    $hasil = [];

    for ($i = 1; $i <= $step; $i++) {
        $fx0 = f($x0);
        $gx0 = g($x0, $lambda);
        $dgx0 = dg($x0, $lambda);
        $x1 = $gx0;
        $fx1 = f($x1);
        $error = abs($x1 - $x0);

        $hasil[] = [
            'iterasi' => $i,
            'x0' => $x0,
            'fx0' => $fx0,
            'gx0' => $gx0,
            'dgx0' => $dgx0,
            'x1' => $x1,
            'fx1' => $fx1,
            'error' => $error,
        ];

        if (!is_finite($x1) || abs($x1) > 1e100) {
            return [
                'hasil' => $hasil,
                'error' => 'Iterasi membesar secara ekstrem. Bentuk g(x), nilai λ, atau tebakan awal ini tidak konvergen.',
            ];
        }

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
$lambda = isset($_GET['lambda']) ? (float) $_GET['lambda'] : 0.25;
$batasIterasi = isset($_GET['iterasi']) ? min(1000, max(1, (int) $_GET['iterasi'])) : 10;

$perhitungan = hitungFixedPoint($xAwal, $lambda, $batasIterasi);
$hasil = $perhitungan['hasil'];
$errorMessage = $perhitungan['error'];

$fxAwal = f($xAwal);
$gxAwal = g($xAwal, $lambda);
$dgxAwal = dg($xAwal, $lambda);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Animasi Metode Fixed-Point</title>

    <link rel="stylesheet" href="assets/metode-akar.css">

    <style>
        .fixed-formula-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-top: 14px;
        }

        .fixed-formula-item {
            padding: 14px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
        }

        .fixed-formula-label {
            margin-bottom: 6px;
            font-size: 13px;
            color: #64748b;
        }

        .fixed-formula-value {
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

        .info-box {
            margin-top: 14px;
            padding: 14px 16px;
            border-left: 4px solid #2563eb;
            border-radius: 8px;
            background: #eff6ff;
            color: #1e3a8a;
            line-height: 1.6;
        }

        .convergence-box {
            margin-top: 14px;
            padding: 14px 16px;
            border: 1px solid #d1fae5;
            border-radius: 10px;
            background: #ecfdf5;
            color: #065f46;
            line-height: 1.6;
        }

        @media (max-width: 700px) {
            .fixed-formula-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body class="page-fixed-point">

<?php require __DIR__ . '/partials/navbar.php'; ?>

<div class="container">

    <h1>Animasi Metode Fixed-Point</h1>

    <div class="subtitle">
        Fungsi akar:
        <strong>f(x) = x² - 2</strong>.

        Bentuk iterasi:
        <strong>
            g(x) = x - <?= htmlspecialchars((string) $lambda) ?>(x² - 2)
        </strong>.
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
                <label for="inputLambda">λ</label>

                <input
                    type="number"
                    step="any"
                    id="inputLambda"
                    name="lambda"
                    value="<?= htmlspecialchars((string) $lambda) ?>"
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
        </div>
    <?php endif; ?>

    <!-- ===================================================== -->
    <!-- 1. INTUISI FIXED-POINT -->
    <!-- ===================================================== -->
    <div class="card">

        <h2 class="section-title">
            1) Intuisi Awal Fixed-Point
        </h2>

        <div class="section-desc">
            Fixed-Point mengubah masalah
            <strong>f(x) = 0</strong>
            menjadi
            <strong>x = g(x)</strong>.

            Akar dicari sebagai titik yang tidak berubah ketika dimasukkan
            ke fungsi g:
            <strong>x* = g(x*)</strong>.
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
            <button
                class="btn-secondary"
                type="button"
                onclick="prevIntuition()"
            >
                ← Sebelumnya
            </button>

            <button
                class="btn-primary"
                type="button"
                onclick="nextIntuition()"
            >
                Berikutnya →
            </button>

            <button
                class="btn-primary"
                type="button"
                onclick="playIntuition()"
            >
                ▶ Play
            </button>

            <button
                class="btn-secondary"
                type="button"
                onclick="pauseIntuition()"
            >
                ⏸ Pause
            </button>

            <button
                class="btn-secondary"
                type="button"
                onclick="resetIntuition()"
            >
                Reset
            </button>
        </div>

        <div class="info-box">
            <strong>Cara membaca grafik:</strong>
            kurva ungu adalah <strong>y = g(x)</strong>,
            sedangkan garis diagonal adalah <strong>y = x</strong>.

            Titik perpotongan keduanya memenuhi
            <strong>x = g(x)</strong>,
            sehingga merupakan fixed point.
        </div>

    </div>

    <!-- ===================================================== -->
    <!-- 2. HITUNGAN FIXED-POINT -->
    <!-- ===================================================== -->
    <div class="card">

        <h2 class="section-title">
            2) Animasi Perhitungan Fixed-Point
        </h2>

        <div class="section-desc">
            Iterasi dilakukan dengan rumus sederhana
            <strong>xₙ₊₁ = g(xₙ)</strong>.

            Grafik memperlihatkan pola
            <strong>vertikal → horizontal → vertikal → horizontal</strong>
            yang dikenal sebagai <em>cobweb iteration</em>.
        </div>

        <div class="canvas-wrap">
            <canvas id="iterationCanvas"></canvas>
        </div>

        <div class="step-box">
            <div class="step-title" id="iterTitle">
                Iterasi 1
            </div>

            <div class="step-text" id="iterText"></div>
        </div>

        <div class="stats-grid">

            <div class="stat">
                <div class="stat-name">xₙ</div>
                <div class="stat-value" id="valX0">-</div>
            </div>

            <div class="stat">
                <div class="stat-name">f(xₙ)</div>
                <div class="stat-value" id="valFx0">-</div>
            </div>

            <div class="stat">
                <div class="stat-name">g(xₙ)</div>
                <div class="stat-value" id="valGx0">-</div>
            </div>

            <div class="stat">
                <div class="stat-name">|xₙ₊₁-xₙ|</div>
                <div class="stat-value" id="valError">-</div>
            </div>

        </div>

        <div class="fixed-formula-grid">

            <div class="fixed-formula-item">
                <div class="fixed-formula-label">
                    Rumus Fixed-Point
                </div>

                <div class="fixed-formula-value">
                    xₙ₊₁ = g(xₙ)
                </div>
            </div>

            <div class="fixed-formula-item">
                <div class="fixed-formula-label">
                    Substitusi Iterasi Aktif
                </div>

                <div
                    class="fixed-formula-value"
                    id="formulaSubstitution"
                >
                    -
                </div>
            </div>

        </div>

        <div class="decision" id="decisionBox"></div>

        <div class="controls">

            <button
                class="btn-secondary"
                type="button"
                onclick="prevIter()"
            >
                ← Sebelumnya
            </button>

            <button
                class="btn-primary"
                type="button"
                onclick="nextIter()"
            >
                Berikutnya →
            </button>

            <button
                class="btn-primary"
                type="button"
                onclick="playIter()"
            >
                ▶ Play
            </button>

            <button
                class="btn-secondary"
                type="button"
                onclick="pauseIter()"
            >
                ⏸ Pause
            </button>

            <button
                class="btn-secondary"
                type="button"
                onclick="resetIter()"
            >
                Reset
            </button>

        </div>

        <div class="table-wrap">

            <table>

                <thead>
                    <tr>
                        <th>Iterasi</th>
                        <th>xₙ</th>
                        <th>f(xₙ)</th>
                        <th>g(xₙ)</th>
                        <th>g'(xₙ)</th>
                        <th>xₙ₊₁</th>
                        <th>f(xₙ₊₁)</th>
                        <th>|xₙ₊₁-xₙ|</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($hasil as $row): ?>

                    <tr id="row-<?= $row['iterasi'] ?>">

                        <td>
                            <?= $row['iterasi'] ?>
                        </td>

                        <td>
                            <?= round($row['x0'], 10) ?>
                        </td>

                        <td>
                            <?= round($row['fx0'], 10) ?>
                        </td>

                        <td>
                            <?= round($row['gx0'], 10) ?>
                        </td>

                        <td>
                            <?= round($row['dgx0'], 10) ?>
                        </td>

                        <td>
                            <?= round($row['x1'], 10) ?>
                        </td>

                        <td>
                            <?= round($row['fx1'], 10) ?>
                        </td>

                        <td>
                            <?= round($row['error'], 10) ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

        <div class="convergence-box">
            <strong>Catatan konvergensi:</strong>
            secara lokal, iterasi fixed-point cenderung konvergen jika
            <strong>|g'(x)| &lt; 1</strong>
            di sekitar fixed point.

            Pada contoh ini:
            <strong>g'(x) = 1 - 2λx</strong>.
        </div>

    </div>

</div>

<script>

const lambda = <?= json_encode($lambda, JSON_NUMERIC_CHECK) ?>;
const fixedData = <?= json_encode($hasil, JSON_NUMERIC_CHECK) ?>;

const initial = {
    x0: <?= json_encode($xAwal, JSON_NUMERIC_CHECK) ?>,
    fx0: <?= json_encode($fxAwal, JSON_NUMERIC_CHECK) ?>,
    gx0: <?= json_encode($gxAwal, JSON_NUMERIC_CHECK) ?>,
    dgx0: <?= json_encode($dgxAwal, JSON_NUMERIC_CHECK) ?>
};

function f(x) {
    return x * x - 2;
}

function g(x) {
    return x - lambda * f(x);
}

function dg(x) {
    return 1 - 2 * lambda * x;
}

function formatNum(value, digits = 8) {
    if (
        value === null ||
        value === undefined ||
        !Number.isFinite(Number(value))
    ) {
        return '-';
    }

    const n = Number(value);

    if (
        Math.abs(n) >= 1e6 ||
        (Math.abs(n) > 0 && Math.abs(n) < 1e-6)
    ) {
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

    ctx.setTransform(
        dpr,
        0,
        0,
        dpr,
        0,
        0
    );

    return {
        canvas,
        ctx,
        width: rect.width,
        height: rect.height
    };
}

const margin = {
    top: 30,
    right: 34,
    bottom: 52,
    left: 64
};

function collectGraphValues() {
    const values = [
        initial.x0,
        initial.gx0,
        0
    ];

    fixedData.forEach(item => {
        values.push(
            item.x0,
            item.x1,
            item.gx0
        );
    });

    return values.filter(Number.isFinite);
}

function createGraphDomain() {
    const values = collectGraphValues();

    let minValue = Math.min(...values);
    let maxValue = Math.max(...values);

    if (minValue === maxValue) {
        minValue -= 1;
        maxValue += 1;
    }

    let span = maxValue - minValue;
    let padding = Math.max(span * 0.25, 0.75);

    let min = minValue - padding;
    let max = maxValue + padding;

    // Karena cobweb membandingkan y=g(x) dan y=x,
    // domain x dan y dibuat sama agar visual tidak menipu.
    const samples = [];

    for (let i = 0; i <= 300; i++) {
        const x = min + (i / 300) * (max - min);
        const y = g(x);

        if (Number.isFinite(y)) {
            samples.push(y);
        }
    }

    if (samples.length > 0) {
        min = Math.min(min, ...samples);
        max = Math.max(max, ...samples);

        span = max - min;
        padding = Math.max(span * 0.08, 0.3);

        min -= padding;
        max += padding;
    }

    return {
        xMin: min,
        xMax: max,
        yMin: min,
        yMax: max
    };
}

let graphDomain = createGraphDomain();

function mapX(x, width) {
    const plotW =
        width -
        margin.left -
        margin.right;

    return margin.left +
        (
            (x - graphDomain.xMin) /
            (graphDomain.xMax - graphDomain.xMin)
        ) *
        plotW;
}

function mapY(y, height) {
    const plotH =
        height -
        margin.top -
        margin.bottom;

    return margin.top +
        (
            (graphDomain.yMax - y) /
            (graphDomain.yMax - graphDomain.yMin)
        ) *
        plotH;
}

function drawPoint(
    ctx,
    x,
    y,
    color,
    label = null,
    radius = 6
) {
    if (
        !Number.isFinite(x) ||
        !Number.isFinite(y)
    ) {
        return;
    }

    ctx.beginPath();

    ctx.arc(
        x,
        y,
        radius,
        0,
        Math.PI * 2
    );

    ctx.fillStyle = color;
    ctx.fill();

    if (label) {
        ctx.fillStyle = color;
        ctx.font = 'bold 14px Arial';

        ctx.fillText(
            label,
            x + 9,
            y - 9
        );
    }
}

function drawArrow(
    ctx,
    x1,
    y1,
    x2,
    y2,
    color = '#0f766e'
) {
    const headLength = 8;
    const angle = Math.atan2(
        y2 - y1,
        x2 - x1
    );

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
    ctx.clearRect(
        0,
        0,
        width,
        height
    );

    ctx.fillStyle = '#ffffff';

    ctx.fillRect(
        0,
        0,
        width,
        height
    );

    const plotW =
        width -
        margin.left -
        margin.right;

    const plotH =
        height -
        margin.top -
        margin.bottom;

    // Grid.
    ctx.strokeStyle = '#e2e8f0';
    ctx.lineWidth = 1;

    for (let i = 0; i <= 8; i++) {
        const x =
            margin.left +
            i * (plotW / 8);

        ctx.beginPath();
        ctx.moveTo(x, margin.top);
        ctx.lineTo(x, height - margin.bottom);
        ctx.stroke();
    }

    for (let i = 0; i <= 6; i++) {
        const y =
            margin.top +
            i * (plotH / 6);

        ctx.beginPath();
        ctx.moveTo(margin.left, y);
        ctx.lineTo(width - margin.right, y);
        ctx.stroke();
    }

    // Sumbu x.
    const xAxisY = mapY(0, height);

    if (
        xAxisY >= margin.top &&
        xAxisY <= height - margin.bottom
    ) {
        ctx.strokeStyle = '#475569';
        ctx.lineWidth = 1.5;

        ctx.beginPath();
        ctx.moveTo(margin.left, xAxisY);
        ctx.lineTo(width - margin.right, xAxisY);
        ctx.stroke();

        ctx.fillStyle = '#475569';
        ctx.font = '12px Arial';

        ctx.fillText(
            'x',
            width - margin.right + 8,
            xAxisY - 6
        );
    }

    // Sumbu y.
    const yAxisX = mapX(0, width);

    if (
        yAxisX >= margin.left &&
        yAxisX <= width - margin.right
    ) {
        ctx.strokeStyle = '#475569';
        ctx.lineWidth = 1.5;

        ctx.beginPath();
        ctx.moveTo(yAxisX, margin.top);
        ctx.lineTo(yAxisX, height - margin.bottom);
        ctx.stroke();

        ctx.fillStyle = '#475569';

        ctx.fillText(
            'y',
            yAxisX + 8,
            margin.top + 12
        );
    }

    // Garis y = x.
    ctx.save();

    ctx.beginPath();

    ctx.rect(
        margin.left,
        margin.top,
        plotW,
        plotH
    );

    ctx.clip();

    ctx.strokeStyle = '#64748b';
    ctx.lineWidth = 2;

    ctx.beginPath();

    ctx.moveTo(
        mapX(graphDomain.xMin, width),
        mapY(graphDomain.xMin, height)
    );

    ctx.lineTo(
        mapX(graphDomain.xMax, width),
        mapY(graphDomain.xMax, height)
    );

    ctx.stroke();

    ctx.restore();

    // Kurva y = g(x).
    ctx.beginPath();

    let started = false;

    for (let i = 0; i <= 700; i++) {
        const x =
            graphDomain.xMin +
            (i / 700) *
            (graphDomain.xMax - graphDomain.xMin);

        const y = g(x);

        if (!Number.isFinite(y)) {
            started = false;
            continue;
        }

        const px = mapX(x, width);
        const py = mapY(y, height);

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

    ctx.fillText(
        'y = x',
        width - 90,
        margin.top + 18
    );

    ctx.fillStyle = '#7c3aed';

    ctx.fillText(
        'y = g(x)',
        width - 90,
        margin.top + 38
    );

    // Label domain.
    ctx.fillStyle = '#64748b';

    const leftLabel =
        formatNum(
            graphDomain.xMin,
            3
        );

    const rightLabel =
        formatNum(
            graphDomain.xMax,
            3
        );

    ctx.fillText(
        leftLabel,
        margin.left,
        height - 14
    );

    ctx.fillText(
        rightLabel,
        width -
            margin.right -
            ctx.measureText(rightLabel).width,
        height - 14
    );
}

function drawCobwebSegment(
    ctx,
    width,
    height,
    xCurrent,
    xNext,
    options = {}
) {
    const {
        verticalColor = '#2563eb',
        horizontalColor = '#0f766e',
        showVertical = true,
        showHorizontal = true,
        arrows = false
    } = options;

    const startX = mapX(xCurrent, width);
    const startY = mapY(xCurrent, height);

    const curveX = mapX(xCurrent, width);
    const curveY = mapY(xNext, height);

    const diagonalX = mapX(xNext, width);
    const diagonalY = mapY(xNext, height);

    if (showVertical) {
        if (arrows) {
            drawArrow(
                ctx,
                startX,
                startY,
                curveX,
                curveY,
                verticalColor
            );
        } else {
            ctx.strokeStyle = verticalColor;
            ctx.lineWidth = 2.2;

            ctx.beginPath();
            ctx.moveTo(startX, startY);
            ctx.lineTo(curveX, curveY);
            ctx.stroke();
        }
    }

    if (showHorizontal) {
        if (arrows) {
            drawArrow(
                ctx,
                curveX,
                curveY,
                diagonalX,
                diagonalY,
                horizontalColor
            );
        } else {
            ctx.strokeStyle = horizontalColor;
            ctx.lineWidth = 2.2;

            ctx.beginPath();
            ctx.moveTo(curveX, curveY);
            ctx.lineTo(diagonalX, diagonalY);
            ctx.stroke();
        }
    }
}

// ==========================================================
// 1) INTUISI FIXED-POINT
// ==========================================================

const intuitionSteps = [
    {
        title:
            'Langkah 1 — Ubah masalah akar menjadi x = g(x)',

        text:
            'Kita mulai dari f(x) = 0. ' +
            'Untuk contoh ini dipilih bentuk iterasi ' +
            'g(x) = x - λf(x).',

        formula:
            'f(x)=0  →  x = g(x) = x - λf(x)'
    },
    {
        title:
            'Langkah 2 — Fixed point adalah perpotongan y = g(x) dan y = x',

        text:
            'Jika suatu nilai x* memenuhi g(x*) = x*, ' +
            'maka x* tidak berubah lagi saat diiterasikan. ' +
            'Itulah fixed point.',

        formula:
            'x* = g(x*)'
    },
    {
        title:
            'Langkah 3 — Mulai dari tebakan awal x₀',

        text:
            'Letakkan x₀ pada garis y = x. ' +
            'Sekarang kita ingin mencari nilai g(x₀).',

        formula:
            'mulai dari x₀'
    },
    {
        title:
            'Langkah 4 — Bergerak vertikal menuju kurva y = g(x)',

        text:
            'Dari posisi (x₀, x₀), bergerak vertikal sampai menyentuh ' +
            'kurva y = g(x). Tinggi titik itu adalah g(x₀).',

        formula:
            'x₁ = g(x₀)'
    },
    {
        title:
            'Langkah 5 — Bergerak horizontal menuju garis y = x',

        text:
            'Nilai g(x₀) sekarang dipindahkan ke sumbu x dengan ' +
            'bergerak horizontal sampai garis y = x. ' +
            'Posisi baru tersebut adalah x₁.',

        formula:
            '(x₀, g(x₀)) → (x₁, x₁), dengan x₁ = g(x₀)'
    },
    {
        title:
            'Langkah 6 — Ulangi proses untuk x₁',

        text:
            'Dari x₁, lakukan lagi langkah vertikal ke y = g(x), ' +
            'kemudian horizontal ke y = x.',

        formula:
            'x₂ = g(x₁)'
    },
    {
        title:
            'Langkah 7 — Terbentuk pola cobweb',

        text:
            'Gerakan vertikal-horizontal berulang membentuk pola seperti jaring. ' +
            'Jika konvergen, lintasan semakin mendekati perpotongan y=g(x) dan y=x.',

        formula:
            'xₙ₊₁ = g(xₙ)'
    },
    {
        title:
            'Langkah 8 — Syarat konvergensi lokal',

        text:
            'Secara lokal, fixed-point cenderung konvergen jika ' +
            'kemiringan g di sekitar fixed point memiliki nilai mutlak kurang dari 1.',

        formula:
            '|g\'(x*)| < 1'
    }
];

let intuitionIndex = 0;
let intuitionTimer = null;

function drawIntuition() {
    const {
        ctx,
        width,
        height
    } =
        setupCanvas(
            'intuitionCanvas'
        );

    drawBaseGraph(
        ctx,
        width,
        height
    );

    const x0 = initial.x0;
    const x1 = initial.gx0;
    const x2 = g(x1);

    if (intuitionIndex >= 1) {
        const fixedApprox =
            fixedData.length > 0
                ? fixedData[fixedData.length - 1].x1
                : x1;

        drawPoint(
            ctx,
            mapX(fixedApprox, width),
            mapY(fixedApprox, height),
            '#dc2626',
            'fixed point'
        );
    }

    if (intuitionIndex >= 2) {
        drawPoint(
            ctx,
            mapX(x0, width),
            mapY(x0, height),
            '#ef4444',
            'x₀'
        );
    }

    if (intuitionIndex >= 3) {
        drawCobwebSegment(
            ctx,
            width,
            height,
            x0,
            x1,
            {
                showVertical: true,
                showHorizontal: false,
                arrows: true
            }
        );

        drawPoint(
            ctx,
            mapX(x0, width),
            mapY(x1, height),
            '#2563eb',
            'g(x₀)'
        );
    }

    if (intuitionIndex >= 4) {
        drawCobwebSegment(
            ctx,
            width,
            height,
            x0,
            x1,
            {
                showVertical: true,
                showHorizontal: true,
                arrows: true
            }
        );

        drawPoint(
            ctx,
            mapX(x1, width),
            mapY(x1, height),
            '#0f766e',
            'x₁'
        );
    }

    if (intuitionIndex >= 5) {
        drawCobwebSegment(
            ctx,
            width,
            height,
            x1,
            x2,
            {
                showVertical: true,
                showHorizontal: true,
                arrows: true
            }
        );
    }

    if (intuitionIndex >= 6) {
        let current = x0;

        const maxPreview =
            Math.min(
                fixedData.length,
                8
            );

        for (
            let i = 0;
            i < maxPreview;
            i++
        ) {
            const next =
                fixedData[i].x1;

            drawCobwebSegment(
                ctx,
                width,
                height,
                current,
                next,
                {
                    showVertical: true,
                    showHorizontal: true,
                    arrows: false
                }
            );

            current = next;
        }
    }

    const step =
        intuitionSteps[
            intuitionIndex
        ];

    document
        .getElementById(
            'intuitionStepTitle'
        )
        .innerHTML =
            step.title;

    document
        .getElementById(
            'intuitionStepText'
        )
        .innerHTML =
            step.text;

    document
        .getElementById(
            'intuitionFormula'
        )
        .innerHTML =
            step.formula;
}

function nextIntuition() {
    if (
        intuitionIndex <
        intuitionSteps.length - 1
    ) {
        intuitionIndex++;

        drawIntuition();
    }
}

function prevIntuition() {
    if (
        intuitionIndex > 0
    ) {
        intuitionIndex--;

        drawIntuition();
    }
}

function playIntuition() {
    clearInterval(
        intuitionTimer
    );

    intuitionTimer =
        setInterval(
            () => {
                if (
                    intuitionIndex >=
                    intuitionSteps.length - 1
                ) {
                    clearInterval(
                        intuitionTimer
                    );

                    return;
                }

                nextIntuition();
            },
            1700
        );
}

function pauseIntuition() {
    clearInterval(
        intuitionTimer
    );
}

function resetIntuition() {
    clearInterval(
        intuitionTimer
    );

    intuitionIndex = 0;

    drawIntuition();
}

// ==========================================================
// 2) ITERASI FIXED-POINT
// ==========================================================

let iterIndex = 0;
let iterTimer = null;

function drawIteration(
    index = 0
) {
    if (
        fixedData.length === 0
    ) {
        const {
            ctx,
            width,
            height
        } =
            setupCanvas(
                'iterationCanvas'
            );

        drawBaseGraph(
            ctx,
            width,
            height
        );

        document
            .getElementById(
                'iterTitle'
            )
            .innerHTML =
                'Iterasi tidak tersedia';

        document
            .getElementById(
                'iterText'
            )
            .innerHTML =
                'Perhitungan Fixed-Point belum menghasilkan iterasi.';

        document
            .getElementById(
                'decisionBox'
            )
            .innerHTML =
                '<strong>Silakan ubah x₀ atau λ.</strong>';

        return;
    }

    if (
        index < 0 ||
        index >= fixedData.length
    ) {
        return;
    }

    iterIndex = index;

    const item =
        fixedData[index];

    const {
        ctx,
        width,
        height
    } =
        setupCanvas(
            'iterationCanvas'
        );

    drawBaseGraph(
        ctx,
        width,
        height
    );

    // Gambarkan seluruh lintasan sampai iterasi aktif.
    for (
        let i = 0;
        i <= index;
        i++
    ) {
        const row =
            fixedData[i];

        const isCurrent =
            i === index;

        drawCobwebSegment(
            ctx,
            width,
            height,
            row.x0,
            row.x1,
            {
                verticalColor:
                    isCurrent
                        ? '#2563eb'
                        : '#94a3b8',

                horizontalColor:
                    isCurrent
                        ? '#0f766e'
                        : '#cbd5e1',

                showVertical: true,
                showHorizontal: true,
                arrows: isCurrent
            }
        );
    }

    drawPoint(
        ctx,
        mapX(
            item.x0,
            width
        ),
        mapY(
            item.x0,
            height
        ),
        '#ef4444',
        `x${item.iterasi - 1}`
    );

    drawPoint(
        ctx,
        mapX(
            item.x0,
            width
        ),
        mapY(
            item.x1,
            height
        ),
        '#2563eb',
        `g(x${item.iterasi - 1})`
    );

    drawPoint(
        ctx,
        mapX(
            item.x1,
            width
        ),
        mapY(
            item.x1,
            height
        ),
        '#0f766e',
        `x${item.iterasi}`
    );

    document
        .getElementById(
            'iterTitle'
        )
        .innerHTML =
            `Iterasi ${item.iterasi}`;

    document
        .getElementById(
            'iterText'
        )
        .innerHTML =
            `Dari xₙ = <strong>${formatNum(item.x0)}</strong>, ` +
            `hitung g(xₙ) = <strong>${formatNum(item.gx0)}</strong>. ` +
            `Karena xₙ₊₁ = g(xₙ), maka tebakan berikutnya adalah ` +
            `<strong>${formatNum(item.x1)}</strong>.`;

    document
        .getElementById(
            'valX0'
        )
        .innerHTML =
            formatNum(
                item.x0
            );

    document
        .getElementById(
            'valFx0'
        )
        .innerHTML =
            formatNum(
                item.fx0
            );

    document
        .getElementById(
            'valGx0'
        )
        .innerHTML =
            formatNum(
                item.gx0
            );

    document
        .getElementById(
            'valError'
        )
        .innerHTML =
            formatNum(
                item.error,
                10
            );

    document
        .getElementById(
            'formulaSubstitution'
        )
        .innerHTML =
            `xₙ₊₁ = ${formatNum(item.x0)} - ` +
            `${formatNum(lambda)}(${formatNum(item.x0)}² - 2)` +
            ` = ${formatNum(item.x1)}`;

    const convergence =
        Math.abs(item.dgx0) < 1
            ? 'Pada titik iterasi ini |g\'(xₙ)| < 1, sehingga perilakunya mendukung konvergensi lokal.'
            : 'Pada titik iterasi ini |g\'(xₙ)| ≥ 1, sehingga iterasi dapat sulit konvergen atau menjauh.';

    document
        .getElementById(
            'decisionBox'
        )
        .innerHTML =
            `<strong>Evaluasi fungsi asli:</strong> ` +
            `f(${formatNum(item.x1)}) = ${formatNum(item.fx1)}<br><br>` +

            `<strong>Turunan g:</strong> ` +
            `g'(xₙ) = ${formatNum(item.dgx0)}, ` +
            `|g'(xₙ)| = ${formatNum(Math.abs(item.dgx0))}<br><br>` +

            `<strong>Error:</strong> ` +
            `|xₙ₊₁ - xₙ| = ${formatNum(item.error, 10)}<br><br>` +

            `<strong>Interpretasi:</strong> ` +
            convergence;

    document
        .querySelectorAll(
            'tbody tr'
        )
        .forEach(
            row =>
                row.classList.remove(
                    'active-row'
                )
        );

    const activeRow =
        document.getElementById(
            'row-' +
            item.iterasi
        );

    if (activeRow) {
        activeRow.classList.add(
            'active-row'
        );

    }
}

function nextIter() {
    if (
        iterIndex <
        fixedData.length - 1
    ) {
        drawIteration(
            iterIndex + 1
        );
    }
}

function prevIter() {
    if (
        iterIndex > 0
    ) {
        drawIteration(
            iterIndex - 1
        );
    }
}

function playIter() {
    clearInterval(
        iterTimer
    );

    iterTimer =
        setInterval(
            () => {
                if (
                    iterIndex >=
                    fixedData.length - 1
                ) {
                    clearInterval(
                        iterTimer
                    );

                    return;
                }

                nextIter();
            },
            1800
        );
}

function pauseIter() {
    clearInterval(
        iterTimer
    );
}

function resetIter() {
    clearInterval(
        iterTimer
    );

    drawIteration(0);
}

window.addEventListener(
    'resize',
    () => {
        graphDomain =
            createGraphDomain();

        drawIntuition();

        drawIteration(
            iterIndex
        );
    }
);

drawIntuition();
drawIteration(0);

</script>

</body>
</html>