<?php

define('C_X_LIMIT', 10);
define('C_Y_LIMIT', 10);

function regressionMetrics(array $points, float $m, float $c): array
{
    $n = count($points);

    if ($n === 0) {
        return [
            'sse' => 0.0,
            'mse' => 0.0,
            'mae' => 0.0,
            'r2' => null,
        ];
    }

    $meanY = array_sum(array_column($points, 1)) / $n;
    $sse = 0.0;
    $sst = 0.0;
    $mae = 0.0;

    foreach ($points as $pt) {
        $x = (float) $pt[0];
        $y = (float) $pt[1];
        $yPred = $c + $m * $x;
        $residual = $y - $yPred;
        $sse += $residual ** 2;
        $mae += abs($residual);
        $sst += ($y - $meanY) ** 2;
    }

    $mse = $sse / $n;
    $mae /= $n;
    $r2 = $sst > 1e-15 ? 1 - ($sse / $sst) : null;

    return [
        'sse' => $sse,
        'mse' => $mse,
        'mae' => $mae,
        'r2' => $r2,
    ];
}

function regressionGradient(array $points, float $m, float $c): array
{
    $n = count($points);

    if ($n === 0) {
        return [
            'dm' => 0.0,
            'dc' => 0.0,
        ];
    }

    $sumDm = 0.0;
    $sumDc = 0.0;

    foreach ($points as $pt) {
        $x = (float) $pt[0];
        $y = (float) $pt[1];
        $yPred = $c + $m * $x;
        $error = $y - $yPred;
        $sumDm += -2 * $x * $error;
        $sumDc += -2 * $error;
    }

    return [
        'dm' => $sumDm / $n,
        'dc' => $sumDc / $n,
    ];
}

function makeTrainingState(array $points, int $epoch, float $m, float $c, ?array $update = null): array
{
    $metrics = regressionMetrics($points, $m, $c);
    $gradient = regressionGradient($points, $m, $c);

    return [
        'epoch' => $epoch,
        'm' => $m,
        'c' => $c,
        'sse' => $metrics['sse'],
        'mse' => $metrics['mse'],
        'mae' => $metrics['mae'],
        'r2' => $metrics['r2'],
        'grad_m' => $gradient['dm'],
        'grad_c' => $gradient['dc'],
        'delta_m' => $update['delta_m'] ?? 0.0,
        'delta_c' => $update['delta_c'] ?? 0.0,
        'previous_m' => $update['previous_m'] ?? $m,
        'previous_c' => $update['previous_c'] ?? $c,
        'startX' => 0.0,
        'startY' => $c,
        'endX' => C_X_LIMIT,
        'endY' => $c + $m * C_X_LIMIT,
    ];
}

function train(array $points = [], float $lr = 0.01, int $epoch = 1000, float $m = 0.0, float $c = 0.0): array
{
    if (empty($points)) {
        return [
            'error' => 'Data titik tidak boleh kosong.',
            'states' => [],
        ];
    }

    if ($lr <= 0) {
        return [
            'error' => 'Learning rate harus lebih besar dari 0.',
            'states' => [],
        ];
    }

    $epoch = max(1, min(10000, $epoch));
    $states = [makeTrainingState($points, 0, $m, $c)];

    for ($i = 1; $i <= $epoch; $i++) {
        $gradient = regressionGradient($points, $m, $c);
        $previousM = $m;
        $previousC = $c;
        $deltaM = $lr * $gradient['dm'];
        $deltaC = $lr * $gradient['dc'];
        $m -= $deltaM;
        $c -= $deltaC;

        if (!is_finite($m) || !is_finite($c)) {
            return [
                'error' => 'Training divergen. Coba kecilkan learning rate.',
                'states' => $states,
            ];
        }

        $states[] = makeTrainingState($points, $i, $m, $c, [
            'previous_m' => $previousM,
            'previous_c' => $previousC,
            'delta_m' => -$deltaM,
            'delta_c' => -$deltaC,
        ]);
    }

    $final = end($states);

    return [
        'error' => null,
        'm' => $final['m'],
        'c' => $final['c'],
        'sse' => $final['sse'],
        'mse' => $final['mse'],
        'mae' => $final['mae'],
        'r2' => $final['r2'],
        'states' => $states,
        'points' => $points,
    ];
}
