<?php

function predictMultipleLinear($x1, $x2, $m1, $m2, $c)
{
    return $c + ($m1 * $x1) + ($m2 * $x2);
}

function evaluateMultipleLinear($points, $m1, $m2, $c)
{
    $n = count($points);

    if ($n === 0) {
        return [
            'mse' => 0,
            'sse' => 0,
            'sst' => 0,
            'r2' => 0,
            'predictions' => [],
        ];
    }

    $sumY = 0;

    foreach ($points as $point) {
        $sumY += $point[2];
    }

    $meanY = $sumY / $n;
    $sse = 0;
    $sst = 0;
    $predictions = [];

    foreach ($points as $point) {
        [$x1, $x2, $y] = $point;

        $yPred = predictMultipleLinear($x1, $x2, $m1, $m2, $c);
        $error = $yPred - $y;

        $sse += $error ** 2;
        $sst += ($y - $meanY) ** 2;

        $predictions[] = [
            'x1' => $x1,
            'x2' => $x2,
            'y' => $y,
            'y_pred' => $yPred,
            'residual' => $y - $yPred,
        ];
    }

    $mse = $sse / $n;
    $r2 = $sst > 1e-15 ? 1 - ($sse / $sst) : 1.0;

    return [
        'mse' => $mse,
        'sse' => $sse,
        'sst' => $sst,
        'r2' => $r2,
        'predictions' => $predictions,
    ];
}

function trainMultipleLinearRegression(
    $points = [],
    $lr = 0.01,
    $epoch = 200,
    $m1 = 0.0,
    $m2 = 0.0,
    $c = 0.0
) {
    if (empty($points)) {
        return [
            'success' => false,
            'message' => 'Dataset tidak boleh kosong.',
            'states' => [],
        ];
    }

    $lr = (float) $lr;
    $epoch = max(1, (int) $epoch);
    $m1 = (float) $m1;
    $m2 = (float) $m2;
    $c = (float) $c;

    $n = count($points);
    $states = [];

    $initialEvaluation = evaluateMultipleLinear($points, $m1, $m2, $c);

    $states[] = [
        'epoch' => 0,
        'm1' => $m1,
        'm2' => $m2,
        'c' => $c,
        'mse' => $initialEvaluation['mse'],
        'r2' => $initialEvaluation['r2'],
        'grad_m1' => null,
        'grad_m2' => null,
        'grad_c' => null,
        'prev_m1' => null,
        'prev_m2' => null,
        'prev_c' => null,
        'predictions' => $initialEvaluation['predictions'],
    ];

    for ($i = 1; $i <= $epoch; $i++) {
        $sumGradM1 = 0;
        $sumGradM2 = 0;
        $sumGradC = 0;

        foreach ($points as $point) {
            [$x1, $x2, $y] = $point;

            $yPred = predictMultipleLinear($x1, $x2, $m1, $m2, $c);
            $error = $yPred - $y;

            $sumGradM1 += $error * $x1;
            $sumGradM2 += $error * $x2;
            $sumGradC += $error;
        }

        $gradM1 = (2 / $n) * $sumGradM1;
        $gradM2 = (2 / $n) * $sumGradM2;
        $gradC = (2 / $n) * $sumGradC;

        $prevM1 = $m1;
        $prevM2 = $m2;
        $prevC = $c;

        $m1 -= $lr * $gradM1;
        $m2 -= $lr * $gradM2;
        $c -= $lr * $gradC;

        if (
            !is_finite($m1) ||
            !is_finite($m2) ||
            !is_finite($c) ||
            abs($m1) > 1e9 ||
            abs($m2) > 1e9 ||
            abs($c) > 1e9
        ) {
            return [
                'success' => false,
                'message' => 'Training divergen. Turunkan learning rate.',
                'states' => $states,
            ];
        }

        $evaluation = evaluateMultipleLinear($points, $m1, $m2, $c);

        if (!is_finite($evaluation['mse']) || $evaluation['mse'] > 1e18) {
            return [
                'success' => false,
                'message' => 'MSE menjadi terlalu besar. Turunkan learning rate.',
                'states' => $states,
            ];
        }

        $states[] = [
            'epoch' => $i,
            'm1' => $m1,
            'm2' => $m2,
            'c' => $c,
            'mse' => $evaluation['mse'],
            'r2' => $evaluation['r2'],
            'grad_m1' => $gradM1,
            'grad_m2' => $gradM2,
            'grad_c' => $gradC,
            'prev_m1' => $prevM1,
            'prev_m2' => $prevM2,
            'prev_c' => $prevC,
            'predictions' => $evaluation['predictions'],
        ];
    }

    return [
        'success' => true,
        'message' => null,
        'states' => $states,
    ];
}