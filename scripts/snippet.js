var currentLang = "html";
const areas = {
    html: document.getElementById('html-area'),
    css: document.getElementById('css-area'),
    js: document.getElementById('js-area')
};

function copyToClipboard() {

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
function updateLines() {
    const activeArea = areas[currentLang];
    const lines = activeArea.innerText.split('\n').length;

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
        updateLines();
        syncScroll(areas[lang]);
    }
}
switchLang("html");
updateLines();

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

document.addEventListener('DOMContentLoaded', function () {
    const heart = document.getElementById('data-like-checkbox')
    if (heart) {
        heart.addEventListener('click', function () {
            const snippet = heart.getAttribute('data-snippet');

            fetch('handlers/like.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `snippet=${encodeURIComponent(snippet)}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('data-liked-value').innerText = data.value;
                    } else {
                        alert(data.error);
                    }
                });
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const heart = document.getElementById('data-save-checkbox');
    if (heart) {
        heart.addEventListener('click', function () {
            const snippet = heart.getAttribute('data-snippet');

            fetch('handlers/save.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `snippet=${encodeURIComponent(snippet)}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('data-saved-value').innerText = data.value;
                    } else {
                        alert(data.error);
                    }
                });
        });
    }
});

function openReport(event) {
    closeConfirmDelete();
    closeInfo();
    closeConfirmDeleteComment();
    event.stopPropagation();
    document.getElementById('report-center-div').style.display = 'block';
    document.getElementById('rest').style.filter = 'brightness(30%)';
}

function closeReport() {
    document.getElementById('report-center-div').style.display = 'none';
    document.getElementById('rest').style.filter = 'brightness(100%)';
}

function reportFormSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    fetch('submit-report.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log("Report submitted successfully");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            closeReport();
        });
}

function openConfirmDelete(event) {
    closeReport();
    closeInfo();
    closeConfirmDeleteComment();
    event.stopPropagation();
    document.getElementById('confirm-delete-center-div').style.display = 'block';
    document.getElementById('rest').style.filter = 'brightness(30%)';
}

function closeConfirmDelete() {
    document.getElementById('confirm-delete-center-div').style.display = 'none';
    document.getElementById('rest').style.filter = 'brightness(100%)';
}

function openInfo(event, info) {
    closeReport();
    closeConfirmDelete();
    closeConfirmDeleteComment();
    if (event != null) {
        event.stopPropagation();
    }
    document.getElementById('info-center-div').style.display = 'block';
    document.getElementById('rest').style.filter = 'brightness(30%)';
    document.getElementById('info-text').innerText = info;
}

function closeInfo() {
    document.getElementById('info-center-div').style.display = 'none';
    document.getElementById('rest').style.filter = 'brightness(100%)';
}

function openConfirmDeleteComment(event, commentId) {
    closeReport();
    closeConfirmDelete();
    closeInfo();
    if (event != null) {
        event.stopPropagation();
    }
    document.getElementById('confirm-delete-comment-center-div').style.display = 'block';
    document.getElementById('rest').style.filter = 'brightness(30%)';
    document.getElementById('confirm-delete-comment').addEventListener('click', function () {
        deleteComment(commentId);
    });
}

function closeConfirmDeleteComment() {
    document.getElementById('confirm-delete-comment-center-div').style.display = 'none';
    document.getElementById('rest').style.filter = 'brightness(100%)';
}

document.addEventListener('DOMContentLoaded', function () {
    const hearts = document.getElementsByClassName('comment-like-checkbox')
    Array.from(hearts).forEach(heart => {
        if (heart) {
            heart.addEventListener('click', function () {
                const snippet = heart.getAttribute('data-snippet');

                fetch('handlers/like-comment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `comment=${encodeURIComponent(snippet)}`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('comment-likes-text-' + data.id).innerText = data.value;
                        } else {
                            alert(data.error);
                        }
                    });
            });
        }
    });
});

function replyToComment(commentId, commentUser) {
    document.getElementById("child-of-input").value = commentId;
    document.getElementById("replying-to").style.display = "flex";
    document.getElementById("replying-to-text").innerText = commentUser;
    location.href = "#comments";
    document.getElementById("comment-area").focus();
}

function replyRemove() {
    document.getElementById("child-of-input").value = -1;
    document.getElementById("replying-to").style.display = "none";
}

function postComment(event) {
    event.preventDefault();
    const formData = new FormData(event.target);

    fetch('handlers/upload-comment.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.href = "snippet.php?name=" + event.target.snippet.value + "&info=Comment sent#comments";
            } else {
                openInfo(event, data.error);
            }
        })
        .catch(error => {
            openInfo(event, error + ' [this is a programming error, please send this error to the staff providing enough context]');
        })
}

function deleteComment(commentId) {
    fetch('handlers/delete-comment.php?id=' + commentId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const url = new URL(window.location.href);
                url.searchParams.set('info', 'Comment deleted');
                url.hash = 'comments';
                window.location.href = url.href;
            } else {
                openInfo(null, data.error);
            }
        })
        .catch(error => {
            openInfo(null, error + ' [this is a programming error, please send this error to the staff providing enough context]');
        })
}

function highlightReply(commentId) {
    let comment = document.getElementById("comment-" + commentId);
    comment.style.animation = 'none';
    comment.offsetHeight;
    comment.style.animation = "highlight 2s ease"
}