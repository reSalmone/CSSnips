const canvas = document.getElementById("first-container-canvas");
const context = canvas.getContext("2d");

var numberOfCurves = 5;
var curveColor = getComputedStyle(canvas.parentElement).backgroundColor;
var curves = [];

function resizeCanvas() {
    canvas.width = canvas.parentElement.clientWidth;
}
window.addEventListener("resize", resizeCanvas);
resizeCanvas();

let offset = 0;
let y = canvas.height / 2;
let frequency = 5;
let amplitude = 50;
let speed = 1;
let direction = -1;

function animate() {
    context.clearRect(0, 0, canvas.width, canvas.height);
    let x = -offset;
    let step = canvas.width / frequency;
    let b = false;

    context.beginPath();
    context.moveTo(x, y);
    while (x < canvas.width) {
        let controlX = x + step / 2;
        let controlY = y + amplitude * (b ? -1 : 1);
        let endX = x + step;
        let endY = y;

        context.quadraticCurveTo(controlX, controlY, endX, endY);
        x += step;
        b = !b;
    }

    offset += speed * direction;

    if (offset < 0) {
        offset += step * 2;
    }

    context.lineTo(canvas.width, 0);
    context.lineTo(0, 0);
    context.fillStyle = getComputedStyle(canvas.parentElement).backgroundColor;
    context.fill();

    requestAnimationFrame(animate);
}
animate();