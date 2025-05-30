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
    const els = document.querySelectorAll('.output-snip');
    const ids = Array.from(els, el => el.dataset.snippetId).map(Number);

    if (!ids.length) return;

    fetch(`handlers/load_snippets.php?ids=${ids.join(',')}`)
        .then(r => r.json())
        .then(snippets => {
            snippets.forEach(s => {
                const iframeId = 'output-snip-frame-' + s.id;
                const loaderID = 'output-loader-' + s.id;
                assignIFrame(iframeId, s.html, s.css, s.js);
                document.getElementById(loaderID).style.display = "none";
            });
        })
        .catch(err => console.error('Batch load error:', err));
});

function updateUrlAndDirect(key, value, locationId) {
    const url = new URL(window.location.href);
    url.searchParams.set(key, value);
    window.location.href = url.toString() + locationId;
}

document.addEventListener('DOMContentLoaded', function () {
    const hearts = document.getElementsByClassName('info-like-checkbox')
    Array.from(hearts).forEach(heart => {
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
                        document.getElementById('info-likes-' + data.id).innerText = data.value;
                    } else {
                        alert(data.error);
                    }
                });
        });
    }
    });
});

function openInfo(event, info) {
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