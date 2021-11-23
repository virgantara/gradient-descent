<?php 
require_once "train.php";
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Simulation on Gradient Descent</title>
	<script src="node_modules/jquery/dist/jquery.min.js"></script>
	<script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
</head> 
<body>



<?php
$M = !empty($_GET['m']) ? $_GET['m'] : 0;
$C = !empty($_GET['c']) ? $_GET['c'] : 0;
$lr = !empty($_GET['lr']) ? $_GET['lr'] : 0.01;
$epoch = !empty($_GET['epoch']) ? $_GET['epoch'] : 1000;



$list_titik = [
	[0.5, 1.4],
	[2.3, 1.9],
	[2.9, 3.2]
];



$start_time = microtime(true); 
$model = train($list_titik, $lr, $epoch, $M, $C);
$end_time = microtime(true); 

// exit;  
// Calculate the script execution time 
$execution_time = ($end_time - $start_time); 

$c = $model['c'];
$m = $model['m'];

$r2 = $model['r2'];

$list_points = $model['list_points'];
$list_titik_converted = $model['list_titik_converted'];

$c = round($c,2);
$m = round($m,2);



?>

<div class="container">
	<h2>Simulation on Gradient Descent by Oddy Virgantara Putra</h2>
	<div class="row">
		<div class="col-md-5">
			
			    
				<form method="GET" action="index.php">
					<div class="mb-3">
						<label for="">Learning Rate</label>
						<input class="form-control" type="text" name="lr" value="<?=$lr;?>">
					</div>
					<div class="mb-3">
						<label for="">Epoch</label>
						<input class="form-control"  type="text" name="epoch" value="<?=$epoch;?>">

					</div>
					<div class="mb-3">
						<label for="">Initial M</label>
						<input class="form-control"  type="text" name="m" value="<?=$M;?>">

					</div>
					<div class="mb-3">
						<label for="">Initial C</label>
						<input class="form-control"  type="text" name="c" value="<?=$C;?>">

					</div>
					<div class="mb-3">
						<p>
						<button type="submit" class="btn btn-primary">Submit</button>
						</p>
					</div>
				</form>
				<ul>
				<?php 
				echo "<li>Durasi training: ".round($execution_time,8)." seconds</li>"; 
				echo "<li>Learning Rate = ".$lr."</li>";
				echo "<li>R<sup>2</sup> = ".$r2."</li>";
				echo "<li>c = ".$c."</li>";
				echo "<li>m = ".$m."</li>";

				echo "<li>y = ".$c." + ".$m."x</li>";
				 ?>
				 </ul>
			
		</div>
   		<div class="col-md-7">
   			<div width="100%" align="center">
				<canvas style="border: 1px solid blue;" id="cc" width="800" height="800"></canvas>	
			</div>
   		</div>
	
</div>


<script src="js/drawing.js"></script>

<script>

$(document).ready(function(){
	var list_titik = <?=json_encode($list_points);?>

	var posisi_titik = <?=json_encode($list_titik_converted);?>
	
	var thecanvas = document.getElementById("cc");



	var c = thecanvas.getContext("2d");
	
	var time = 500;
	
	
	$.each(list_titik, function(i,pt){
		

		setTimeout( function(){ 
			
		 	c.clearRect(0,0,C_WIDTH,C_HEIGHT);
			drawCartesian(c, C_WIDTH, C_HEIGHT)
			$.each(posisi_titik, function(j,obj){
		 		drawCartesianPoint(c, obj.x, obj.y)
		 	})

		 	var x1 = pt.startX * C_WIDTH / C_X_LIMIT
	 		var y1 = pt.startY * C_HEIGHT / C_Y_LIMIT
	 		var x2 = pt.endX * C_WIDTH / C_X_LIMIT
	 		var y2 = pt.endY * C_HEIGHT / C_Y_LIMIT
	 		drawCartesianLine(c,x1,y1, x2, y2)
			c.font = "30px Arial";
			c.fillStyle = '#000000';
			c.fillText("Iterasi: "+eval(i+1), x1 + 50,30); 

	// 		// draw(c,pt, i)
		}, time)

		time += 10		


	// 	// window.setInterval(function(){
	// 	// 	
	// 	// },50);	
	})
});

</script>

</body>
</html>
