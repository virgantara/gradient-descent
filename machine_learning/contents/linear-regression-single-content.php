<main class="container">
<div class="container">
    <h1>Linear Regression dengan Gradient Descent</h1>

    <div class="subtitle">
        Visualisasi bagaimana garis <strong>ŷ = c + mx</strong> belajar dari data
        dengan meminimalkan error kuadrat menggunakan gradient descent.
    </div>

    <div class="card">
        <form method="GET" class="input-form">
            <div class="input-group">
                <label for="lr">Learning Rate</label>
                <input type="number" step="any" min="0.0000001" id="lr" name="lr" value="<?= htmlspecialchars((string) $lr) ?>" required>
            </div>

            <div class="input-group">
                <label for="epoch">Epoch</label>
                <input type="number" min="1" max="10000" id="epoch" name="epoch" value="<?= $epoch ?>" required>
            </div>

            <div class="input-group">
                <label for="m">Initial m</label>
                <input type="number" step="any" id="m" name="m" value="<?= htmlspecialchars((string) $initialM) ?>" required>
            </div>

            <div class="input-group">
                <label for="c">Initial c</label>
                <input type="number" step="any" id="c" name="c" value="<?= htmlspecialchars((string) $initialC) ?>" required>
            </div>

            <div class="input-action">
                <button type="submit" class="btn-primary">Train</button>
            </div>
        </form>
    </div>

    <?php if ($errorMessage): ?>
        <div class="card">
            <div class="alert">
                <strong>Training berhenti:</strong>
                <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="card">
        <h2>1) Intuisi Awal Linear Regression</h2>

        <div class="section-desc">
            Sebelum melihat training, pahami dulu apa yang sedang dicari gradient descent:
            sebuah garis yang membuat total residual kuadrat sekecil mungkin.
        </div>

        <div class="canvas-wrap">
            <canvas id="intuitionCanvas"></canvas>
        </div>

        <div class="step-box">
            <div class="step-title" id="intuitionTitle"></div>
            <div class="step-text" id="intuitionText"></div>
            <div class="formula-box" id="intuitionFormula"></div>
        </div>

        <div class="controls">
            <button type="button" class="btn-secondary" onclick="previousIntuition()">← Sebelumnya</button>
            <button type="button" class="btn-primary" onclick="nextIntuition()">Berikutnya →</button>
            <button type="button" class="btn-primary" onclick="playIntuition()">▶ Play Intuisi</button>
            <button type="button" class="btn-secondary" onclick="pauseIntuition()">⏸ Pause</button>
            <button type="button" class="btn-secondary" onclick="resetIntuition()">↺ Reset</button>
        </div>
    </div>

    <div class="card">
        <h2>2) Training Langkah demi Langkah</h2>

        <div class="section-desc">
            Gunakan tombol atau slider untuk melihat perubahan parameter
            <strong>m</strong> dan <strong>c</strong> pada setiap epoch.
        </div>

        <div class="train-layout">
            <div>
                <div class="canvas-wrap">
                    <canvas id="trainingCanvas"></canvas>
                </div>

                <div class="range-row">
                    <span>Epoch</span>
                    <input type="range" id="epochSlider" min="0" max="<?= max(0, count($states) - 1) ?>" value="0" step="1">
                    <strong id="epochLabel">0</strong>
                </div>

                <div class="controls">
                    <button type="button" class="btn-secondary" onclick="previousEpoch()">← Sebelumnya</button>
                    <button type="button" class="btn-primary" onclick="nextEpoch()">Berikutnya →</button>
                    <button type="button" class="btn-primary" onclick="playTraining()">▶ Play Training</button>
                    <button type="button" class="btn-secondary" onclick="pauseTraining()">⏸ Pause</button>
                    <button type="button" class="btn-secondary" onclick="resetTraining()">↺ Reset</button>
                </div>
            </div>

            <div>
                <div class="stats-grid" style="grid-template-columns: 1fr 1fr; margin-top: 0;">
                    <div class="stat">
                        <div class="stat-name">Slope m</div>
                        <div class="stat-value" id="statM">-</div>
                    </div>

                    <div class="stat">
                        <div class="stat-name">Intercept c</div>
                        <div class="stat-value" id="statC">-</div>
                    </div>

                    <div class="stat">
                        <div class="stat-name">MSE</div>
                        <div class="stat-value" id="statMSE">-</div>
                    </div>

                    <div class="stat">
                        <div class="stat-name">R²</div>
                        <div class="stat-value" id="statR2">-</div>
                    </div>
                </div>

                <div class="step-box">
                    <div class="step-title">Update Parameter</div>
                    <div class="formula-box" id="updateFormula">-</div>
                </div>

                <div class="step-box">
                    <div class="step-title">Gradient</div>
                    <div class="step-text" id="gradientInfo">-</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>3) Kurva Loss</h2>

        <div class="section-desc">
            MSE seharusnya turun ketika gradient descent bergerak ke parameter yang lebih baik.
        </div>

        <div class="loss-wrap">
            <canvas id="lossCanvas"></canvas>
        </div>
    </div>

    <div class="card">
        <h2>Hasil Akhir</h2>

        <?php if ($finalState): ?>
            <ul class="summary-list">
                <li>Durasi training: <?= number_format($executionTime, 8) ?> detik</li>
                <li>Learning rate: <?= htmlspecialchars((string) $lr) ?></li>
                <li>Epoch: <?= $epoch ?></li>
                <li>m = <?= round($finalState['m'], 8) ?></li>
                <li>c = <?= round($finalState['c'], 8) ?></li>
                <li>SSE = <?= round($finalState['sse'], 8) ?></li>
                <li>MSE = <?= round($finalState['mse'], 8) ?></li>
                <li>MAE = <?= round($finalState['mae'], 8) ?></li>
                <li>R² = <?= $finalState['r2'] === null ? '-' : round($finalState['r2'], 8) ?></li>
                <li><strong>ŷ = <?= round($finalState['c'], 4) ?> + <?= round($finalState['m'], 4) ?>x</strong></li>
            </ul>
        <?php endif; ?>

        <div class="note">
            Pada versi lama, variabel <strong>r2</strong> sebenarnya hanya menyimpan absolute residual titik terakhir,
            dan <strong>ssr</strong> terus diakumulasi lintas epoch. Versi ini menghitung R², SSE, MSE, dan MAE
            dari model pada epoch yang sedang aktif.
        </div>
    </div>
</div>

<script>
const points = <?= json_encode($points, JSON_NUMERIC_CHECK) ?>;
const states = <?= json_encode($states, JSON_NUMERIC_CHECK) ?>;
const learningRate = <?= json_encode($lr, JSON_NUMERIC_CHECK) ?>;
let intuitionStep = 0;
let intuitionTimer = null;
let currentEpochIndex = 0;
let trainingTimer = null;

const intuitionSteps = [
    {
        title: 'Langkah 1 — Kita punya sekumpulan titik data',
        text: 'Tujuan regresi linear adalah mencari satu garis yang mewakili kecenderungan hubungan x dan y.',
        formula: 'data = {(x₁,y₁), (x₂,y₂), ..., (xₙ,yₙ)}'
    },
    {
        title: 'Langkah 2 — Mulai dari sebuah garis tebakan',
        text: 'Garis ditentukan oleh dua parameter: slope m dan intercept c. Pada awal training, nilainya boleh belum bagus.',
        formula: 'ŷ = c + mx'
    },
    {
        title: 'Langkah 3 — Ukur residual setiap titik',
        text: 'Residual adalah jarak vertikal antara nilai aktual y dan prediksi garis ŷ. Residual positif berarti titik berada di atas garis, residual negatif berarti di bawah garis.',
        formula: 'eᵢ = yᵢ - ŷᵢ'
    },
    {
        title: 'Langkah 4 — Kuadratkan residual menjadi loss',
        text: 'Residual dikuadratkan agar error positif dan error besar mendapat penalti lebih besar. Kita gunakan mean squared error sebagai loss training.',
        formula: 'MSE = (1/n) Σ(yᵢ - (c + mxᵢ))²'
    },
    {
        title: 'Langkah 5 — Cari arah penurunan loss',
        text: 'Turunan parsial terhadap m dan c memberi tahu arah kemiringan loss. Gradient descent bergerak ke arah yang berlawanan dengan gradient.',
        formula: '∂MSE/∂m = (-2/n)Σxᵢ(yᵢ-ŷᵢ)\n∂MSE/∂c = (-2/n)Σ(yᵢ-ŷᵢ)'
    },
    {
        title: 'Langkah 6 — Update m dan c',
        text: 'Learning rate mengatur seberapa jauh kita bergerak. Setelah update, garis baru diharapkan mempunyai loss yang lebih kecil.',
        formula: 'm ← m - α(∂MSE/∂m)\nc ← c - α(∂MSE/∂c)'
    },
    {
        title: 'Langkah 7 — Ulangi berkali-kali',
        text: 'Satu kali update disebut satu epoch pada simulasi ini. Proses diulang sampai jumlah epoch tercapai atau perubahan sudah sangat kecil.',
        formula: 'prediksi → residual → gradient → update → ulangi'
    }
];

function formatNumber(value, digits = 6)
{
    if (value === null || value === undefined || !Number.isFinite(Number(value))) {
        return '-';
    }

    const n = Number(value);

    if (Math.abs(n) >= 1e6 || (Math.abs(n) > 0 && Math.abs(n) < 1e-5)) {
        return n.toExponential(4);
    }

    return n.toFixed(digits).replace(/0+$/, '').replace(/\.$/, '');
}

function setupCanvas(id)
{
    const canvas = document.getElementById(id);
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

function createDomain(extraStates = states)
{
    const xs = points.map(pt => Number(pt[0]));
    const ys = points.map(pt => Number(pt[1]));

    extraStates.forEach(state => {
        if (!state) {
            return;
        }

        xs.push(0, 10);
        ys.push(Number(state.startY), Number(state.endY));
    });

    let xMin = Math.min(...xs, 0);
    let xMax = Math.max(...xs, 10);
    let yMin = Math.min(...ys, 0);
    let yMax = Math.max(...ys, 1);
    const xSpan = Math.max(xMax - xMin, 1);
    const ySpan = Math.max(yMax - yMin, 1);

    xMin -= xSpan * 0.08;
    xMax += xSpan * 0.08;
    yMin -= ySpan * 0.12;
    yMax += ySpan * 0.12;

    return { xMin, xMax, yMin, yMax };
}

const domain = createDomain();
const graphMargin = { left: 58, right: 28, top: 28, bottom: 48 };

function mapX(x, width)
{
    const plotWidth = width - graphMargin.left - graphMargin.right;
    return graphMargin.left + ((x - domain.xMin) / (domain.xMax - domain.xMin)) * plotWidth;
}

function mapY(y, height)
{
    const plotHeight = height - graphMargin.top - graphMargin.bottom;
    return graphMargin.top + ((domain.yMax - y) / (domain.yMax - domain.yMin)) * plotHeight;
}

function drawBaseGraph(ctx, width, height)
{
    ctx.clearRect(0, 0, width, height);
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, width, height);

    const plotWidth = width - graphMargin.left - graphMargin.right;
    const plotHeight = height - graphMargin.top - graphMargin.bottom;

    ctx.strokeStyle = '#e2e8f0';
    ctx.lineWidth = 1;

    for (let i = 0; i <= 8; i++) {
        const x = graphMargin.left + (i / 8) * plotWidth;
        ctx.beginPath();
        ctx.moveTo(x, graphMargin.top);
        ctx.lineTo(x, height - graphMargin.bottom);
        ctx.stroke();
    }

    for (let i = 0; i <= 6; i++) {
        const y = graphMargin.top + (i / 6) * plotHeight;
        ctx.beginPath();
        ctx.moveTo(graphMargin.left, y);
        ctx.lineTo(width - graphMargin.right, y);
        ctx.stroke();
    }

    const xAxisY = mapY(0, height);
    const yAxisX = mapX(0, width);

    ctx.strokeStyle = '#64748b';
    ctx.lineWidth = 1.5;

    if (xAxisY >= graphMargin.top && xAxisY <= height - graphMargin.bottom) {
        ctx.beginPath();
        ctx.moveTo(graphMargin.left, xAxisY);
        ctx.lineTo(width - graphMargin.right, xAxisY);
        ctx.stroke();
    }

    if (yAxisX >= graphMargin.left && yAxisX <= width - graphMargin.right) {
        ctx.beginPath();
        ctx.moveTo(yAxisX, graphMargin.top);
        ctx.lineTo(yAxisX, height - graphMargin.bottom);
        ctx.stroke();
    }

    ctx.fillStyle = '#64748b';
    ctx.font = '12px Arial';
    ctx.fillText('x', width - graphMargin.right + 8, xAxisY - 5);
    ctx.fillText('y', yAxisX + 7, graphMargin.top + 12);
}

function drawDataset(ctx, width, height)
{
    points.forEach((pt, index) => {
        const x = mapX(Number(pt[0]), width);
        const y = mapY(Number(pt[1]), height);

        ctx.beginPath();
        ctx.arc(x, y, 6, 0, Math.PI * 2);
        ctx.fillStyle = '#7c3aed';
        ctx.fill();

        ctx.fillStyle = '#475569';
        ctx.font = '12px Arial';
        ctx.fillText(`P${index + 1} (${formatNumber(pt[0], 2)}, ${formatNumber(pt[1], 2)})`, x + 8, y - 8);
    });
}

function drawRegressionLine(ctx, width, height, state, color = '#2563eb', lineWidth = 3)
{
    if (!state) {
        return;
    }

    const x1 = domain.xMin;
    const x2 = domain.xMax;
    const y1 = Number(state.c) + Number(state.m) * x1;
    const y2 = Number(state.c) + Number(state.m) * x2;

    ctx.save();
    ctx.beginPath();
    ctx.rect(
        graphMargin.left,
        graphMargin.top,
        width - graphMargin.left - graphMargin.right,
        height - graphMargin.top - graphMargin.bottom
    );
    ctx.clip();
    ctx.strokeStyle = color;
    ctx.lineWidth = lineWidth;
    ctx.beginPath();
    ctx.moveTo(mapX(x1, width), mapY(y1, height));
    ctx.lineTo(mapX(x2, width), mapY(y2, height));
    ctx.stroke();
    ctx.restore();
}

function drawResiduals(ctx, width, height, state)
{
    if (!state) {
        return;
    }

    points.forEach(pt => {
        const x = Number(pt[0]);
        const y = Number(pt[1]);
        const yPred = Number(state.c) + Number(state.m) * x;
        const px = mapX(x, width);
        const py = mapY(y, height);
        const pyPred = mapY(yPred, height);

        ctx.save();
        ctx.setLineDash([6, 4]);
        ctx.strokeStyle = '#ef4444';
        ctx.lineWidth = 1.5;
        ctx.beginPath();
        ctx.moveTo(px, py);
        ctx.lineTo(px, pyPred);
        ctx.stroke();
        ctx.restore();

        ctx.beginPath();
        ctx.arc(px, pyPred, 4, 0, Math.PI * 2);
        ctx.fillStyle = '#f59e0b';
        ctx.fill();
    });
}

function drawArrow(ctx, x1, y1, x2, y2, color)
{
    const headLength = 9;
    const angle = Math.atan2(y2 - y1, x2 - x1);

    ctx.strokeStyle = color;
    ctx.fillStyle = color;
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.moveTo(x1, y1);
    ctx.lineTo(x2, y2);
    ctx.stroke();

    ctx.beginPath();
    ctx.moveTo(x2, y2);
    ctx.lineTo(x2 - headLength * Math.cos(angle - Math.PI / 6), y2 - headLength * Math.sin(angle - Math.PI / 6));
    ctx.lineTo(x2 - headLength * Math.cos(angle + Math.PI / 6), y2 - headLength * Math.sin(angle + Math.PI / 6));
    ctx.closePath();
    ctx.fill();
}

function drawIntuition()
{
    const { ctx, width, height } = setupCanvas('intuitionCanvas');
    drawBaseGraph(ctx, width, height);
    drawDataset(ctx, width, height);

    const initialState = states[0] || null;
    const firstUpdatedState = states[1] || initialState;

    if (intuitionStep >= 1) {
        drawRegressionLine(ctx, width, height, initialState, '#2563eb', 3);
    }

    if (intuitionStep >= 2) {
        drawResiduals(ctx, width, height, initialState);
    }

    if (intuitionStep >= 3 && initialState) {
        ctx.fillStyle = '#991b1b';
        ctx.font = 'bold 14px Arial';
        ctx.fillText(`MSE awal = ${formatNumber(initialState.mse, 6)}`, graphMargin.left + 10, graphMargin.top + 20);
    }

    if (intuitionStep >= 4 && initialState) {
        ctx.fillStyle = '#0f766e';
        ctx.font = 'bold 13px Arial';
        ctx.fillText(`∂MSE/∂m = ${formatNumber(initialState.grad_m, 5)}`, graphMargin.left + 10, graphMargin.top + 44);
        ctx.fillText(`∂MSE/∂c = ${formatNumber(initialState.grad_c, 5)}`, graphMargin.left + 10, graphMargin.top + 64);
    }

    if (intuitionStep >= 5 && firstUpdatedState) {
        drawRegressionLine(ctx, width, height, firstUpdatedState, '#16a34a', 3);

        const xLabel = Math.min(3.5, domain.xMax - 0.5);
        const oldY = initialState.c + initialState.m * xLabel;
        const newY = firstUpdatedState.c + firstUpdatedState.m * xLabel;
        drawArrow(
            ctx,
            mapX(xLabel, width),
            mapY(oldY, height),
            mapX(xLabel, width),
            mapY(newY, height),
            '#16a34a'
        );

        ctx.fillStyle = '#15803d';
        ctx.font = 'bold 13px Arial';
        ctx.fillText('garis setelah 1 update', graphMargin.left + 10, graphMargin.top + 88);
    }

    if (intuitionStep >= 6 && states.length > 1) {
        const previewIndexes = [
            Math.min(1, states.length - 1),
            Math.floor((states.length - 1) * 0.25),
            Math.floor((states.length - 1) * 0.5),
            Math.floor((states.length - 1) * 0.75),
            states.length - 1
        ];

        previewIndexes.forEach((index, i) => {
            drawRegressionLine(ctx, width, height, states[index], `rgba(37, 99, 235, ${0.18 + i * 0.16})`, 2);
        });
    }

    const step = intuitionSteps[intuitionStep];
    document.getElementById('intuitionTitle').innerHTML = step.title;
    document.getElementById('intuitionText').innerHTML = step.text;
    document.getElementById('intuitionFormula').innerHTML = step.formula.replace(/\n/g, '<br>');
}

function nextIntuition()
{
    if (intuitionStep < intuitionSteps.length - 1) {
        intuitionStep++;
        drawIntuition();
    }
}

function previousIntuition()
{
    if (intuitionStep > 0) {
        intuitionStep--;
        drawIntuition();
    }
}

function playIntuition()
{
    clearInterval(intuitionTimer);

    intuitionTimer = setInterval(() => {
        if (intuitionStep >= intuitionSteps.length - 1) {
            clearInterval(intuitionTimer);
            intuitionTimer = null;
            return;
        }

        nextIntuition();
    }, 1700);
}

function pauseIntuition()
{
    clearInterval(intuitionTimer);
    intuitionTimer = null;
}

function resetIntuition()
{
    pauseIntuition();
    intuitionStep = 0;
    drawIntuition();
}

function drawTraining(index)
{
    if (states.length === 0) {
        return;
    }

    currentEpochIndex = Math.max(0, Math.min(index, states.length - 1));

    const state = states[currentEpochIndex];
    const { ctx, width, height } = setupCanvas('trainingCanvas');

    drawBaseGraph(ctx, width, height);
    drawDataset(ctx, width, height);
    drawRegressionLine(ctx, width, height, state, '#2563eb', 3);
    drawResiduals(ctx, width, height, state);

    ctx.fillStyle = '#1e293b';
    ctx.font = 'bold 15px Arial';
    ctx.fillText(`Epoch ${state.epoch}`, graphMargin.left + 10, graphMargin.top + 20);

    document.getElementById('epochSlider').value = currentEpochIndex;
    document.getElementById('epochLabel').innerHTML = state.epoch;
    document.getElementById('statM').innerHTML = formatNumber(state.m, 8);
    document.getElementById('statC').innerHTML = formatNumber(state.c, 8);
    document.getElementById('statMSE').innerHTML = formatNumber(state.mse, 8);
    document.getElementById('statR2').innerHTML = formatNumber(state.r2, 8);

    if (state.epoch === 0) {
        document.getElementById('updateFormula').innerHTML =
            'Belum ada update. Ini adalah parameter awal: ' +
            `m = ${formatNumber(state.m, 6)}, c = ${formatNumber(state.c, 6)}.`;
    } else {
        document.getElementById('updateFormula').innerHTML =
            `m: ${formatNumber(state.previous_m, 6)} → ${formatNumber(state.m, 6)} ` +
            `(Δm = ${formatNumber(state.delta_m, 6)})<br>` +
            `c: ${formatNumber(state.previous_c, 6)} → ${formatNumber(state.c, 6)} ` +
            `(Δc = ${formatNumber(state.delta_c, 6)})`;
    }

    document.getElementById('gradientInfo').innerHTML =
        `∂MSE/∂m = <strong>${formatNumber(state.grad_m, 8)}</strong><br>` +
        `∂MSE/∂c = <strong>${formatNumber(state.grad_c, 8)}</strong><br>` +
        `Learning rate α = <strong>${formatNumber(learningRate, 8)}</strong>`;

    drawLossChart();
}

function nextEpoch()
{
    if (currentEpochIndex < states.length - 1) {
        drawTraining(currentEpochIndex + 1);
    }
}

function previousEpoch()
{
    if (currentEpochIndex > 0) {
        drawTraining(currentEpochIndex - 1);
    }
}

function playTraining()
{
    if (states.length <= 1) {
        return;
    }

    clearInterval(trainingTimer);

    const stride = Math.max(1, Math.floor(states.length / 180));

    trainingTimer = setInterval(() => {
        if (currentEpochIndex >= states.length - 1) {
            clearInterval(trainingTimer);
            trainingTimer = null;
            return;
        }

        drawTraining(Math.min(states.length - 1, currentEpochIndex + stride));
    }, 70);
}

function pauseTraining()
{
    clearInterval(trainingTimer);
    trainingTimer = null;
}

function resetTraining()
{
    pauseTraining();
    drawTraining(0);
}

function drawLossChart()
{
    const { ctx, width, height } = setupCanvas('lossCanvas');

    ctx.clearRect(0, 0, width, height);
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, width, height);

    if (states.length === 0) {
        return;
    }

    const margin = { left: 65, right: 25, top: 20, bottom: 42 };
    const plotWidth = width - margin.left - margin.right;
    const plotHeight = height - margin.top - margin.bottom;
    const maxMSE = Math.max(...states.map(state => Number(state.mse)), 1e-12);

    function getX(index)
    {
        if (states.length === 1) {
            return margin.left;
        }

        return margin.left + (index / (states.length - 1)) * plotWidth;
    }

    function getY(value)
    {
        return margin.top + (1 - value / maxMSE) * plotHeight;
    }

    ctx.strokeStyle = '#e2e8f0';
    ctx.fillStyle = '#64748b';
    ctx.font = '11px Arial';
    ctx.textAlign = 'right';

    for (let i = 0; i <= 4; i++) {
        const ratio = i / 4;
        const y = margin.top + ratio * plotHeight;
        const value = maxMSE * (1 - ratio);

        ctx.beginPath();
        ctx.moveTo(margin.left, y);
        ctx.lineTo(width - margin.right, y);
        ctx.stroke();
        ctx.fillText(formatNumber(value, 4), margin.left - 8, y + 3);
    }

    ctx.strokeStyle = '#64748b';
    ctx.lineWidth = 1.3;
    ctx.beginPath();
    ctx.moveTo(margin.left, margin.top);
    ctx.lineTo(margin.left, height - margin.bottom);
    ctx.lineTo(width - margin.right, height - margin.bottom);
    ctx.stroke();

    ctx.beginPath();

    for (let i = 0; i <= currentEpochIndex; i++) {
        const x = getX(i);
        const y = getY(Number(states[i].mse));

        if (i === 0) {
            ctx.moveTo(x, y);
        } else {
            ctx.lineTo(x, y);
        }
    }

    ctx.strokeStyle = '#2563eb';
    ctx.lineWidth = 2.5;
    ctx.stroke();

    const activeX = getX(currentEpochIndex);
    const activeY = getY(Number(states[currentEpochIndex].mse));

    ctx.beginPath();
    ctx.arc(activeX, activeY, 5, 0, Math.PI * 2);
    ctx.fillStyle = '#f59e0b';
    ctx.fill();

    ctx.fillStyle = '#334155';
    ctx.font = '12px Arial';
    ctx.textAlign = 'center';
    ctx.fillText('Epoch', margin.left + plotWidth / 2, height - 12);

    ctx.save();
    ctx.translate(16, margin.top + plotHeight / 2);
    ctx.rotate(-Math.PI / 2);
    ctx.fillText('MSE', 0, 0);
    ctx.restore();
}

document.getElementById('epochSlider').addEventListener('input', event => {
    pauseTraining();
    drawTraining(Number(event.target.value));
});

window.addEventListener('resize', () => {
    drawIntuition();
    drawTraining(currentEpochIndex);
});

drawIntuition();

if (states.length > 0) {
    drawTraining(0);
}
</script>
</main>
