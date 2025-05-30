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

for (let i = 0; i < numberOfCurves; i++) {
    curves.push({
        offset: Math.random() * 500,
        speed: Math.random() + 0.5,
        direction: Math.random() < 0.5 ? 1 : -1,
        y: (canvas.height / numberOfCurves) * (numberOfCurves - i - 1) + (canvas.height / numberOfCurves) / 2,
        frequency: Math.round(canvas.width / 400), //how many waves there are
        amplitude: Math.random() * canvas.height / numberOfCurves, //how high they reach
    });
}

function animate() {
    context.clearRect(0, 0, canvas.width, canvas.height);

    curves.forEach(c => {
        let x = -c.offset;
        let step = canvas.width / c.frequency;
        let b = false;

        context.beginPath();
        context.moveTo(x, c.y);
        while (x < canvas.width) {
            let controlX = x + step / 2;
            let controlY = c.y + c.amplitude * (b ? -1 : 1);
            let endX = x + step;
            let endY = c.y;

            context.quadraticCurveTo(controlX, controlY, endX, endY);
            x += step;
            b = !b;
        }

        c.offset += c.speed * c.direction;

        if (c.offset < 0) {
            c.offset += step * 2;
        }

        context.lineTo(canvas.width, 0);
        context.lineTo(0, 0);
        color = getComputedStyle(canvas.parentElement).backgroundColor;
        alpha = curves.indexOf(c) === numberOfCurves - 1 ? 1 : 3 / (numberOfCurves + 2);
        context.fillStyle = setAlpha(color, alpha);
        context.fill();
    });

    requestAnimationFrame(animate);
}

function setAlpha(color, alpha) {
    alpha = Math.max(0, Math.min(1, alpha)); //clamp alpha to 1

    //if color is in rgba form
    const rgbaMatch = color.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)/i);
    if (rgbaMatch) {
        const [_, r, g, b] = rgbaMatch;
        return `rgba(${r}, ${g}, ${b}, ${alpha})`;
    }

    //if color is in hex
    const hexMatch = color.match(/^#([a-f\d]{3,6})$/i);
    if (hexMatch) {
        let hex = hexMatch[1];
        if (hex.length === 3) {
            hex = hex.split('').map(ch => ch + ch).join('');
        }
        const bigint = parseInt(hex, 16);
        const r = (bigint >> 16) & 255;
        const g = (bigint >> 8) & 255;
        const b = bigint & 255;
        return `rgba(${r}, ${g}, ${b}, ${alpha})`;
    }

    //else
    return color;
}

animate();