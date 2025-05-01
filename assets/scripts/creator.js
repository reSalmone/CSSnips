var currentLang = "html";
function copyToClipboard() {
    const areas = {
        html: document.getElementById('html-area'),
        css: document.getElementById('css-area'),
        js: document.getElementById('js-area')
    };

    navigator.clipboard.writeText(areas[currentLang].value);

    let copySpan = document.getElementById("copy-span");
    if (copySpan.innerText === "Copy") {
        copySpan.innerText = "Copied";
        setTimeout(function () {
            copySpan.innerText = "Copy";
        }, 1000);
    }
}

function displayCode() {
    const html = document.getElementById('html-area').value;
    const css = document.getElementById('css-area').value;
    const js = document.getElementById('js-area').value;

    const completeDocument = `
            <!DOCTYPE html>
            <html>
            <head>
              <link rel="stylesheet" href="assets/NoveoSans-Book/style.css">
              <style>
                * {
                    font-family: "Noveo Sans";
                }
                body {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    height: 100vh;
                    margin: 0;
                }
                ${css}
              </style>
            </head>
            <body>
              ${html}
              <script>
                ${js}
              <\/script>
            </body>
            </html>
        `;

    let output = document.getElementById("output");
    output.srcdoc = completeDocument;
}

function insertTab(e, textArea) {
    if (e.key === 'Tab') {
        e.preventDefault();

        const start = textArea.selectionStart;
        const end = textArea.selectionEnd;

        textArea.value = textArea.value.substring(0, start) + '\t' + textArea.value.substring(end);
        textArea.selectionStart = textArea.selectionEnd = start + 1;
    }
}

const lineNumbers = document.getElementById('line-numbers');

function updateLines(area) {
    const lines = area.value.split('\n').length;
    lineNumbers.innerHTML = '';
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

const tags = [];

document.getElementById("tags-input").addEventListener("keydown", function (event) {
    if (event.key === " " || event.key == "Enter") {
        event.preventDefault();
        addTag();
    }
});

function addTag() {
    const input = document.getElementById("tags-input");
    const tag = capitalizeProper(input.value.trim());
    if (tag !== "" && !tags.includes(tag)) {
        tags.push(tag);
        renderTags();
        input.value = "";
    } else {
        input.value = "";
    }
    input.focus;
}

function removeTag(index) {
    tags.splice(index, 1);
    renderTags();
}

function renderTags() {
    const tagList = document.getElementById("tags-list");
    tagList.innerHTML = "";
    tags.forEach((tag, index) => {
        const el = document.createElement("div");
        el.className = "tags-tag";
        el.innerText = tag;
        el.onclick = () => removeTag(index);
        tagList.appendChild(el);
    });
}



function capitalizeProper(str) {
    if (!str) return str;
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
}

let elementType = "button";

const dropdownDiv = document.querySelector('.type-dropdown-div');
const dropdownContent = document.querySelector('.type-dropdown-container');

dropdownDiv.addEventListener('click', function () {
    dropdownContent.style.display = 'block';
});

dropdownDiv.addEventListener('mouseleave', function () {
    dropdownContent.style.display = 'none';
});

function setElementType(button) {
    elementType = button.innerText.toLowerCase();
    updateCurrentTypeButton();
    dropdownContent.style.display = 'none';
}

function updateCurrentTypeButton() {
    let currentTypeButton = document.getElementById("current-type");
    currentTypeButton.innerText = "Type: " + elementType;
}
updateCurrentTypeButton();





function openPost() {
}