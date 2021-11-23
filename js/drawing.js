const C_X_LIMIT = 10
const C_Y_LIMIT = 10
const C_WIDTH = 800
const C_HEIGHT = 800


function drawCartesian(ctx, w, h){
    // ctx.translate(w/2, h/2);
       
    let lebar = w / 2
    let tinggi = h - 20


        // Add some lines
    ctx.beginPath();

    ctx.strokeStyle = '#000000';
    ctx.lineWidth = 2;
    // draw X-axis
    ctx.moveTo(0,tinggi);
    ctx.lineTo(w,tinggi);
    ctx.stroke();

    // draw Y-axis
    ctx.moveTo(20,0);
    ctx.lineTo(20,h);
    ctx.stroke();     
    ctx.fillStyle = '#0000FF';
    ctx.font = "20px Arial";
    ctx.fillText("+ x",w - 40, tinggi - 10);
    // ctx.fillText("- y",lebar - 30, h - 10);
    
    // ctx.fillText("- x",10, tinggi - 10);
    ctx.fillText("+ y",20, 20);
}

function drawCartesianPoint(ctx, x, y) {
    x = 20 + x;
    y = (C_HEIGHT) - y
    ctx.fillStyle = '#00A300';
    ctx.fillRect(x, (y), 10, 10); 
    ctx.fillStyle = '#00A300';
    ctx.font = "12px Arial";
    ctx.fillText("("+x+","+y+")",x, y+23);
    
}

function drawCartesianLine(ctx, x1, y1, x2, y2) {
    x1 = 20 + x1
    y1 = (C_HEIGHT) - y1
    x2 = 20 + x2
    y2 = (C_HEIGHT) - y2
    
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
