<?php

function sigmoid($x){
	return 1 / (1 + exp(-$x));
}

function deriv_sigmoid($x){
	$fx = sigmoid($x);
	return $fx * (1 - $fx);
}
		//  1 2 3 4 5 6
$weights = [
	0, // w1
	1, // w2
	0, // w3
	1, // w4
	0, // w5
	1  // w6
];

$bias = [0,0,0];


$lr = 0.1;
$epochs = 1000;

$all_y = [1,0,0,1];
$X = [
	[-2, -1],
	[25, 6],
	[17, 4],
	[-15, -6]
];
echo '<pre>';

echo "Old Bobot: ";
print_r($weights);
echo "<br>";
echo "Old Bias: ";
print_r($bias);
echo "<br>";

for($epoch=0;$epoch<=$epochs;$epoch++){
	foreach($X as $q => $x){
		$y_true = $all_y[$q];
		
		$sum_h1 = $x[0] * $weights[0] + $x[1] * $weights[1] + $bias[0];
		$h1 = sigmoid($sum_h1);

		$sum_h2 = $x[0] * $weights[2] + $x[1] * $weights[3] + $bias[1];
		$h2 = sigmoid($sum_h2);		

		$sum_o1 = $h1 * $weights[4] + $h2 * $weights[5] + $bias[2];
		$o1 = sigmoid($sum_o1);

		$y_pred = $o1;

		// Backpropagasi (backpro)
		// backpro dimulai dari belakang (dari node o1)
		$d_L_d_ypred = -2 * ($y_true - $y_pred);

		$d_yp_d_h1 = $weights[4] * deriv_sigmoid($sum_o1);
		$d_yp_d_h2 = $weights[5] * deriv_sigmoid($sum_o1);

		// di node o1 -> dipengaruhi oleh w5 dan w6 maka
		$d_yp_d_w5 = $h1 * deriv_sigmoid($sum_o1);
		$d_yp_d_w6 = $h2 * deriv_sigmoid($sum_o1);
		$d_yp_d_b3 = deriv_sigmoid($sum_o1);

		// node h1
		$d_h1_d_w1 = $x[0] * deriv_sigmoid($sum_h1);
		$d_h1_d_w2 = $x[1] * deriv_sigmoid($sum_h1);
		$d_h1_d_b1 = deriv_sigmoid($sum_h1);

		// node h2
		$d_h2_d_w3 = $x[0] * deriv_sigmoid($sum_h2);
		$d_h2_d_w4 = $x[1] * deriv_sigmoid($sum_h2);
		$d_h2_d_b2 = deriv_sigmoid($sum_h2);

		// update weight dan bias
		$weights[0] = $weights[0] - ($lr * $d_L_d_ypred * $d_yp_d_h1 * $d_h1_d_w1);
		$weights[1] = $weights[1] - ($lr * $d_L_d_ypred * $d_yp_d_h1 * $d_h1_d_w2);
		$bias[0] = $bias[0] - ($lr * $d_L_d_ypred * $d_yp_d_h1 * $d_h1_d_b1);

		$weights[2] = $weights[2] - ($lr * $d_L_d_ypred * $d_yp_d_h2 * $d_h2_d_w3);
		$weights[3] = $weights[3] - ($lr * $d_L_d_ypred * $d_yp_d_h2 * $d_h2_d_w4);
		$bias[1] = $bias[1] - ($lr * $d_L_d_ypred * $d_yp_d_h2 * $d_h2_d_b2);

		$weights[4] = $weights[4] - ($lr * $d_L_d_ypred * $d_yp_d_w5);
		$weights[5] = $weights[5] - ($lr * $d_L_d_ypred * $d_yp_d_w6);
		$bias[2] = $bias[2] - ($lr * $d_L_d_ypred * $d_yp_d_b3);

	}
}

echo "New Bobot: ";
print_r($weights);
echo "<br>";
echo "New Bias: ";
print_r($bias);
echo "<br>";
echo '</pre>';

exit;