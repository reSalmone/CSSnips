document.addEventListener('DOMContentLoaded', function () {

    const heart = document.getElementsByClassName('snip-like-checkbox');
    Array.from(heart).forEach(element => {
        element.addEventListener('click', function () {
            const snippet = element.getAttribute('data-snippet');

            fetch('handlers/like_snip.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `snippet=${encodeURIComponent(snippet)}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('data-liked-value-'+snippet).innerText = data.value;
                    } else {
                        alert(data.error);
                    }
                });
        });


    });
});