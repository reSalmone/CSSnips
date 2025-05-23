var currentLang = "html";
var saved = true;
let elementType = "button";


const areas = {
    html: document.getElementById('html-area'),
    css: document.getElementById('css-area'),
    js: document.getElementById('js-area')
};

function copyToClipboard() {
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
                    overflow: hidden;
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

let tags = [];

document.getElementById("tags-input").addEventListener("keydown", function (event) {
    if (event.key === " " || event.key == "Enter") {
        event.preventDefault();
        addTag();
    }
});

function addTag() {
    const input = document.getElementById("tags-input");
    if (tags.length < 10) {
        const tag = capitalizeProper(input.value.trim());
        if (tag !== "" && !tags.includes(tag)) {
            tags.push(tag);
            renderTags();
            input.value = "";
        } else {
            input.value = "";
        }
    }
    input.focus;
    saved = false;
}

function removeTag(index) {
    tags.splice(index, 1);
    renderTags();
}

function renderTags() {
    const tagList = document.getElementById("tags-list");
    tagList.innerHTML = "";
    Array.from(tags).forEach((tag, index) => {
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

const dropdownDiv = document.querySelector('.type-dropdown-div');
const dropdownContent = document.querySelector('.type-dropdown-container');

dropdownDiv.addEventListener('click', function () {
    dropdownContent.style.display = 'block';
});

dropdownDiv.addEventListener('mouseleave', function () {
    dropdownContent.style.display = 'none';
});

function setElementType(button, event) {
    elementType = button.innerText.toLowerCase();
    event.stopPropagation();
    updateCurrentTypeButton();
    dropdownContent.style.display = 'none';
}

function updateCurrentTypeButton() {
    let currentTypeButton = document.getElementById("current-type");
    currentTypeButton.innerText = "Type: " + elementType;
    saved = false;
}


function randomString(length) {
    const letters = 'abcdefghijklmnopqrstuvwxyz';
    let result = '';
    for (let i = 0; i < length; i++) {
        result += letters.charAt(Math.floor(Math.random() * letters.length));
    }
    return result;
}

function openPost(event) {
    closeDrafts();
    closeLoad();
    event.stopPropagation();
    document.getElementById('post-center-div').style.display = 'block';
    document.getElementById('rest').style.filter = 'brightness(50%)';

    let output = document.getElementById('output');
    let postOutput = document.getElementById('post-preview');
    const srcDoc = output.contentDocument || output.contentWindow.document;
    const dstDoc = postOutput.contentDocument || postOutput.contentWindow.document;
    dstDoc.open();
    dstDoc.write(srcDoc.documentElement.outerHTML);
    dstDoc.close();

    let name = document.getElementById('post-name');
    name.placeholder = randomString(10);

    let type = document.getElementById('current-type');
    let postType = document.getElementById('post-type');
    postType.innerText = type.innerText;

    let description = document.getElementById('description-area');
    let postDescription = document.getElementById('post-description-content');
    postDescription.innerText = description.value;

    let tags = document.getElementById('tags-list');
    let posttags = document.getElementById('post-tags-list');

    posttags.innerHTML = "";
    Array.from(tags.children).forEach(tag => {
        let newTag = tag.cloneNode(true);
        newTag.classList.remove("tags-tag");
        newTag.classList.add("post-tags-tag");
        posttags.appendChild(newTag);
    });

    checkNameAvailability();
}

function checkNameAvailability() {
    let checkname = '';
    let name = document.getElementById('post-name');
    if (name.value != '') {
        checkname = name.value;
    } else {
        checkname = name.placeholder;
    }

    if (!/^[a-zA-Z0-9_]{3,16}$/.test(checkname)) {
        displayAvailability(false);
        return;
    }

    fetch('checkname.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `name=${encodeURIComponent(checkname)}`
    })
        .then(response => response.json())
        .then(data => {
            displayAvailability(data.success);
            if (data.success) {
                hidePostServerError();
            }
        });
}

function displayAvailability(available) {
    if (available) {
        document.getElementById('post-name-check-success').style.display = 'block';
        document.getElementById('post-name-check-failure').style.display = 'none';

        document.getElementById('post-name').style.border = "2px solid rgb(100, 255, 100)";
    } else {
        document.getElementById('post-name-check-success').style.display = 'none';
        document.getElementById('post-name-check-failure').style.display = 'block';

        document.getElementById('post-name').style.border = "2px solid rgb(255, 100, 100)";
    }
}

function closePost() {
    document.getElementById('post-center-div').style.display = 'none';
    document.getElementById('rest').style.filter = 'brightness(100%)';
}

function saveDraft(name) {
    const html = document.getElementById("html-area").value;
    const css = document.getElementById("css-area").value;
    const js = document.getElementById("js-area").value;

    let postname = null;
    if (name != '') {
        postname = name;
    } else {
        postname = randomString(16);
    }
    let postType = document.getElementById('current-type').innerText.split(':')[1]?.trim();
    let postDescription = document.getElementById('description-area').value;
    let posttagslist = document.getElementById('tags-list');
    let postTags = Array.from(posttagslist.children).map(child => child.innerText.trim());
    let postVariationName = document.getElementById('variation-name');
    let postChallengeName = document.getElementById('challenge-name');

    let completeHTML = `
<!DOCTYPE html>
<html>
<head><style>${css}</style></head>
<body>${html}<script>${js}<\/script></body>
</html>`;

    let file = new File([completeHTML], "snippet.html", { type: "text/html" });

    let formData = new FormData();
    formData.append("postFile", file);
    formData.append("postName", postname);
    formData.append("postType", postType);
    formData.append("postDescription", postDescription);
    formData.append("postTags", JSON.stringify(postTags));
    if (postVariationName) {
        formData.append("postVariation", postVariationName.textContent);
    }
    if (postChallengeName) {
        formData.append("postChallenge", postChallengeName.textContent);
    }

    fetch("upload-draft.php", {
        method: "POST",
        body: formData
    }).then(res => res.json())
        .then(data => {
            if (data.success) {
                saved = true;
                location.href = 'creator.php?draft=' + postname;
            } else {
                alert('error: ' + data.error);
            }
        });

}

function deleteDraft(name) {

    fetch("delete-draft.php?name=" + name, {
        method: "GET",
    }).then(res => res.json())
        .then(data => {
            if (data.success) {
                console.log(data.id);
                document.getElementById("drafts-output-snip-" + data.id).remove();
            } else {
                showPostServerError(data.error);
            }
        });

}

function saveChanges() {
    const html = document.getElementById("html-area").value;
    const css = document.getElementById("css-area").value;
    const js = document.getElementById("js-area").value;

    let postname = document.getElementById("editing-name").innerText.toLowerCase();
    let postType = document.getElementById('current-type').innerText.split(':')[1]?.trim();;
    let postDescription = document.getElementById('description-area').value;
    let posttagslist = document.getElementById('tags-list');
    let postTags = Array.from(posttagslist.children).map(child => child.innerText.trim());

    let completeHTML = `
<!DOCTYPE html>
<html>
<head><style>${css}</style></head>
<body>${html}<script>${js}<\/script></body>
</html>`;

    let file = new File([completeHTML], "snippet.html", { type: "text/html" });

    let formData = new FormData();
    formData.append("postFile", file);
    formData.append("postName", postname);
    formData.append("postType", postType);
    formData.append("postDescription", postDescription);
    formData.append("postTags", JSON.stringify(postTags));

    fetch("upload-changes.php", {
        method: "POST",
        body: formData
    }).then(res => res.json())
        .then(data => {
            if (data.success) {
                localStorage.clear();
                //tell in some way to snippet that the changes have been made
                location.href = "snippet.php?name=" + postname;
            } else {
                console.log(data.error);
            }
        });
}

function postSnippet() {
    const html = document.getElementById("html-area").value;
    const css = document.getElementById("css-area").value;
    const js = document.getElementById("js-area").value;

    let postname = document.getElementById("post-name").value.toLowerCase();
    if (postname == "") {
        postname = document.getElementById("post-name").placeholder;
        console.log("name:" + postname);
    }
    let postType = document.getElementById('post-type').innerText.split(':')[1]?.trim();;
    let postDescription = document.getElementById('post-description-content').innerText;
    let posttagslist = document.getElementById('post-tags-list');
    const postTags = Array.from(posttagslist.children).map(child => child.innerText.trim());
    let postVariationName = document.getElementById('variation-name');
    let postChallengeName = document.getElementById('challenge-name');

    const completeHTML = `
<!DOCTYPE html>
<html>
<head><style>${css}</style></head>
<body>${html}<script>${js}<\/script></body>
</html>`;

    const file = new File([completeHTML], "snippet.html", { type: "text/html" });

    const formData = new FormData();
    formData.append("postFile", file);
    formData.append("postName", postname);
    formData.append("postType", postType);
    formData.append("postDescription", postDescription);
    formData.append("postTags", JSON.stringify(postTags));
    if (postVariationName) {
        formData.append("postVariation", postVariationName.textContent);
    }
    if (postChallengeName) {
        formData.append("postChallenge", postChallengeName.textContent);
    }

    fetch("upload.php", {
        method: "POST",
        body: formData
    }).then(res => res.json())
        .then(data => {
            if (data.success) {
                saved = true;
                location.href = "snippet.php?name=" + postname;
            } else {
                showPostServerError(data.error);
            }
        });

}

function showPostServerError(postError) {
    let errorBox = document.getElementById("post-server-error");
    let errorSnip = document.createElement("span");
    errorSnip.textContent = postError;
    errorBox.innerHTML = '';
    errorBox.appendChild(errorSnip);
    errorBox.style.display = "block";
}

function hidePostServerError() {
    let errorBox = document.getElementById("post-server-error");
    errorBox.innerHTML = '';
    errorBox.style.display = "none";
}

function openLoad() {
    document.getElementById('load-center-div').style.display = 'block';
    document.getElementById('rest').style.filter = 'brightness(30%)';
}

function closeLoad() {
    document.getElementById('load-center-div').style.display = 'none';
    document.getElementById('rest').style.filter = 'brightness(100%)';
}

function openDrafts(event) {
    closeLoad();
    closePost();
    event.stopPropagation();
    document.getElementById('drafts-center-div').style.display = 'block';
    document.getElementById('rest').style.filter = 'brightness(30%)';
}

function closeDrafts() {
    document.getElementById('drafts-center-div').style.display = 'none';
    document.getElementById('rest').style.filter = 'brightness(100%)';
}

function assignIFrame(iframeID, html, css, js) {
    html = html != null ? html : ""
    css = css != null ? css : ""
    js = js != null ? js : ""
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
                    border: 0;
                    outline: 0;
                    overflow: hidden;
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

    let output = document.getElementById(iframeID);
    output.srcdoc = completeDocument;
}

document.addEventListener("DOMContentLoaded", () => {
    const els = document.querySelectorAll('.drafts-output-snip');
    const ids = Array.from(els, el => el.dataset.snippetId).map(Number);

    if (!ids.length) return;

    fetch(`load_snippets.php?ids=${ids.join(',')}&draft=true`)
        .then(r => r.json())
        .then(snippets => {
            snippets.forEach(s => {
                const iframeId = 'drafts-output-snip-frame-' + s.id;
                const loaderID = 'drafts-output-loader-' + s.id;
                assignIFrame(iframeId, s.html, s.css, s.js);
                document.getElementById(loaderID).style.display = "none";
            });
        })
        .catch(err => console.error('Batch load error:', err));
});

function updateUrlAndDirect(key, value) {
    const url = new URL(window.location.href);
    url.searchParams.set(key, value);
    window.location.href = url.toString();
}

window.addEventListener('beforeunload', function (e) {
    if (!saved) {
        e.preventDefault();
        e.returnValue = '';
    }
});

document.querySelectorAll('form.form-form').forEach(form => {
    form.addEventListener('submit', () => {
        saveInLocalStorage();
        saved = true;
    });
});

function saveInLocalStorage() {
    const html = document.getElementById("html-area").value;
    const css = document.getElementById("css-area").value;
    const js = document.getElementById("js-area").value;

    let description = document.getElementById("description-area").value;
    let postVariationName = document.getElementById('variation-name');
    let postChallengeName = document.getElementById('challenge-name');

    localStorage.setItem("unsaved-html", html);
    localStorage.setItem("unsaved-css", css);
    localStorage.setItem("unsaved-js", js);

    localStorage.setItem("unsaved-type", elementType);
    localStorage.setItem("unsaved-description", description);
    localStorage.setItem("unsaved-tags", tags);

    if (postVariationName) {
        document.cookie = "variationCookie=" + postVariationName.textContent + "; path=/";
    }
    if (postChallengeName) {
        document.cookie = "challengeCookie=" + postChallengeName.textContent + "; path=/";
    }
}

function restoreLocalStorage() {
    Object.keys(areas).forEach(type => {
        const savedCode = localStorage.getItem("unsaved-" + type);
        localStorage.removeItem("unsaved-" + type);
        if (savedCode) {
            areas[type].value = savedCode;
        }
        updateLines(areas[type]);
    });
    displayCode();

    localType = localStorage.getItem("unsaved-type");
    localDescription = localStorage.getItem("unsaved-description");
    localTags = localStorage.getItem("unsaved-tags");
    localStorage.removeItem("unsaved-type");
    localStorage.removeItem("unsaved-description");
    localStorage.removeItem("unsaved-tags");

    if (localType) {
        elementType = localType;
        updateCurrentTypeButton();
    }
    if (localDescription) {
        document.getElementById("description-area").value = localDescription;
    }
    if (localTags) {
        tags = localTags.split(',');
        renderTags();
    }
}

switchLang("html");
displayCode();

var loadedFromStorage = false;
if (localStorage.getItem("unsaved-html")) {
    restoreLocalStorage();
    loadedFromStorage = true;
}