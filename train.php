<?php

define("C_X_LIMIT", 10);
define("C_Y_LIMIT", 10);

function train($points = [], $lr = 0.01, $epoch=1000, $m = 0, $c = 0){

	$results = [];

	if(empty($points))
		return;

	$list_titik = $points;

	$list_titik_converted = [];

	foreach($list_titik as $pt){
		$p = [
			'x' => round($pt[0] / 10 * 800),
			'y' => round($pt[1] / 10 * 800)
		];

		$list_titik_converted[] = $p;
	}

	
	$list_points = [];
	$r2 = 0;
	for($i = 0; $i < $epoch;$i++){

		$sum_dssr_dc = 0;
		$sum_dssr_dm = 0;

		foreach($list_titik as $pt){
			$x = $pt[0];
			$yt = $pt[1];
			
			$dSSR_dc = -2 * ($yt - ($c + $m * $x));
			$dSSR_dm = -2 * $x * ($yt - ($c + $m * $x));

			$sum_dssr_dm += $dSSR_dm;
			$sum_dssr_dc += $dSSR_dc;

			$yp = $c + $m * $x;
			$r2 = abs($yt - $yp);
		}

		$step_size = $sum_dssr_dc * $lr;
		$c = $c - $step_size;

		$step_size = $sum_dssr_dm * $lr;
		$m = $m - $step_size;
		
		$start_X = 0;
		$start_Y = $c + $m * $start_X;

		$end_X = C_X_LIMIT;
		$end_Y = $c + $m * $end_X;

		$pt = [
			'startX' => $start_X,
			'startY' => $start_Y,
			'endX' => $end_X,
			'endY' => $end_Y
		];

		$list_points[] = $pt;
		
	}

	$results = [
		'c' => $c,
		'r2' => $r2,
		'm' => $m,
		'list_points' => $list_points,
		'list_titik_converted' => $list_titik_converted
	];

	return $results;
}