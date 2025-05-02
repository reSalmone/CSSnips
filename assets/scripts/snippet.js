var currentLang = "html";
function copyToClipboard() {
    const areas = {
        html: document.getElementById('html-area'),
        css: document.getElementById('css-area'),
        js: document.getElementById('js-area')
    };

    navigator.clipboard.writeText(areas[currentLang].innerHTML);

    let copySpan = document.getElementById("copy-span");
    if (copySpan.innerText === "Copy") {
        copySpan.innerText = "Copied";
        setTimeout(function () {
            copySpan.innerText = "Copy";
        }, 1000);
    }
}

const lineNumbers = document.getElementById('line-numbers');

function updateLines(area) {
    const lines = area.textContent.split(/<div>|<br>/i).length;
    lineNumbers.textContent = '';
    for (let i = 1; i <= lines; i++) {
        const line = document.createElement('div');
        line.textContent = i;
        lineNumbers.appendChild(line);
    }
}

function syncScroll(area) {
    lineNumbers.scrollTop = area.scrollTop;
}

function switchLang(lang) {
    currentLang = lang;
    const areas = {
        html: document.getElementById('html-area'),
        css: document.getElementById('css-area'),
        js: document.getElementById('js-area')
    };

    const buttons = {
        html: document.getElementById('html-button'),
        css: document.getElementById('css-button'),
        js: document.getElementById('js-button')
    };

    Object.keys(areas).forEach(type => {
        areas[type].style.display = 'none';
        buttons[type].classList.remove('active');
    });

    if (areas[lang] && buttons[lang]) {
        areas[lang].style.display = 'block';
        buttons[lang].classList.add('active');
        updateLines(areas[lang]);
        syncScroll(areas[lang]);
    }
}
switchLang("html");