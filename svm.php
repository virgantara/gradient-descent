<?php
// Fungsi untuk memuat data dari file iris.data
function load_data($filename) {
    $dataset = [];
    if (!file_exists($filename)) {
        throw new Exception("File $filename tidak ditemukan.");
    }

    // Buka file
    $file = fopen($filename, 'r');
    if (!$file) {
        throw new Exception("Gagal membuka file $filename.");
    }

    // Baca baris demi baris
    while (($line = fgetcsv($file)) !== false) {
        // Setiap baris adalah array; tambahkan ke dataset
        if (count($line) === 5) { // Pastikan ada 5 kolom
            $dataset[] = [
                (float)$line[0], // Fitur 1
                (float)$line[1], // Fitur 2
                (float)$line[2], // Fitur 3
                (float)$line[3], // Fitur 4
                trim($line[4])   // Label kelas
            ];
        }
    }

    fclose($file); // Tutup file
    return $dataset;
}

// Fungsi normalisasi dataset
function normalize($data) {
    $normalized = [];
    $min = $max = [];
    foreach ($data[0] as $key => $value) {
        if (!is_numeric($value)) continue;
        $min[$key] = min(array_column($data, $key));
        $max[$key] = max(array_column($data, $key));
    }
    foreach ($data as $row) {
        $normalizedRow = [];
        foreach ($row as $key => $value) {
            if (is_numeric($value)) {
                $normalizedRow[$key] = ($value - $min[$key]) / ($max[$key] - $min[$key]);
            } else {
                $normalizedRow[$key] = $value;
            }
        }
        $normalized[] = $normalizedRow;
    }
    return $normalized;
}

function linear_kernel($x1, $x2) {
    // Periksa apakah panjang kedua vektor sama
    if (count($x1) !== count($x2)) {
        throw new Exception("Panjang vektor tidak sama, tidak dapat menghitung kernel linier.");
    }

    // Inisialisasi hasil dot product
    $dot_product = 0;

    // Hitung dot product secara manual
    for ($i = 0; $i < count($x1); $i++) {
        $dot_product += $x1[$i] * $x2[$i];
    }

    // Kembalikan hasil dot product
    return $dot_product;
}

function predict($x, $weights, $bias) {
    // Periksa apakah panjang vektor fitur dan bobot sama
    if (count($x) !== count($weights)) {
        throw new Exception("Jumlah fitur dan bobot tidak sama, tidak dapat memprediksi.");
    }

    // Inisialisasi prediksi awal
    $result = 0;

    // Hitung perkalian setiap fitur dengan bobotnya
    for ($i = 0; $i < count($x); $i++) {
        $result += $x[$i] * $weights[$i];
    }

    // Tambahkan bias ke hasil prediksi
    $result += $bias;

    // Kembalikan hasil prediksi
    return $result;
}


// Fungsi untuk melatih model SVM One-vs-Rest
function train_ovr($train_data, $label, $learning_rate, $epochs, $lambda=0.01) {
    $weights = [0, 0, 0, 0]; // Sesuai dengan jumlah fitur
    $bias = 0;

    foreach (range(1, $epochs) as $epoch) {
        foreach ($train_data as $data) {
            $features = array_slice($data, 0, 4);
            $y = ($data[4] === $label) ? 1 : -1;

            $prediction = predict($features, $weights, $bias);

            if ($y * $prediction < 1) {
                foreach ($weights as $key => $weight) {
                    $weights[$key] += $learning_rate * ($y * $features[$key] - $lambda * $weight);
                }
                $bias += $learning_rate * $y;
            } else {
                foreach ($weights as $key => $weight) {
                    $weights[$key] -= $learning_rate * $lambda * $weight;
                }
            }
        }
    }

    return [$weights, $bias];
}

// Load dan normalisasi dataset
try {
    $filename = "iris.data";
    $dataset = load_data($filename);
    $dataset = normalize($dataset);

    // Split dataset menjadi train dan test
    $train_size = (int)(0.8 * count($dataset));
    $train_data = array_slice($dataset, 0, $train_size);
    $test_data = array_slice($dataset, $train_size);

    // Daftar label kelas
    $classes = ['Iris-setosa', 'Iris-versicolor', 'Iris-virginica'];

    // Training untuk setiap kelas (One-vs-Rest)
    $models = [];
    $lambda = 0.01;
    $learning_rate = 0.03;
    foreach ($classes as $class) {
        // echo "Melatih model untuk kelas: $class<br>";
        [$weights, $bias] = train_ovr($train_data, $class, $learning_rate, 1000,$lambda);
        $models[$class] = ['weights' => $weights, 'bias' => $bias];
    }

    // Evaluasi dengan data uji
    $correct_predictions = 0;
    foreach ($test_data as $data) {
        $features = array_slice($data, 0, 4);

        // Hitung skor untuk setiap model
        $scores = [];
        foreach ($models as $class => $model) {
            $scores[$class] = predict($features, $model['weights'], $model['bias']);
        }

        // Pilih kelas dengan skor tertinggi
        $predicted_class = array_keys($scores, max($scores))[0];
        $actual_class = $data[4];

        if ($predicted_class === $actual_class) {
            $correct_predictions++;
        }
    }

    // Hitung akurasi
    $accuracy = $correct_predictions / count($test_data);
    echo "Akurasi: " . ($accuracy * 100) . "%\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}