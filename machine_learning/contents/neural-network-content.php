<?php

require_once __DIR__ . '/../train_neural_network.php';

$learningRate = isset($_GET['lr']) && is_numeric($_GET['lr']) ? (float) $_GET['lr'] : 0.1;
$epochs = isset($_GET['epochs']) ? max(10, min(5000, (int) $_GET['epochs'])) : 1000;
$seed = isset($_GET['seed']) ? (int) $_GET['seed'] : 42;
$recordEvery = isset($_GET['record']) ? max(1, min(100, (int) $_GET['record'])) : 10;

/*
 * Dataset mengikuti contoh sumber:
 * Weight (minus 135), Height (minus 66), Gender
 * Female = 1, Male = 0
 */
$names = ['Alice', 'Bob', 'Charlie', 'Diana'];

$data = [
    [-2, -1],
    [25, 6],
    [17, 4],
    [-15, -6],
];

$labels = [1, 0, 0, 1];

$training = nnTrain(
    $data,
    $labels,
    $learningRate,
    $epochs,
    $seed,
    $recordEvery
);

$states = $training['states'];
$finalEvaluation = $training['evaluation'];
$finalParams = $training['params'];

/*
 * Worked examples from the source.
 */
$singleNeuronWeights = [0, 1];
$singleNeuronBias = 4;
$singleNeuronInput = [2, 3];
$singleNeuronZ = ($singleNeuronWeights[0] * $singleNeuronInput[0])
    + ($singleNeuronWeights[1] * $singleNeuronInput[1])
    + $singleNeuronBias;
$singleNeuronOutput = nnSigmoid($singleNeuronZ);

$feedforwardParams = [
    'w1' => 0,
    'w2' => 1,
    'w3' => 0,
    'w4' => 1,
    'w5' => 0,
    'w6' => 1,
    'b1' => 0,
    'b2' => 0,
    'b3' => 0,
];

$feedforwardExample = nnFeedforward([2, 3], $feedforwardParams);

$aliceExampleParams = [
    'w1' => 1,
    'w2' => 1,
    'w3' => 1,
    'w4' => 1,
    'w5' => 1,
    'w6' => 1,
    'b1' => 0,
    'b2' => 0,
    'b3' => 0,
];

$aliceBackprop = nnBackpropOne([-2, -1], 1, $aliceExampleParams);
?>

<style>
.nn-layout {
    display: grid;
    grid-template-columns: minmax(0, 1.65fr) minmax(280px, .75fr);
    gap: 20px;
    align-items: start;
}

.nn-stage {
    position: relative;
    min-height: 460px;
    overflow: hidden;
    border: 1px solid var(--border);
    border-radius: 14px;
    background:
        radial-gradient(circle at 20% 20%, rgba(37, 99, 235, .05), transparent 28%),
        linear-gradient(180deg, #fff, #f8fafc);
}

.nn-stage svg {
    display: block;
    width: 100%;
    height: 460px;
}

.nn-node {
    transition: opacity .25s ease, filter .25s ease, transform .25s ease;
    transform-box: fill-box;
    transform-origin: center;
}

.nn-node circle {
    fill: #fff;
    stroke: #94a3b8;
    stroke-width: 2;
}

.nn-node text {
    fill: #334155;
    font-size: 14px;
    font-weight: 700;
    text-anchor: middle;
    dominant-baseline: middle;
}

.nn-node.active circle {
    stroke: #2563eb;
    stroke-width: 4;
    filter: drop-shadow(0 0 8px rgba(37, 99, 235, .28));
}

.nn-node.active {
    transform: scale(1.06);
}

.nn-link {
    stroke: #cbd5e1;
    stroke-width: 2;
    opacity: .65;
    transition: stroke .25s ease, stroke-width .25s ease, opacity .25s ease;
}

.nn-link.active {
    stroke: #2563eb;
    stroke-width: 4;
    opacity: 1;
}

.nn-link.backward {
    stroke: #dc2626;
}

.nn-layer-label {
    fill: #64748b;
    font-size: 13px;
    font-weight: 700;
    text-anchor: middle;
}

.nn-value {
    fill: #0f172a;
    font-size: 12px;
    text-anchor: middle;
}

.nn-panel {
    display: grid;
    gap: 12px;
}

.nn-callout {
    padding: 16px;
    border: 1px solid #bfdbfe;
    border-radius: 12px;
    background: #eff6ff;
    color: #1e3a8a;
    line-height: 1.65;
}

.nn-formula {
    margin-top: 10px;
    padding: 12px 14px;
    overflow-x: auto;
    border-radius: 9px;
    background: #fff;
    color: #1d4ed8;
    font-family: "Courier New", monospace;
    line-height: 1.65;
}

.nn-stat {
    padding: 14px;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: #f8fafc;
}

.nn-stat-label {
    margin-bottom: 6px;
    color: #64748b;
    font-size: 13px;
}

.nn-stat-value {
    font-size: 18px;
    font-weight: 700;
    overflow-wrap: anywhere;
}

.nn-controls {
    display: flex;
    flex-wrap: wrap;
    gap: 9px;
    margin-top: 16px;
}

.nn-btn {
    min-height: 40px;
    padding: 9px 14px;
    border: 0;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 700;
}

.nn-btn-primary {
    background: #2563eb;
    color: #fff;
}

.nn-btn-secondary {
    background: #e2e8f0;
    color: #334155;
}

.nn-step-row {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 8px;
    margin-top: 14px;
}

.nn-step-chip {
    padding: 9px 8px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #fff;
    color: #64748b;
    font-size: 11px;
    text-align: center;
}

.nn-step-chip.active {
    border-color: #93c5fd;
    background: #eff6ff;
    color: #1d4ed8;
    font-weight: 700;
}

.nn-table-wrap {
    overflow-x: auto;
}

.nn-table {
    width: 100%;
    border-collapse: collapse;
}

.nn-table th,
.nn-table td {
    padding: 10px 12px;
    border-bottom: 1px solid #e2e8f0;
    text-align: right;
    white-space: nowrap;
}

.nn-table th:first-child,
.nn-table td:first-child {
    text-align: left;
}

.nn-table th {
    background: #f8fafc;
}

.nn-loss-canvas {
    width: 100%;
    height: 330px;
    display: block;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: #fff;
}

.nn-training-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(280px, .8fr);
    gap: 20px;
}

.nn-weight-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}

.nn-training-range {
    width: 100%;
    margin-top: 14px;
}

.nn-training-visual {
    margin-bottom: 16px;
}

.nn-training-stage {
    min-height: 320px;
}

.nn-training-stage svg {
    height: 320px;
}

.nn-weight-label {
    transition: fill .2s ease, transform .2s ease, opacity .2s ease;
}

.nn-weight-label.changed,
.nn-bias-label.changed {
    fill: #ea580c;
    animation: nnPulse .45s ease;
}

.nn-weight-delta {
    fill: #0f172a;
    font-size: 10px;
    text-anchor: middle;
    dominant-baseline: middle;
    pointer-events: none;
}

.nn-weight-delta.up {
    fill: #16a34a;
}

.nn-weight-delta.down {
    fill: #dc2626;
}

.nn-training-note {
    margin-top: 10px;
    color: #64748b;
    font-size: 13px;
    line-height: 1.55;
}

@keyframes nnPulse {
    0% {
        transform: scale(1);
        opacity: .7;
    }
    50% {
        transform: scale(1.18);
        opacity: 1;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.nn-mini-neuron {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
    gap: 16px;
    padding: 20px;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    background: #f8fafc;
}

.nn-mini-inputs,
.nn-mini-output {
    display: grid;
    gap: 10px;
}

.nn-pill {
    padding: 10px 12px;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    background: #fff;
    text-align: center;
    font-family: "Courier New", monospace;
}

.nn-neuron-core {
    width: 110px;
    height: 110px;
    display: grid;
    place-items: center;
    border: 3px solid #2563eb;
    border-radius: 50%;
    background: #eff6ff;
    color: #1d4ed8;
    font-weight: 800;
    text-align: center;
}

.nn-source-note {
    color: #64748b;
    font-size: 13px;
    line-height: 1.6;
}

@media (max-width: 950px) {
    .nn-layout,
    .nn-training-grid {
        grid-template-columns: 1fr;
    }

    .nn-step-row {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 700px) {
    .nn-mini-neuron {
        grid-template-columns: 1fr;
    }

    .nn-neuron-core {
        margin: auto;
    }

    .nn-weight-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .nn-step-row {
        grid-template-columns: repeat(2, 1fr);
    }

    .nn-stage svg {
        height: 390px;
    }

    .nn-stage {
        min-height: 390px;
    }
}
</style>

<main class="container">
    <div class="page-header">
        <h1>Neural Network dari Nol — 2 Input, 2 Hidden, 1 Output</h1>
        <p>
            Visualisasi ini mengikuti alur pembelajaran sumber:
            neuron → feedforward → loss → chain rule / backpropagation → stochastic gradient descent.
        </p>
    </div>

    <div class="card">
        <h2>1) Building Block: Satu Neuron</h2>

        <p>
            Sebuah neuron mengalikan setiap input dengan weight, menjumlahkannya dengan bias,
            lalu melewatkan hasilnya ke fungsi aktivasi sigmoid.
        </p>

        <div class="nn-mini-neuron">
            <div class="nn-mini-inputs">
                <div class="nn-pill">x₁ = 2, w₁ = 0</div>
                <div class="nn-pill">x₂ = 3, w₂ = 1</div>
                <div class="nn-pill">bias = 4</div>
            </div>

            <div class="nn-neuron-core">
                Σ + sigmoid
            </div>

            <div class="nn-mini-output">
                <div class="nn-pill">z = <?= round($singleNeuronZ, 6) ?></div>
                <div class="nn-pill">y = <?= round($singleNeuronOutput, 9) ?></div>
            </div>
        </div>

        <div class="formula" style="margin-top:16px;">
            y = sigmoid(x₁w₁ + x₂w₂ + b)
            = sigmoid(2×0 + 3×1 + 4)
            = sigmoid(7)
            ≈ <?= round($singleNeuronOutput, 6) ?>
        </div>
    </div>

    <div class="card">
        <h2>2) Animasi Feedforward Jaringan 2–2–1</h2>

        <div class="nn-layout">
            <div>
                <div class="nn-stage">
                    <svg id="feedforwardSvg" viewBox="0 0 760 460" role="img" aria-label="Neural network 2 input, 2 hidden, 1 output">
                        <text x="120" y="35" class="nn-layer-label">Input</text>
                        <text x="380" y="35" class="nn-layer-label">Hidden Layer</text>
                        <text x="640" y="35" class="nn-layer-label">Output</text>

                        <line id="ff-l-x1-h1" class="nn-link" x1="145" y1="145" x2="350" y2="135"></line>
                        <line id="ff-l-x2-h1" class="nn-link" x1="145" y1="315" x2="350" y2="135"></line>
                        <line id="ff-l-x1-h2" class="nn-link" x1="145" y1="145" x2="350" y2="315"></line>
                        <line id="ff-l-x2-h2" class="nn-link" x1="145" y1="315" x2="350" y2="315"></line>
                        <line id="ff-l-h1-o1" class="nn-link" x1="410" y1="135" x2="610" y2="225"></line>
                        <line id="ff-l-h2-o1" class="nn-link" x1="410" y1="315" x2="610" y2="225"></line>

                        <g id="ff-x1" class="nn-node">
                            <circle cx="120" cy="145" r="34"></circle>
                            <text x="120" y="145">x₁</text>
                        </g>

                        <g id="ff-x2" class="nn-node">
                            <circle cx="120" cy="315" r="34"></circle>
                            <text x="120" y="315">x₂</text>
                        </g>

                        <g id="ff-h1" class="nn-node">
                            <circle cx="380" cy="135" r="34"></circle>
                            <text x="380" y="135">h₁</text>
                        </g>

                        <g id="ff-h2" class="nn-node">
                            <circle cx="380" cy="315" r="34"></circle>
                            <text x="380" y="315">h₂</text>
                        </g>

                        <g id="ff-o1" class="nn-node">
                            <circle cx="640" cy="225" r="38"></circle>
                            <text x="640" y="225">o₁</text>
                        </g>

                        <text x="120" y="195" class="nn-value" id="ff-val-x1">x₁ = 2</text>
                        <text x="120" y="365" class="nn-value" id="ff-val-x2">x₂ = 3</text>
                        <text x="380" y="185" class="nn-value" id="ff-val-h1">h₁ = -</text>
                        <text x="380" y="365" class="nn-value" id="ff-val-h2">h₂ = -</text>
                        <text x="640" y="285" class="nn-value" id="ff-val-o1">o₁ = -</text>
                    </svg>
                </div>

                <div class="nn-controls">
                    <button type="button" class="nn-btn nn-btn-secondary" onclick="prevFeedforward()">← Sebelumnya</button>
                    <button type="button" class="nn-btn nn-btn-primary" onclick="nextFeedforward()">Berikutnya →</button>
                    <button type="button" class="nn-btn nn-btn-primary" onclick="playFeedforward()">▶ Play</button>
                    <button type="button" class="nn-btn nn-btn-secondary" onclick="pauseFeedforward()">⏸ Pause</button>
                    <button type="button" class="nn-btn nn-btn-secondary" onclick="resetFeedforward()">↺ Reset</button>
                </div>

                <div class="nn-step-row">
                    <div class="nn-step-chip" data-ff-chip="0">Input</div>
                    <div class="nn-step-chip" data-ff-chip="1">Hidden h₁</div>
                    <div class="nn-step-chip" data-ff-chip="2">Hidden h₂</div>
                    <div class="nn-step-chip" data-ff-chip="3">Output o₁</div>
                    <div class="nn-step-chip" data-ff-chip="4">Selesai</div>
                </div>
            </div>

            <div class="nn-panel">
                <div class="nn-callout">
                    <strong id="ffTitle"></strong>
                    <div id="ffText" style="margin-top:8px;"></div>
                    <div class="nn-formula" id="ffFormula"></div>
                </div>

                <div class="nn-stat">
                    <div class="nn-stat-label">h₁</div>
                    <div class="nn-stat-value"><?= round($feedforwardExample['h1'], 6) ?></div>
                </div>

                <div class="nn-stat">
                    <div class="nn-stat-label">h₂</div>
                    <div class="nn-stat-value"><?= round($feedforwardExample['h2'], 6) ?></div>
                </div>

                <div class="nn-stat">
                    <div class="nn-stat-label">Output o₁</div>
                    <div class="nn-stat-value"><?= round($feedforwardExample['o1'], 6) ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>3) Dataset dan Loss</h2>

        <p>
            Dataset mengikuti contoh sumber. Nilai weight dan height sudah digeser;
            label Male = 0 dan Female = 1.
        </p>

        <div class="nn-table-wrap">
            <table class="nn-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Weight − 135</th>
                        <th>Height − 66</th>
                        <th>Label</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $i => $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($names[$i]) ?></td>
                            <td><?= $row[0] ?></td>
                            <td><?= $row[1] ?></td>
                            <td><?= $labels[$i] ?> (<?= $labels[$i] === 1 ? 'Female' : 'Male' ?>)</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="formula" style="margin-top:16px;">
            MSE = (1/n) Σ (y_true − y_pred)²
        </div>
    </div>

    <div class="card">
        <h2>4) Animasi Backpropagation — Contoh Alice</h2>

        <p>
            Untuk mengikuti contoh turunan pada sumber, bagian ini memakai
            Alice = [-2, -1], y = 1, semua weight awal = 1, dan semua bias = 0.
        </p>

        <div class="nn-layout">
            <div>
                <div class="nn-stage">
                    <svg id="backpropSvg" viewBox="0 0 760 460">
                        <text x="120" y="35" class="nn-layer-label">Input</text>
                        <text x="380" y="35" class="nn-layer-label">Hidden</text>
                        <text x="640" y="35" class="nn-layer-label">Output / Loss</text>

                        <line id="bp-l-x1-h1" class="nn-link" x1="145" y1="145" x2="350" y2="135"></line>
                        <line id="bp-l-x2-h1" class="nn-link" x1="145" y1="315" x2="350" y2="135"></line>
                        <line id="bp-l-x1-h2" class="nn-link" x1="145" y1="145" x2="350" y2="315"></line>
                        <line id="bp-l-x2-h2" class="nn-link" x1="145" y1="315" x2="350" y2="315"></line>
                        <line id="bp-l-h1-o1" class="nn-link" x1="410" y1="135" x2="610" y2="225"></line>
                        <line id="bp-l-h2-o1" class="nn-link" x1="410" y1="315" x2="610" y2="225"></line>

                        <g id="bp-x1" class="nn-node">
                            <circle cx="120" cy="145" r="34"></circle>
                            <text x="120" y="145">x₁</text>
                        </g>
                        <g id="bp-x2" class="nn-node">
                            <circle cx="120" cy="315" r="34"></circle>
                            <text x="120" y="315">x₂</text>
                        </g>
                        <g id="bp-h1" class="nn-node">
                            <circle cx="380" cy="135" r="34"></circle>
                            <text x="380" y="135">h₁</text>
                        </g>
                        <g id="bp-h2" class="nn-node">
                            <circle cx="380" cy="315" r="34"></circle>
                            <text x="380" y="315">h₂</text>
                        </g>
                        <g id="bp-o1" class="nn-node">
                            <circle cx="640" cy="225" r="38"></circle>
                            <text x="640" y="225">ŷ</text>
                        </g>

                        <text x="120" y="195" class="nn-value">x₁ = -2</text>
                        <text x="120" y="365" class="nn-value">x₂ = -1</text>
                        <text x="380" y="185" class="nn-value">h₁ ≈ <?= round($aliceBackprop['feedforward']['h1'], 4) ?></text>
                        <text x="380" y="365" class="nn-value">h₂ ≈ <?= round($aliceBackprop['feedforward']['h2'], 4) ?></text>
                        <text x="640" y="285" class="nn-value">ŷ ≈ <?= round($aliceBackprop['feedforward']['o1'], 4) ?></text>
                    </svg>
                </div>

                <div class="nn-controls">
                    <button type="button" class="nn-btn nn-btn-secondary" onclick="prevBackprop()">← Sebelumnya</button>
                    <button type="button" class="nn-btn nn-btn-primary" onclick="nextBackprop()">Berikutnya →</button>
                    <button type="button" class="nn-btn nn-btn-primary" onclick="playBackprop()">▶ Play</button>
                    <button type="button" class="nn-btn nn-btn-secondary" onclick="pauseBackprop()">⏸ Pause</button>
                    <button type="button" class="nn-btn nn-btn-secondary" onclick="resetBackprop()">↺ Reset</button>
                </div>
            </div>

            <div class="nn-panel">
                <div class="nn-callout">
                    <strong id="bpTitle"></strong>
                    <div id="bpText" style="margin-top:8px;"></div>
                    <div class="nn-formula" id="bpFormula"></div>
                </div>

                <div class="nn-stat">
                    <div class="nn-stat-label">Loss Alice</div>
                    <div class="nn-stat-value"><?= round($aliceBackprop['loss'], 6) ?></div>
                </div>

                <div class="nn-stat">
                    <div class="nn-stat-label">∂L/∂w₁</div>
                    <div class="nn-stat-value"><?= round($aliceBackprop['gradients']['w1'], 6) ?></div>
                </div>

                <div class="nn-stat">
                    <div class="nn-stat-label">Makna</div>
                    <div class="nn-stat-value" style="font-size:14px; line-height:1.5;">
                        Gradient positif → SGD akan menurunkan w₁.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>5) Training dengan Batch Gradient Descent</h2>

        <p>
            Pada versi ini, parameter <strong>tidak di-update per sampel</strong>.
            Semua gradient dari seluruh data dijumlahkan lebih dulu, lalu dirata-ratakan,
            dan parameter di-update <strong>sekali per epoch</strong>. Jadi ini adalah
            <strong>Batch Gradient Descent</strong>, bukan Stochastic Gradient Descent.
        </p>

        <form method="GET" class="input-form">
            <div class="input-group">
                <label for="lr">Learning Rate η</label>
                <input type="number" step="any" id="lr" name="lr" value="<?= htmlspecialchars((string) $learningRate) ?>" required>
            </div>

            <div class="input-group">
                <label for="epochs">Epoch</label>
                <input type="number" min="10" max="5000" id="epochs" name="epochs" value="<?= $epochs ?>" required>
            </div>

            <div class="input-group">
                <label for="seed">Seed</label>
                <input type="number" id="seed" name="seed" value="<?= $seed ?>" required>
            </div>

            <div class="input-group">
                <label for="record">Simpan state tiap N epoch</label>
                <input type="number" min="1" max="100" id="record" name="record" value="<?= $recordEvery ?>" required>
            </div>

            <div class="input-action">
                <button type="submit" class="btn btn-primary">Train Ulang</button>
            </div>
        </form>

        <div class="nn-training-grid" style="margin-top:22px;">
            <div>
                <div class="nn-training-visual">
                    <div class="nn-stage nn-training-stage">
                        <svg id="trainingSvg" viewBox="0 0 760 320" role="img" aria-label="Animasi perubahan weight saat training">
                            <text x="120" y="32" class="nn-layer-label">Input</text>
                            <text x="380" y="32" class="nn-layer-label">Hidden</text>
                            <text x="640" y="32" class="nn-layer-label">Output</text>

                            <line class="nn-link active" x1="145" y1="110" x2="350" y2="100"></line>
                            <line class="nn-link active" x1="145" y1="220" x2="350" y2="100"></line>
                            <line class="nn-link active" x1="145" y1="110" x2="350" y2="220"></line>
                            <line class="nn-link active" x1="145" y1="220" x2="350" y2="220"></line>
                            <line class="nn-link active" x1="410" y1="100" x2="610" y2="160"></line>
                            <line class="nn-link active" x1="410" y1="220" x2="610" y2="160"></line>

                            <g class="nn-node active">
                                <circle cx="120" cy="110" r="30"></circle>
                                <text x="120" y="110">x₁</text>
                            </g>
                            <g class="nn-node active">
                                <circle cx="120" cy="220" r="30"></circle>
                                <text x="120" y="220">x₂</text>
                            </g>
                            <g class="nn-node active">
                                <circle cx="380" cy="100" r="30"></circle>
                                <text x="380" y="100">h₁</text>
                            </g>
                            <g class="nn-node active">
                                <circle cx="380" cy="220" r="30"></circle>
                                <text x="380" y="220">h₂</text>
                            </g>
                            <g class="nn-node active">
                                <circle cx="640" cy="160" r="34"></circle>
                                <text x="640" y="160">o₁</text>
                            </g>

                            <!-- current weight labels -->
                            <text id="train-w1" class="nn-weight-label" x="247" y="92">w₁ = -</text>
                            <text id="train-w2" class="nn-weight-label" x="248" y="155">w₂ = -</text>
                            <text id="train-w3" class="nn-weight-label" x="246" y="175">w₃ = -</text>
                            <text id="train-w4" class="nn-weight-label" x="248" y="235">w₄ = -</text>
                            <text id="train-w5" class="nn-weight-label" x="520" y="115">w₅ = -</text>
                            <text id="train-w6" class="nn-weight-label" x="520" y="215">w₆ = -</text>

                            <!-- delta labels -->
                            <text id="delta-w1" class="nn-weight-delta" x="247" y="107"></text>
                            <text id="delta-w2" class="nn-weight-delta" x="248" y="170"></text>
                            <text id="delta-w3" class="nn-weight-delta" x="246" y="190"></text>
                            <text id="delta-w4" class="nn-weight-delta" x="248" y="250"></text>
                            <text id="delta-w5" class="nn-weight-delta" x="520" y="130"></text>
                            <text id="delta-w6" class="nn-weight-delta" x="520" y="230"></text>

                            <text id="train-b1" class="nn-bias-label" x="435" y="77">b₁ = -</text>
                            <text id="train-b2" class="nn-bias-label" x="435" y="252">b₂ = -</text>
                            <text id="train-b3" class="nn-bias-label" x="690" y="142">b₃ = -</text>

                            <text id="delta-b1" class="nn-weight-delta" x="435" y="92"></text>
                            <text id="delta-b2" class="nn-weight-delta" x="435" y="267"></text>
                            <text id="delta-b3" class="nn-weight-delta" x="690" y="157"></text>
                        </svg>
                    </div>
                    <div class="nn-training-note">
                        Diagram ini memperlihatkan <strong>nilai weight dan bias berubah di setiap epoch</strong>.
                        Teks oranye berarti baru saja berubah; Δ hijau/merah menunjukkan besar perubahan pada epoch tersebut.
                    </div>
                </div>

                <canvas id="lossCanvas" class="nn-loss-canvas"></canvas>

                <input
                    type="range"
                    id="trainingRange"
                    class="nn-training-range"
                    min="0"
                    max="<?= max(0, count($states) - 1) ?>"
                    value="0"
                    oninput="goToTrainingState(Number(this.value))"
                >

                <div class="nn-controls">
                    <button type="button" class="nn-btn nn-btn-secondary" onclick="prevTraining()">← Sebelumnya</button>
                    <button type="button" class="nn-btn nn-btn-primary" onclick="nextTraining()">Berikutnya →</button>
                    <button type="button" class="nn-btn nn-btn-primary" onclick="playTraining()">▶ Play</button>
                    <button type="button" class="nn-btn nn-btn-secondary" onclick="pauseTraining()">⏸ Pause</button>
                    <button type="button" class="nn-btn nn-btn-secondary" onclick="resetTraining()">↺ Reset</button>
                </div>
            </div>

            <div class="nn-panel">
                <div class="nn-stat">
                    <div class="nn-stat-label">Epoch</div>
                    <div class="nn-stat-value" id="trainEpoch">-</div>
                </div>

                <div class="nn-stat">
                    <div class="nn-stat-label">MSE Loss</div>
                    <div class="nn-stat-value" id="trainLoss">-</div>
                </div>

                <div class="nn-stat">
                    <div class="nn-stat-label">Accuracy @ 0.5</div>
                    <div class="nn-stat-value" id="trainAccuracy">-</div>
                </div>

                <div class="nn-stat">
                    <div class="nn-stat-label">Update rule</div>
                    <div class="nn-stat-value" id="trainUpdateRule" style="font-size:13px; line-height:1.6;">-</div>
                </div>

                <div class="nn-weight-grid">
                    <?php foreach (['w1','w2','w3','w4','w5','w6','b1','b2','b3'] as $param): ?>
                        <div class="nn-stat">
                            <div class="nn-stat-label"><?= htmlspecialchars($param) ?></div>
                            <div class="nn-stat-value" style="font-size:14px;" id="param-<?= htmlspecialchars($param) ?>">-</div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="nn-table-wrap" style="margin-top:22px;">
            <table class="nn-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>y true</th>
                        <th>y pred</th>
                        <th>Prediksi kelas</th>
                    </tr>
                </thead>
                <tbody id="predictionBody"></tbody>
            </table>
        </div>

        <div class="formula" style="margin-top:18px;">
            parameter ← parameter − η × (1/n) Σ ∂Lᵢ/∂parameter
        </div>
    </div>

    <div class="card">
        <h2>Model Akhir</h2>

        <p>
            Loss akhir:
            <strong><?= round($finalEvaluation['loss'], 8) ?></strong>.
            Accuracy threshold 0.5:
            <strong><?= round($finalEvaluation['accuracy'] * 100, 2) ?>%</strong>.
        </p>

        <div class="nn-table-wrap">
            <table class="nn-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Label</th>
                        <th>Output NN</th>
                        <th>Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($finalEvaluation['predictions'] as $i => $prediction): ?>
                        <tr>
                            <td><?= htmlspecialchars($names[$i]) ?></td>
                            <td><?= $prediction['y_true'] ?></td>
                            <td><?= round($prediction['y_pred'], 6) ?></td>
                            <td><?= $prediction['class'] === 1 ? 'Female (1)' : 'Male (0)' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <p class="nn-source-note" style="margin-top:16px;">
            Halaman ini sengaja mempertahankan arsitektur dan contoh pembelajaran sederhana
            2-input → 2-hidden → 1-output agar alur feedforward, loss, backpropagation,
            dan Batch Gradient Descent dapat diamati satu per satu.
        </p>
    </div>
</main>

<script>
const feedforwardValues = {
    h1: <?= json_encode($feedforwardExample['h1'], JSON_NUMERIC_CHECK) ?>,
    h2: <?= json_encode($feedforwardExample['h2'], JSON_NUMERIC_CHECK) ?>,
    o1: <?= json_encode($feedforwardExample['o1'], JSON_NUMERIC_CHECK) ?>
};

const aliceBackprop = <?= json_encode($aliceBackprop, JSON_NUMERIC_CHECK) ?>;
const trainingStates = <?= json_encode($states, JSON_NUMERIC_CHECK) ?>;
const trainingNames = <?= json_encode($names, JSON_UNESCAPED_UNICODE) ?>;
const learningRateValue = <?= json_encode($learningRate, JSON_NUMERIC_CHECK) ?>;

function fmt(value, digits = 6)
{
    const n = Number(value);

    if (!Number.isFinite(n)) {
        return '-';
    }

    if (Math.abs(n) >= 1e5 || (Math.abs(n) > 0 && Math.abs(n) < 1e-5)) {
        return n.toExponential(4);
    }

    return n.toFixed(digits).replace(/0+$/, '').replace(/\.$/, '');
}


function fmtDelta(value, digits = 5)
{
    const n = Number(value);

    if (!Number.isFinite(n) || Math.abs(n) < 1e-12) {
        return 'Δ 0';
    }

    const prefix = n > 0 ? 'Δ +' : 'Δ ';
    return prefix + fmt(n, digits);
}

function updateTrainingWeightLabel(name, value, delta)
{
    const label = document.getElementById('train-' + name);
    const deltaEl = document.getElementById('delta-' + name);

    if (!label || !deltaEl) {
        return;
    }

    label.textContent = name + ' = ' + fmt(value, 5);
    label.classList.remove('changed');
    void label.offsetWidth;
    label.classList.add('changed');

    deltaEl.textContent = fmtDelta(delta, 5);
    deltaEl.classList.remove('up', 'down');

    if (Number(delta) > 0) {
        deltaEl.classList.add('up');
    } else if (Number(delta) < 0) {
        deltaEl.classList.add('down');
    }
}

/* =========================================================
   Feedforward animation
   ========================================================= */

const feedforwardSteps = [
    {
        title: 'Langkah 1 — Masukkan x₁ dan x₂',
        text: 'Input x = [2, 3] masuk ke dua neuron hidden.',
        formula: 'x = [2, 3]'
    },
    {
        title: 'Langkah 2 — Hitung h₁',
        text: 'Dengan w = [0,1] dan b = 0, neuron h₁ menerima 0×2 + 1×3.',
        formula: 'h₁ = sigmoid(3) ≈ ' + fmt(feedforwardValues.h1, 4)
    },
    {
        title: 'Langkah 3 — Hitung h₂',
        text: 'h₂ memakai parameter yang sama, sehingga nilainya sama dengan h₁.',
        formula: 'h₂ = sigmoid(3) ≈ ' + fmt(feedforwardValues.h2, 4)
    },
    {
        title: 'Langkah 4 — Hidden menjadi input output neuron',
        text: 'o₁ menerima [h₁, h₂]. Dengan w = [0,1], hanya h₂ yang berkontribusi.',
        formula: 'o₁ = sigmoid(0×h₁ + 1×h₂)'
    },
    {
        title: 'Langkah 5 — Feedforward selesai',
        text: 'Output jaringan untuk x=[2,3] diperoleh.',
        formula: 'o₁ ≈ ' + fmt(feedforwardValues.o1, 4)
    }
];

let ffIndex = 0;
let ffTimer = null;

function clearFeedforwardHighlights()
{
    document.querySelectorAll('#feedforwardSvg .nn-node, #feedforwardSvg .nn-link')
        .forEach(el => el.classList.remove('active'));
}

function renderFeedforward()
{
    clearFeedforwardHighlights();

    document.getElementById('ff-val-h1').textContent = 'h₁ = -';
    document.getElementById('ff-val-h2').textContent = 'h₂ = -';
    document.getElementById('ff-val-o1').textContent = 'o₁ = -';

    if (ffIndex >= 0) {
        document.getElementById('ff-x1').classList.add('active');
        document.getElementById('ff-x2').classList.add('active');
    }

    if (ffIndex >= 1) {
        document.getElementById('ff-l-x1-h1').classList.add('active');
        document.getElementById('ff-l-x2-h1').classList.add('active');
        document.getElementById('ff-h1').classList.add('active');
        document.getElementById('ff-val-h1').textContent =
            'h₁ = ' + fmt(feedforwardValues.h1, 4);
    }

    if (ffIndex >= 2) {
        document.getElementById('ff-l-x1-h2').classList.add('active');
        document.getElementById('ff-l-x2-h2').classList.add('active');
        document.getElementById('ff-h2').classList.add('active');
        document.getElementById('ff-val-h2').textContent =
            'h₂ = ' + fmt(feedforwardValues.h2, 4);
    }

    if (ffIndex >= 3) {
        document.getElementById('ff-l-h1-o1').classList.add('active');
        document.getElementById('ff-l-h2-o1').classList.add('active');
        document.getElementById('ff-o1').classList.add('active');
    }

    if (ffIndex >= 4) {
        document.getElementById('ff-val-o1').textContent =
            'o₁ = ' + fmt(feedforwardValues.o1, 4);
    }

    const step = feedforwardSteps[ffIndex];

    document.getElementById('ffTitle').textContent = step.title;
    document.getElementById('ffText').textContent = step.text;
    document.getElementById('ffFormula').textContent = step.formula;

    document.querySelectorAll('[data-ff-chip]').forEach(chip => {
        chip.classList.toggle(
            'active',
            Number(chip.dataset.ffChip) === ffIndex
        );
    });
}

function nextFeedforward()
{
    if (ffIndex < feedforwardSteps.length - 1) {
        ffIndex++;
        renderFeedforward();
    }
}

function prevFeedforward()
{
    if (ffIndex > 0) {
        ffIndex--;
        renderFeedforward();
    }
}

function playFeedforward()
{
    pauseFeedforward();

    ffTimer = setInterval(() => {
        if (ffIndex >= feedforwardSteps.length - 1) {
            pauseFeedforward();
            return;
        }

        nextFeedforward();
    }, 1300);
}

function pauseFeedforward()
{
    if (ffTimer !== null) {
        clearInterval(ffTimer);
        ffTimer = null;
    }
}

function resetFeedforward()
{
    pauseFeedforward();
    ffIndex = 0;
    renderFeedforward();
}

/* =========================================================
   Backprop animation
   ========================================================= */

const bpSteps = [
    {
        title: 'Langkah 1 — Feedforward Alice',
        text: 'Alice memiliki x₁=-2, x₂=-1. Semua weight=1 dan bias=0.',
        formula: 'h₁ = h₂ = sigmoid(-3)'
    },
    {
        title: 'Langkah 2 — Hitung output',
        text: 'Output neuron menerima h₁ dan h₂ lalu menghasilkan ŷ.',
        formula: 'ŷ = sigmoid(h₁ + h₂) ≈ ' + fmt(aliceBackprop.feedforward.o1, 4)
    },
    {
        title: 'Langkah 3 — Hitung loss',
        text: 'Karena label Alice adalah 1, loss satu sampel adalah squared error.',
        formula: 'L = (1 - ŷ)² ≈ ' + fmt(aliceBackprop.loss, 6)
    },
    {
        title: 'Langkah 4 — Mulai dari ∂L/∂ŷ',
        text: 'Backpropagation berjalan mundur dari loss menuju parameter.',
        formula: '∂L/∂ŷ = -2(1-ŷ) ≈ ' + fmt(aliceBackprop.d_L_d_ypred, 4)
    },
    {
        title: 'Langkah 5 — Chain rule menuju w₁',
        text: 'w₁ memengaruhi h₁, lalu h₁ memengaruhi ŷ, lalu ŷ memengaruhi loss.',
        formula: '∂L/∂w₁ = (∂L/∂ŷ)(∂ŷ/∂h₁)(∂h₁/∂w₁)'
    },
    {
        title: 'Langkah 6 — Dapat gradient w₁',
        text: 'Gradient positif berarti menaikkan w₁ akan menaikkan loss.',
        formula: '∂L/∂w₁ ≈ ' + fmt(aliceBackprop.gradients.w1, 6)
    },
    {
        title: 'Langkah 7 — SGD memperbarui parameter',
        text: 'Parameter digeser berlawanan arah gradient agar loss turun.',
        formula: 'w₁ ← w₁ - η(∂L/∂w₁)'
    }
];

let bpIndex = 0;
let bpTimer = null;

function clearBackprop()
{
    document.querySelectorAll('#backpropSvg .nn-node, #backpropSvg .nn-link')
        .forEach(el => {
            el.classList.remove('active');
            el.classList.remove('backward');
        });
}

function renderBackprop()
{
    clearBackprop();

    if (bpIndex >= 0) {
        ['bp-x1', 'bp-x2', 'bp-h1', 'bp-h2'].forEach(id => {
            document.getElementById(id).classList.add('active');
        });

        ['bp-l-x1-h1', 'bp-l-x2-h1', 'bp-l-x1-h2', 'bp-l-x2-h2'].forEach(id => {
            document.getElementById(id).classList.add('active');
        });
    }

    if (bpIndex >= 1) {
        document.getElementById('bp-o1').classList.add('active');
        document.getElementById('bp-l-h1-o1').classList.add('active');
        document.getElementById('bp-l-h2-o1').classList.add('active');
    }

    if (bpIndex >= 3) {
        document.getElementById('bp-o1').classList.add('active');
    }

    if (bpIndex >= 4) {
        ['bp-l-h1-o1', 'bp-l-x1-h1'].forEach(id => {
            const el = document.getElementById(id);
            el.classList.add('active');
            el.classList.add('backward');
        });

        document.getElementById('bp-h1').classList.add('active');
        document.getElementById('bp-x1').classList.add('active');
    }

    const step = bpSteps[bpIndex];

    document.getElementById('bpTitle').textContent = step.title;
    document.getElementById('bpText').textContent = step.text;
    document.getElementById('bpFormula').textContent = step.formula;
}

function nextBackprop()
{
    if (bpIndex < bpSteps.length - 1) {
        bpIndex++;
        renderBackprop();
    }
}

function prevBackprop()
{
    if (bpIndex > 0) {
        bpIndex--;
        renderBackprop();
    }
}

function playBackprop()
{
    pauseBackprop();

    bpTimer = setInterval(() => {
        if (bpIndex >= bpSteps.length - 1) {
            pauseBackprop();
            return;
        }

        nextBackprop();
    }, 1450);
}

function pauseBackprop()
{
    if (bpTimer !== null) {
        clearInterval(bpTimer);
        bpTimer = null;
    }
}

function resetBackprop()
{
    pauseBackprop();
    bpIndex = 0;
    renderBackprop();
}

/* =========================================================
   SGD training animation
   ========================================================= */

let trainIndex = 0;
let trainTimer = null;

function drawLossChart()
{
    const canvas = document.getElementById('lossCanvas');
    const rect = canvas.getBoundingClientRect();
    const dpr = window.devicePixelRatio || 1;
    const width = rect.width;
    const height = rect.height;

    canvas.width = width * dpr;
    canvas.height = height * dpr;

    const ctx = canvas.getContext('2d');
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    ctx.clearRect(0, 0, width, height);

    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, width, height);

    if (trainingStates.length === 0) {
        return;
    }

    const margin = { left: 62, right: 24, top: 24, bottom: 46 };
    const plotW = width - margin.left - margin.right;
    const plotH = height - margin.top - margin.bottom;
    const visible = trainingStates.slice(0, trainIndex + 1);
    const maxEpoch = trainingStates[trainingStates.length - 1].epoch || 1;
    const maxLoss = Math.max(...trainingStates.map(s => Number(s.loss)), 1e-9);

    function mapX(epoch)
    {
        return margin.left + (epoch / maxEpoch) * plotW;
    }

    function mapY(loss)
    {
        return margin.top + (1 - loss / maxLoss) * plotH;
    }

    ctx.strokeStyle = '#e2e8f0';
    ctx.lineWidth = 1;

    for (let i = 0; i <= 5; i++) {
        const y = margin.top + (i / 5) * plotH;
        const value = maxLoss * (1 - i / 5);

        ctx.beginPath();
        ctx.moveTo(margin.left, y);
        ctx.lineTo(width - margin.right, y);
        ctx.stroke();

        ctx.fillStyle = '#64748b';
        ctx.font = '11px Arial';
        ctx.textAlign = 'right';
        ctx.textBaseline = 'middle';
        ctx.fillText(fmt(value, 3), margin.left - 8, y);
    }

    ctx.strokeStyle = '#64748b';
    ctx.lineWidth = 1.4;

    ctx.beginPath();
    ctx.moveTo(margin.left, margin.top);
    ctx.lineTo(margin.left, height - margin.bottom);
    ctx.lineTo(width - margin.right, height - margin.bottom);
    ctx.stroke();

    ctx.beginPath();

    visible.forEach((state, i) => {
        const x = mapX(state.epoch);
        const y = mapY(state.loss);

        if (i === 0) {
            ctx.moveTo(x, y);
        } else {
            ctx.lineTo(x, y);
        }
    });

    ctx.strokeStyle = '#2563eb';
    ctx.lineWidth = 3;
    ctx.stroke();

    visible.forEach(state => {
        const x = mapX(state.epoch);
        const y = mapY(state.loss);

        ctx.beginPath();
        ctx.arc(x, y, 3.5, 0, Math.PI * 2);
        ctx.fillStyle = '#2563eb';
        ctx.fill();
    });

    const active = visible[visible.length - 1];

    if (active) {
        const x = mapX(active.epoch);
        const y = mapY(active.loss);

        ctx.beginPath();
        ctx.arc(x, y, 7, 0, Math.PI * 2);
        ctx.strokeStyle = '#f59e0b';
        ctx.lineWidth = 2;
        ctx.stroke();
    }

    ctx.fillStyle = '#334155';
    ctx.font = '12px Arial';
    ctx.textAlign = 'center';
    ctx.fillText('Epoch', margin.left + plotW / 2, height - 12);

    ctx.save();
    ctx.translate(16, margin.top + plotH / 2);
    ctx.rotate(-Math.PI / 2);
    ctx.fillText('MSE Loss', 0, 0);
    ctx.restore();
}

function renderTraining()
{
    if (trainingStates.length === 0) {
        return;
    }

    const state = trainingStates[trainIndex];

    document.getElementById('trainEpoch').textContent = state.epoch;
    document.getElementById('trainLoss').textContent = fmt(state.loss, 8);
    document.getElementById('trainAccuracy').textContent =
        fmt(Number(state.accuracy) * 100, 2) + '%';
    document.getElementById('trainingRange').value = trainIndex;

    document.getElementById('trainUpdateRule').innerHTML =
        'θ ← θ − η · (1/n)Σ∂Lᵢ/∂θ' + '<br>' +
        'η = ' + fmt(learningRateValue, 5);

    Object.entries(state.params).forEach(([name, value]) => {
        const el = document.getElementById('param-' + name);

        if (el) {
            const delta = state.deltas && name in state.deltas ? state.deltas[name] : 0;
            el.innerHTML = fmt(value, 5) + '<br><small style="color:#64748b;">' + fmtDelta(delta, 5) + '</small>';
        }
    });

    ['w1', 'w2', 'w3', 'w4', 'w5', 'w6', 'b1', 'b2', 'b3'].forEach(name => {
        const value = state.params[name];
        const delta = state.deltas && name in state.deltas ? state.deltas[name] : 0;
        updateTrainingWeightLabel(name, value, delta);
    });

    const body = document.getElementById('predictionBody');
    body.innerHTML = '';

    state.predictions.forEach((prediction, i) => {
        const tr = document.createElement('tr');

        tr.innerHTML =
            '<td>' + trainingNames[i] + '</td>' +
            '<td>' + prediction.y_true + '</td>' +
            '<td>' + fmt(prediction.y_pred, 6) + '</td>' +
            '<td>' + (Number(prediction.class) === 1 ? 'Female (1)' : 'Male (0)') + '</td>';

        body.appendChild(tr);
    });

    drawLossChart();
}

function nextTraining()
{
    if (trainIndex < trainingStates.length - 1) {
        trainIndex++;
        renderTraining();
    }
}

function prevTraining()
{
    if (trainIndex > 0) {
        trainIndex--;
        renderTraining();
    }
}

function playTraining()
{
    pauseTraining();

    trainTimer = setInterval(() => {
        if (trainIndex >= trainingStates.length - 1) {
            pauseTraining();
            return;
        }

        nextTraining();
    }, 180);
}

function pauseTraining()
{
    if (trainTimer !== null) {
        clearInterval(trainTimer);
        trainTimer = null;
    }
}

function resetTraining()
{
    pauseTraining();
    trainIndex = 0;
    renderTraining();
}

function goToTrainingState(index)
{
    pauseTraining();
    trainIndex = Math.max(0, Math.min(index, trainingStates.length - 1));
    renderTraining();
}

window.addEventListener('resize', drawLossChart);

renderFeedforward();
renderBackprop();
renderTraining();
</script>