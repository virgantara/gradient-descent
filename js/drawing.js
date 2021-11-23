
function drawCartesian(ctx, w, h){
	// ctx.translate(w/2, h/2);
       
	let lebar = w / 2
	let tinggi = h / 2


        // Add some lines
    ctx.beginPath();

    ctx.strokeStyle = '#000000';
    ctx.lineWidth = 2;
    // draw X-axis
    ctx.moveTo(-(lebar),tinggi);
    ctx.lineTo(w,tinggi);
    ctx.stroke();

    // draw Y-axis
    ctx.moveTo(lebar,0);
    ctx.lineTo(lebar,h);
    ctx.stroke();     
    ctx.fillStyle = '#0000FF';
    ctx.font = "20px Arial";
    ctx.fillText("+ x",w - 30, tinggi - 10);
    ctx.fillText("- y",lebar - 30, h - 10);
    
    ctx.fillText("- x",10, tinggi - 10);
    ctx.fillText("+ y",lebar + 10, 20);
}

function drawCartesianPoint(ctx, x, y) {
	x = (C_WIDTH / 2) + x;
	y = (C_HEIGHT / 2) - y
	ctx.fillStyle = '#00A300';
    ctx.fillRect(x, (y), 10, 10); 

}

function drawCartesianLine(ctx, x1, y1, x2, y2) {
	x1 = (C_WIDTH / 2) + x1
	y1 = (C_HEIGHT / 2) - y1
	x2 = (C_WIDTH / 2) + x2
	y2 = (C_HEIGHT / 2) - y2
    
    ctx.beginPath();
    ctx.strokeStyle = '#ff0000';
  	ctx.moveTo(x1,y1);
  	ctx.lineTo(x2,y2);
  	ctx.lineWidth = 2;
  	ctx.stroke();

}

function drawCartesianText(ctx, x, y, text) {
    ctx.fillText(text , x, -(y));  
}
