<?php

function nnSigmoid(float $x): float
{
    if ($x >= 0) {
        $z = exp(-$x);
        return 1 / (1 + $z);
    }

    $z = exp($x);
    return $z / (1 + $z);
}

function nnDerivSigmoid(float $x): float
{
    $fx = nnSigmoid($x);
    return $fx * (1 - $fx);
}

function nnMseLoss(array $yTrue, array $yPred): float
{
    $n = count($yTrue);

    if ($n === 0 || $n !== count($yPred)) {
        return 0.0;
    }

    $sum = 0.0;

    for ($i = 0; $i < $n; $i++) {
        $sum += ($yTrue[$i] - $yPred[$i]) ** 2;
    }

    return $sum / $n;
}

function nnRandomNormal(): float
{
    $u1 = max(mt_rand() / mt_getrandmax(), 1e-12);
    $u2 = mt_rand() / mt_getrandmax();

    return sqrt(-2 * log($u1)) * cos(2 * M_PI * $u2);
}

function nnFeedforward(array $x, array $p): array
{
    $sumH1 = $p['w1'] * $x[0] + $p['w2'] * $x[1] + $p['b1'];
    $h1 = nnSigmoid($sumH1);

    $sumH2 = $p['w3'] * $x[0] + $p['w4'] * $x[1] + $p['b2'];
    $h2 = nnSigmoid($sumH2);

    $sumO1 = $p['w5'] * $h1 + $p['w6'] * $h2 + $p['b3'];
    $o1 = nnSigmoid($sumO1);

    return [
        'sum_h1' => $sumH1,
        'h1' => $h1,
        'sum_h2' => $sumH2,
        'h2' => $h2,
        'sum_o1' => $sumO1,
        'o1' => $o1,
    ];
}

function nnEvaluate(array $data, array $labels, array $p): array
{
    $predictions = [];
    $correct = 0;

    foreach ($data as $i => $x) {
        $ff = nnFeedforward($x, $p);
        $pred = $ff['o1'];
        $class = $pred >= 0.5 ? 1 : 0;

        if ($class === (int) $labels[$i]) {
            $correct++;
        }

        $predictions[] = [
            'x' => $x,
            'y_true' => $labels[$i],
            'y_pred' => $pred,
            'class' => $class,
            'h1' => $ff['h1'],
            'h2' => $ff['h2'],
        ];
    }

    $yPred = array_map(fn ($row) => $row['y_pred'], $predictions);

    return [
        'loss' => nnMseLoss($labels, $yPred),
        'accuracy' => count($labels) > 0 ? $correct / count($labels) : 0,
        'predictions' => $predictions,
    ];
}

function nnBackpropOne(array $x, float $yTrue, array $p): array
{
    $ff = nnFeedforward($x, $p);

    $sumH1 = $ff['sum_h1'];
    $h1 = $ff['h1'];
    $sumH2 = $ff['sum_h2'];
    $h2 = $ff['h2'];
    $sumO1 = $ff['sum_o1'];
    $yPred = $ff['o1'];

    $dLDyPred = -2 * ($yTrue - $yPred);

    $dYPredDw5 = $h1 * nnDerivSigmoid($sumO1);
    $dYPredDw6 = $h2 * nnDerivSigmoid($sumO1);
    $dYPredDb3 = nnDerivSigmoid($sumO1);

    $dYPredDh1 = $p['w5'] * nnDerivSigmoid($sumO1);
    $dYPredDh2 = $p['w6'] * nnDerivSigmoid($sumO1);

    $dH1Dw1 = $x[0] * nnDerivSigmoid($sumH1);
    $dH1Dw2 = $x[1] * nnDerivSigmoid($sumH1);
    $dH1Db1 = nnDerivSigmoid($sumH1);

    $dH2Dw3 = $x[0] * nnDerivSigmoid($sumH2);
    $dH2Dw4 = $x[1] * nnDerivSigmoid($sumH2);
    $dH2Db2 = nnDerivSigmoid($sumH2);

    return [
        'feedforward' => $ff,
        'loss' => ($yTrue - $yPred) ** 2,
        'd_L_d_ypred' => $dLDyPred,
        'gradients' => [
            'w1' => $dLDyPred * $dYPredDh1 * $dH1Dw1,
            'w2' => $dLDyPred * $dYPredDh1 * $dH1Dw2,
            'w3' => $dLDyPred * $dYPredDh2 * $dH2Dw3,
            'w4' => $dLDyPred * $dYPredDh2 * $dH2Dw4,
            'w5' => $dLDyPred * $dYPredDw5,
            'w6' => $dLDyPred * $dYPredDw6,
            'b1' => $dLDyPred * $dYPredDh1 * $dH1Db1,
            'b2' => $dLDyPred * $dYPredDh2 * $dH2Db2,
            'b3' => $dLDyPred * $dYPredDb3,
        ],
        'parts' => [
            'd_ypred_d_h1' => $dYPredDh1,
            'd_h1_d_w1' => $dH1Dw1,
            'd_ypred_d_h2' => $dYPredDh2,
            'd_h2_d_w3' => $dH2Dw3,
        ],
    ];
}

function nnZeroGradients(): array
{
    return [
        'w1' => 0.0,
        'w2' => 0.0,
        'w3' => 0.0,
        'w4' => 0.0,
        'w5' => 0.0,
        'w6' => 0.0,
        'b1' => 0.0,
        'b2' => 0.0,
        'b3' => 0.0,
    ];
}

function nnTrain(
    array $data,
    array $labels,
    float $learningRate = 0.1,
    int $epochs = 1000,
    int $seed = 42,
    int $recordEvery = 10
): array {
    mt_srand($seed);

    $p = [
        'w1' => nnRandomNormal(),
        'w2' => nnRandomNormal(),
        'w3' => nnRandomNormal(),
        'w4' => nnRandomNormal(),
        'w5' => nnRandomNormal(),
        'w6' => nnRandomNormal(),
        'b1' => nnRandomNormal(),
        'b2' => nnRandomNormal(),
        'b3' => nnRandomNormal(),
    ];

    $states = [];
    $initialEval = nnEvaluate($data, $labels, $p);

    $states[] = [
        'epoch' => 0,
        'loss' => $initialEval['loss'],
        'accuracy' => $initialEval['accuracy'],
        'params' => $p,
        'gradients' => nnZeroGradients(),
        'deltas' => nnZeroGradients(),
        'predictions' => $initialEval['predictions'],
    ];

    $epochs = max(1, $epochs);
    $recordEvery = max(1, $recordEvery);
    $sampleCount = max(1, count($data));

    for ($epoch = 1; $epoch <= $epochs; $epoch++) {
        $sumGradients = nnZeroGradients();

        foreach ($data as $i => $x) {
            $backprop = nnBackpropOne($x, (float) $labels[$i], $p);

            foreach ($backprop['gradients'] as $name => $gradient) {
                $sumGradients[$name] += $gradient;
            }
        }

        $avgGradients = nnZeroGradients();
        $deltas = nnZeroGradients();

        foreach ($sumGradients as $name => $gradientSum) {
            $avgGradients[$name] = $gradientSum / $sampleCount;
            $delta = -$learningRate * $avgGradients[$name];
            $deltas[$name] = $delta;
            $p[$name] += $delta;
        }

        if ($epoch % $recordEvery === 0 || $epoch === $epochs) {
            $evaluation = nnEvaluate($data, $labels, $p);

            $states[] = [
                'epoch' => $epoch,
                'loss' => $evaluation['loss'],
                'accuracy' => $evaluation['accuracy'],
                'params' => $p,
                'gradients' => $avgGradients,
                'deltas' => $deltas,
                'predictions' => $evaluation['predictions'],
            ];
        }
    }

    return [
        'params' => $p,
        'states' => $states,
        'evaluation' => nnEvaluate($data, $labels, $p),
    ];
}