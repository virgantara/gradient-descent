<?php

require_once __DIR__ . '/../train_multiple.php';

$lr = isset($_GET['lr']) && is_numeric($_GET['lr']) ? (float) $_GET['lr'] : 0.01;
$epoch = isset($_GET['epoch']) ? max(1, min(1000, (int) $_GET['epoch'])) : 200;
$initialM1 = isset($_GET['m1']) && is_numeric($_GET['m1']) ? (float) $_GET['m1'] : 0.0;
$initialM2 = isset($_GET['m2']) && is_numeric($_GET['m2']) ? (float) $_GET['m2'] : 0.0;
$initialC = isset($_GET['c']) && is_numeric($_GET['c']) ? (float) $_GET['c'] : 0.0;

$points = [
    [1.0, 1.0, 3.2],
    [2.0, 1.0, 4.7],
    [3.0, 1.0, 6.1],
    [1.0, 2.0, 4.0],
    [2.0, 2.0, 5.6],
    [3.0, 2.0, 7.0],
    [1.0, 3.0, 4.9],
    [2.0, 3.0, 6.3],
    [3.0, 3.0, 7.9],
];

$training = trainMultipleLinearRegression(
    $points,
    $lr,
    $epoch,
    $initialM1,
    $initialM2,
    $initialC
);

$states = $training['states'];
$message = $training['message'];
$lastState = !empty($states) ? $states[count($states) - 1] : null;
?>

<script src="https://cdn.plot.ly/plotly-2.35.2.min.js"></script>

<style>
.multiple-form {
    grid-template-columns: repeat(5, minmax(0, 1fr)) auto;
}

.ml3d-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.7fr) minmax(280px, .8fr);
    gap: 20px;
    align-items: start;
}

.ml3d-plot {
    width: 100%;
    height: 560px;
    border: 1px solid var(--border, #e2e8f0);
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
}

.ml3d-panel {
    display: grid;
    gap: 12px;
}

.ml3d-stat {
    padding: 14px;
    border: 1px solid var(--border, #e2e8f0);
    border-radius: 12px;
    background: #f8fafc;
}

.ml3d-stat-label {
    margin-bottom: 6px;
    color: #64748b;
    font-size: 13px;
}

.ml3d-stat-value {
    font-size: 18px;
    font-weight: 700;
    overflow-wrap: anywhere;
}

.ml3d-explanation {
    padding: 16px;
    border-radius: 12px;
    background: #eff6ff;
    color: #1e3a8a;
    line-height: 1.65;
}

.ml3d-formula {
    margin-top: 10px;
    padding: 12px 14px;
    border-radius: 9px;
    background: #fff;
    font-family: "Courier New", monospace;
    color: #1d4ed8;
    overflow-x: auto;
}

.ml3d-controls {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 16px;
}

.ml3d-controls button {
    min-height: 40px;
    padding: 9px 14px;
    border: 0;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 700;
}

.ml3d-btn-primary {
    background: #2563eb;
    color: #fff;
}

.ml3d-btn-secondary {
    background: #e2e8f0;
    color: #334155;
}

.ml3d-speed {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-left: auto;
    color: #64748b;
    font-size: 13px;
}

.ml3d-speed input {
    width: 120px;
}

.ml3d-progress {
    margin-top: 14px;
}

.ml3d-progress input {
    width: 100%;
}

.ml3d-loss {
    width: 100%;
    height: 320px;
}

.ml3d-warning {
    margin-bottom: 20px;
    padding: 14px 16px;
    border: 1px solid #fecaca;
    border-radius: 12px;
    background: #fef2f2;
    color: #991b1b;
    line-height: 1.6;
}

.ml3d-intuition-steps {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    margin-top: 16px;
}

.ml3d-step-chip {
    padding: 10px;
    border: 1px solid #e2e8f0;
    border-radius: 9px;
    background: #fff;
    color: #64748b;
    font-size: 12px;
    text-align: center;
}

.ml3d-step-chip.active {
    border-color: #93c5fd;
    background: #eff6ff;
    color: #1d4ed8;
    font-weight: 700;
}

@media (max-width: 1000px) {
    .multiple-form {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .multiple-form .input-action {
        grid-column: 1 / -1;
    }

    .ml3d-grid {
        grid-template-columns: 1fr;
    }

    .ml3d-plot {
        height: 480px;
    }
}

@media (max-width: 700px) {
    .multiple-form {
        grid-template-columns: 1fr;
    }

    .multiple-form .input-action {
        grid-column: auto;
    }

    .ml3d-plot {
        height: 400px;
    }

    .ml3d-intuition-steps {
        grid-template-columns: repeat(2, 1fr);
    }

    .ml3d-speed {
        width: 100%;
        margin-left: 0;
    }
}
</style>

<main class="container">
    <div class="page-header">
        <h1>Regresi Linier Multiple Variabel — 2 Fitur</h1>
        <p>
            Untuk dua variabel input, model tidak lagi berupa garis 2D.
            Model membentuk <strong>bidang regresi 3D</strong> dengan sumbu
            x₁, x₂, dan y.
        </p>
    </div>

    <div class="card">
        <h2>Model Dasar</h2>

        <div class="formula">
            ŷ = c + m₁x₁ + m₂x₂
        </div>

        <p>
            Setiap titik data memiliki koordinat
            <strong>(x₁, x₂, y)</strong>.
            Model mencoba menggeser dan memiringkan bidang prediksi agar
            residual terhadap semua titik menjadi sekecil mungkin.
        </p>
    </div>

    <div class="card">
        <h2>Parameter Training</h2>

        <form method="GET" class="input-form multiple-form">
            <div class="input-group">
                <label for="lr">Learning Rate</label>
                <input type="number" step="any" id="lr" name="lr" value="<?= htmlspecialchars((string) $lr) ?>" required>
            </div>

            <div class="input-group">
                <label for="epoch">Epoch</label>
                <input type="number" min="1" max="1000" id="epoch" name="epoch" value="<?= $epoch ?>" required>
            </div>

            <div class="input-group">
                <label for="m1">Initial m₁</label>
                <input type="number" step="any" id="m1" name="m1" value="<?= htmlspecialchars((string) $initialM1) ?>" required>
            </div>

            <div class="input-group">
                <label for="m2">Initial m₂</label>
                <input type="number" step="any" id="m2" name="m2" value="<?= htmlspecialchars((string) $initialM2) ?>" required>
            </div>

            <div class="input-group">
                <label for="c">Initial c</label>
                <input type="number" step="any" id="c" name="c" value="<?= htmlspecialchars((string) $initialC) ?>" required>
            </div>

            <div class="input-action">
                <button type="submit" class="btn btn-primary">Train & Visualisasikan</button>
            </div>
        </form>
    </div>

    <?php if ($message): ?>
        <div class="ml3d-warning">
            <strong>Perhatian:</strong>
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <h2>1) Animasi Intuisi Awal</h2>

        <p>
            Ikuti ide dasarnya sebelum melihat seluruh epoch training.
        </p>

        <div class="ml3d-grid">
            <div>
                <div id="intuition3d" class="ml3d-plot"></div>

                <div class="ml3d-controls">
                    <button type="button" class="ml3d-btn-secondary" onclick="prevIntuition()">← Sebelumnya</button>
                    <button type="button" class="ml3d-btn-primary" onclick="nextIntuition()">Berikutnya →</button>
                    <button type="button" class="ml3d-btn-primary" onclick="playIntuition()">▶ Play</button>
                    <button type="button" class="ml3d-btn-secondary" onclick="pauseIntuition()">⏸ Pause</button>
                    <button type="button" class="ml3d-btn-secondary" onclick="resetIntuition()">↺ Reset</button>
                </div>

                <div class="ml3d-intuition-steps">
                    <div class="ml3d-step-chip" data-intuition-chip="0">1. Titik Data</div>
                    <div class="ml3d-step-chip" data-intuition-chip="1">2. Plane Awal</div>
                    <div class="ml3d-step-chip" data-intuition-chip="2">3. Residual</div>
                    <div class="ml3d-step-chip" data-intuition-chip="3">4. MSE</div>
                    <div class="ml3d-step-chip" data-intuition-chip="4">5. Gradient</div>
                    <div class="ml3d-step-chip" data-intuition-chip="5">6. Update</div>
                    <div class="ml3d-step-chip" data-intuition-chip="6">7. Plane Bergerak</div>
                    <div class="ml3d-step-chip" data-intuition-chip="7">8. Ulangi</div>
                </div>
            </div>

            <div class="ml3d-panel">
                <div class="ml3d-explanation">
                    <strong id="intuitionTitle"></strong>
                    <div id="intuitionText" style="margin-top:8px;"></div>
                    <div class="ml3d-formula" id="intuitionFormula"></div>
                </div>

                <div class="ml3d-stat">
                    <div class="ml3d-stat-label">Initial m₁</div>
                    <div class="ml3d-stat-value"><?= round($initialM1, 6) ?></div>
                </div>

                <div class="ml3d-stat">
                    <div class="ml3d-stat-label">Initial m₂</div>
                    <div class="ml3d-stat-value"><?= round($initialM2, 6) ?></div>
                </div>

                <div class="ml3d-stat">
                    <div class="ml3d-stat-label">Initial c</div>
                    <div class="ml3d-stat-value"><?= round($initialC, 6) ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>2) Gradient Descent Langkah demi Langkah</h2>

        <div class="ml3d-grid">
            <div>
                <div id="training3d" class="ml3d-plot"></div>

                <div class="ml3d-controls">
                    <button type="button" class="ml3d-btn-secondary" onclick="previousEpoch()">← Sebelumnya</button>
                    <button type="button" class="ml3d-btn-primary" onclick="nextEpoch()">Berikutnya →</button>
                    <button type="button" class="ml3d-btn-primary" onclick="playTraining()">▶ Play</button>
                    <button type="button" class="ml3d-btn-secondary" onclick="pauseTraining()">⏸ Pause</button>
                    <button type="button" class="ml3d-btn-secondary" onclick="resetTraining()">↺ Reset</button>

                    <label class="ml3d-speed">
                        Kecepatan
                        <input type="range" id="speedRange" min="50" max="1000" step="50" value="250">
                    </label>
                </div>

                <div class="ml3d-progress">
                    <input
                        type="range"
                        id="epochRange"
                        min="0"
                        max="<?= max(0, count($states) - 1) ?>"
                        value="0"
                        oninput="goToEpoch(Number(this.value))"
                    >
                </div>
            </div>

            <div class="ml3d-panel">
                <div class="ml3d-stat">
                    <div class="ml3d-stat-label">Epoch</div>
                    <div class="ml3d-stat-value" id="statEpoch">0</div>
                </div>

                <div class="ml3d-stat">
                    <div class="ml3d-stat-label">m₁</div>
                    <div class="ml3d-stat-value" id="statM1">-</div>
                </div>

                <div class="ml3d-stat">
                    <div class="ml3d-stat-label">m₂</div>
                    <div class="ml3d-stat-value" id="statM2">-</div>
                </div>

                <div class="ml3d-stat">
                    <div class="ml3d-stat-label">c</div>
                    <div class="ml3d-stat-value" id="statC">-</div>
                </div>

                <div class="ml3d-stat">
                    <div class="ml3d-stat-label">MSE</div>
                    <div class="ml3d-stat-value" id="statMSE">-</div>
                </div>

                <div class="ml3d-stat">
                    <div class="ml3d-stat-label">R²</div>
                    <div class="ml3d-stat-value" id="statR2">-</div>
                </div>

                <div class="ml3d-explanation">
                    <strong>Update parameter</strong>
                    <div class="ml3d-formula" id="updateFormula">
                        Epoch 0: belum ada update.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>3) Kurva Konvergensi Loss</h2>
        <div id="lossChart" class="ml3d-loss"></div>
    </div>

    <?php if ($lastState): ?>
        <div class="card">
            <h2>Model Akhir</h2>

            <div class="formula">
                ŷ =
                <?= round($lastState['c'], 6) ?>
                + <?= round($lastState['m1'], 6) ?>x₁
                + <?= round($lastState['m2'], 6) ?>x₂
            </div>

            <p>
                MSE akhir:
                <strong><?= round($lastState['mse'], 8) ?></strong>,
                R²:
                <strong><?= round($lastState['r2'], 8) ?></strong>.
            </p>
        </div>
    <?php endif; ?>
</main>

<script>
const mlPoints = <?= json_encode($points, JSON_NUMERIC_CHECK) ?>;
const trainingStates = <?= json_encode($states, JSON_NUMERIC_CHECK) ?>;
const learningRate = <?= json_encode($lr, JSON_NUMERIC_CHECK) ?>;

let intuitionIndex = 0;
let intuitionTimer = null;
let trainingIndex = 0;
let trainingTimer = null;

const intuitionSteps = [
    {
        title: 'Langkah 1 — Data hidup di ruang 3D',
        text: 'Karena ada dua fitur input, setiap observasi mempunyai koordinat (x₁, x₂, y). Jadi data tidak lagi cukup divisualisasikan dengan scatter 2D.',
        formula: 'Pᵢ = (x₁ᵢ, x₂ᵢ, yᵢ)'
    },
    {
        title: 'Langkah 2 — Model adalah sebuah bidang',
        text: 'Pada regresi linier dua variabel, semua prediksi membentuk sebuah plane. Nilai m₁ dan m₂ mengatur kemiringan bidang, sedangkan c menggesernya naik atau turun.',
        formula: 'ŷ = c + m₁x₁ + m₂x₂'
    },
    {
        title: 'Langkah 3 — Residual adalah jarak vertikal',
        text: 'Untuk setiap titik data, model menghasilkan ŷ. Selisih antara y asli dan ŷ disebut residual. Garis vertikal pada grafik memperlihatkan residual tersebut.',
        formula: 'residualᵢ = yᵢ - ŷᵢ'
    },
    {
        title: 'Langkah 4 — Gabungkan error menjadi MSE',
        text: 'Semua residual dikuadratkan agar tanda positif dan negatif tidak saling menghapus, lalu dirata-ratakan.',
        formula: 'MSE = (1/n) Σ(yᵢ - ŷᵢ)²'
    },
    {
        title: 'Langkah 5 — Hitung gradient',
        text: 'Gradient menunjukkan arah perubahan MSE terhadap m₁, m₂, dan c. Kita ingin bergerak ke arah yang menurunkan MSE.',
        formula: '∂MSE/∂m₁,  ∂MSE/∂m₂,  ∂MSE/∂c'
    },
    {
        title: 'Langkah 6 — Update parameter',
        text: 'Setiap parameter dikurangi learning rate dikali gradient-nya.',
        formula: 'θ ← θ - α∇MSE'
    },
    {
        title: 'Langkah 7 — Plane bergerak',
        text: 'Setelah parameter berubah, bidang prediksi ikut bergeser dan berputar. Tujuannya membuat residual keseluruhan semakin kecil.',
        formula: 'm₁, m₂, c berubah → plane baru'
    },
    {
        title: 'Langkah 8 — Ulangi sampai konvergen',
        text: 'Proses residual → MSE → gradient → update diulang untuk banyak epoch hingga perubahan loss menjadi kecil.',
        formula: 'epoch 1 → 2 → 3 → ...'
    }
];

function formatNumber(value, digits = 6)
{
    const n = Number(value);

    if (!Number.isFinite(n)) {
        return '-';
    }

    if (Math.abs(n) >= 1e6 || (Math.abs(n) > 0 && Math.abs(n) < 1e-5)) {
        return n.toExponential(4);
    }

    return n.toFixed(digits).replace(/0+$/, '').replace(/\.$/, '');
}

function getBounds()
{
    const x1Values = mlPoints.map(point => Number(point[0]));
    const x2Values = mlPoints.map(point => Number(point[1]));
    const yValues = mlPoints.map(point => Number(point[2]));

    return {
        x1Min: Math.min(...x1Values),
        x1Max: Math.max(...x1Values),
        x2Min: Math.min(...x2Values),
        x2Max: Math.max(...x2Values),
        yMin: Math.min(...yValues),
        yMax: Math.max(...yValues)
    };
}

function createPlane(state)
{
    const bounds = getBounds();
    const pad1 = Math.max((bounds.x1Max - bounds.x1Min) * 0.15, 0.3);
    const pad2 = Math.max((bounds.x2Max - bounds.x2Min) * 0.15, 0.3);

    const x1Grid = [
        bounds.x1Min - pad1,
        bounds.x1Min + (bounds.x1Max - bounds.x1Min) * 0.25,
        bounds.x1Min + (bounds.x1Max - bounds.x1Min) * 0.50,
        bounds.x1Min + (bounds.x1Max - bounds.x1Min) * 0.75,
        bounds.x1Max + pad1
    ];

    const x2Grid = [
        bounds.x2Min - pad2,
        bounds.x2Min + (bounds.x2Max - bounds.x2Min) * 0.25,
        bounds.x2Min + (bounds.x2Max - bounds.x2Min) * 0.50,
        bounds.x2Min + (bounds.x2Max - bounds.x2Min) * 0.75,
        bounds.x2Max + pad2
    ];

    const z = x2Grid.map(x2 => {
        return x1Grid.map(x1 => {
            return Number(state.c) + Number(state.m1) * x1 + Number(state.m2) * x2;
        });
    });

    return { x1Grid, x2Grid, z };
}

function createPointTrace()
{
    return {
        type: 'scatter3d',
        mode: 'markers',
        name: 'Data aktual',
        x: mlPoints.map(point => point[0]),
        y: mlPoints.map(point => point[1]),
        z: mlPoints.map(point => point[2]),
        marker: {
            size: 6,
            color: '#7c3aed'
        },
        hovertemplate: 'x₁=%{x}<br>x₂=%{y}<br>y=%{z}<extra></extra>'
    };
}

function createPlaneTrace(state, opacity = 0.65)
{
    const plane = createPlane(state);

    return {
        type: 'surface',
        name: 'Prediction plane',
        x: plane.x1Grid,
        y: plane.x2Grid,
        z: plane.z,
        opacity,
        showscale: false,
        colorscale: [
            [0, '#bfdbfe'],
            [1, '#2563eb']
        ],
        hovertemplate: 'x₁=%{x}<br>x₂=%{y}<br>ŷ=%{z:.4f}<extra></extra>'
    };
}

function createResidualTrace(state)
{
    const x = [];
    const y = [];
    const z = [];

    state.predictions.forEach(item => {
        x.push(item.x1, item.x1, null);
        y.push(item.x2, item.x2, null);
        z.push(item.y, item.y_pred, null);
    });

    return {
        type: 'scatter3d',
        mode: 'lines',
        name: 'Residual',
        x,
        y,
        z,
        line: {
            color: '#ef4444',
            width: 5
        },
        hoverinfo: 'skip'
    };
}

function base3dLayout(title)
{
    return {
        title: {
            text: title,
            font: { size: 15 }
        },
        margin: { l: 0, r: 0, t: 45, b: 0 },
        scene: {
            xaxis: { title: 'x₁' },
            yaxis: { title: 'x₂' },
            zaxis: { title: 'y / ŷ' },
            camera: {
                eye: { x: 1.45, y: 1.45, z: 1.15 }
            },
            aspectmode: 'cube'
        },
        legend: {
            x: 0,
            y: 1
        }
    };
}

function renderIntuition()
{
    if (trainingStates.length === 0) {
        return;
    }

    const initialState = trainingStates[0];
    const nextState = trainingStates[Math.min(1, trainingStates.length - 1)];
    const traces = [createPointTrace()];

    if (intuitionIndex >= 1) {
        const planeState = intuitionIndex >= 6 ? nextState : initialState;
        traces.unshift(createPlaneTrace(planeState));
    }

    if (intuitionIndex >= 2) {
        const residualState = intuitionIndex >= 6 ? nextState : initialState;
        traces.push(createResidualTrace(residualState));
    }

    Plotly.react(
        'intuition3d',
        traces,
        base3dLayout('Intuisi Regresi Linier 2 Variabel'),
        {
            responsive: true,
            displaylogo: false
        }
    );

    const step = intuitionSteps[intuitionIndex];

    document.getElementById('intuitionTitle').textContent = step.title;
    document.getElementById('intuitionText').textContent = step.text;
    document.getElementById('intuitionFormula').textContent = step.formula;

    document.querySelectorAll('[data-intuition-chip]').forEach(chip => {
        chip.classList.toggle(
            'active',
            Number(chip.dataset.intuitionChip) === intuitionIndex
        );
    });
}

function nextIntuition()
{
    if (intuitionIndex < intuitionSteps.length - 1) {
        intuitionIndex++;
        renderIntuition();
    }
}

function prevIntuition()
{
    if (intuitionIndex > 0) {
        intuitionIndex--;
        renderIntuition();
    }
}

function playIntuition()
{
    pauseIntuition();

    intuitionTimer = setInterval(() => {
        if (intuitionIndex >= intuitionSteps.length - 1) {
            pauseIntuition();
            return;
        }

        nextIntuition();
    }, 1600);
}

function pauseIntuition()
{
    if (intuitionTimer !== null) {
        clearInterval(intuitionTimer);
        intuitionTimer = null;
    }
}

function resetIntuition()
{
    pauseIntuition();
    intuitionIndex = 0;
    renderIntuition();
}

function renderTraining(index)
{
    if (trainingStates.length === 0) {
        return;
    }

    trainingIndex = Math.max(0, Math.min(index, trainingStates.length - 1));

    const state = trainingStates[trainingIndex];

    Plotly.react(
        'training3d',
        [
            createPlaneTrace(state),
            createPointTrace(),
            createResidualTrace(state)
        ],
        base3dLayout('Prediction Plane — Epoch ' + state.epoch),
        {
            responsive: true,
            displaylogo: false
        }
    );

    document.getElementById('statEpoch').textContent = state.epoch;
    document.getElementById('statM1').textContent = formatNumber(state.m1);
    document.getElementById('statM2').textContent = formatNumber(state.m2);
    document.getElementById('statC').textContent = formatNumber(state.c);
    document.getElementById('statMSE').textContent = formatNumber(state.mse, 8);
    document.getElementById('statR2').textContent = formatNumber(state.r2, 8);
    document.getElementById('epochRange').value = trainingIndex;

    if (state.epoch === 0) {
        document.getElementById('updateFormula').textContent =
            'Epoch 0: parameter masih menggunakan nilai awal.';
    } else {
        document.getElementById('updateFormula').innerHTML =
            'm₁ = ' + formatNumber(state.prev_m1) +
            ' - (' + formatNumber(learningRate) + ')(' + formatNumber(state.grad_m1) + ')' +
            ' = <strong>' + formatNumber(state.m1) + '</strong><br>' +

            'm₂ = ' + formatNumber(state.prev_m2) +
            ' - (' + formatNumber(learningRate) + ')(' + formatNumber(state.grad_m2) + ')' +
            ' = <strong>' + formatNumber(state.m2) + '</strong><br>' +

            'c = ' + formatNumber(state.prev_c) +
            ' - (' + formatNumber(learningRate) + ')(' + formatNumber(state.grad_c) + ')' +
            ' = <strong>' + formatNumber(state.c) + '</strong>';
    }

    renderLossChart();
}

function renderLossChart()
{
    if (trainingStates.length === 0) {
        return;
    }

    const visible = trainingStates.slice(0, trainingIndex + 1);

    Plotly.react(
        'lossChart',
        [{
            type: 'scatter',
            mode: 'lines+markers',
            name: 'MSE',
            x: visible.map(state => state.epoch),
            y: visible.map(state => state.mse),
            line: { color: '#2563eb', width: 3 },
            marker: { size: 5 }
        }],
        {
            margin: { l: 65, r: 20, t: 20, b: 55 },
            xaxis: { title: 'Epoch' },
            yaxis: { title: 'MSE', rangemode: 'tozero' },
            showlegend: false
        },
        {
            responsive: true,
            displaylogo: false
        }
    );
}

function nextEpoch()
{
    if (trainingIndex < trainingStates.length - 1) {
        renderTraining(trainingIndex + 1);
    }
}

function previousEpoch()
{
    if (trainingIndex > 0) {
        renderTraining(trainingIndex - 1);
    }
}

function goToEpoch(index)
{
    pauseTraining();
    renderTraining(index);
}

function playTraining()
{
    pauseTraining();

    const speed = Number(document.getElementById('speedRange').value) || 250;

    trainingTimer = setInterval(() => {
        if (trainingIndex >= trainingStates.length - 1) {
            pauseTraining();
            return;
        }

        nextEpoch();
    }, speed);
}

function pauseTraining()
{
    if (trainingTimer !== null) {
        clearInterval(trainingTimer);
        trainingTimer = null;
    }
}

function resetTraining()
{
    pauseTraining();
    renderTraining(0);
}

renderIntuition();
renderTraining(0);
</script>