<?php
// Dataset: Iris features and classes (limited for simplicity)
$data = [
    ['x' => [5.1, 3.5, 1.4, 0.2], 'y' => 1], // Iris-setosa
    ['x' => [7.0, 3.2, 4.7, 1.4], 'y' => -1], // Iris-versicolor
    ['x' => [5.4, 3.9, 1.7, 0.4], 'y' => 1], // Iris-setosa
    ['x' => [6.4, 3.2, 4.5, 1.5], 'y' => -1], // Iris-versicolor
];

// Hyperparameters
$learningRate = 0.01;
$epochs = 1000;
$lambda = 0.01; // Regularization parameter

// Initialize weights and bias
$w = [0, 0, 0, 0]; // One weight for each feature
$b = 0;            // Bias term

// Dot product function
function dotProduct($x1, $x2) {
    if (count($x1) !== count($x2)) {
        throw new Exception("Vectors must be of the same dimension");
    }

    $dotProduct = 0;
    for ($i = 0; $i < count($x1); $i++) {
        $dotProduct += $x1[$i] * $x2[$i];
    }
    return $dotProduct;
}

// Training the SVM
for ($epoch = 0; $epoch < $epochs; $epoch++) {
    foreach ($data as $sample) {
        $x = $sample['x']; // Features
        $y = $sample['y']; // Label (-1 or 1)
        
        // Calculate prediction
        $output = dotProduct($w, $x) + $b;
        
        // Check if sample is misclassified
        if ($y * $output < 1) {
            // Update weights and bias for misclassified samples
            for ($i = 0; $i < count($w); $i++) {
                $w[$i] += $learningRate * ($y * $x[$i] - 2 * $lambda * $w[$i]);
            }
            $b += $learningRate * $y;
        } else {
            // Update weights with regularization for correctly classified samples
            for ($i = 0; $i < count($w); $i++) {
                $w[$i] -= $learningRate * 2 * $lambda * $w[$i];
            }
        }
    }
}

// Prediction function
function predict($x, $w, $b) {
    $output = dotProduct($w, $x) + $b;
    return $output > 0 ? 1 : -1;
}

// Test the SVM with new samples
$testSamples = [
    [5.1, 3.8, 1.5, 0.3], // Iris-setosa
    [6.5, 2.8, 4.6, 1.5], // Iris-versicolor
];

foreach ($testSamples as $testSample) {
    $prediction = predict($testSample, $w, $b);
    echo "Features: (" . implode(", ", $testSample) . ") => Prediction: " . ($prediction == 1 ? "Iris-setosa" : "Iris-versicolor") . PHP_EOL;
}
?>
