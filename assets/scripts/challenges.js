const Carosello=document.getElementById('s-carosello');
const SlideLeft=document.getElementById('s-left');
const SlideRight=document.getElementById('s-right');

SlideLeft.addEventListener('click',()=>{Carosello.scrollLeft -=500;});
SlideRight.addEventListener('click',()=>{Carosello.scrollLeft +=500;});

document.addEventListener('DOMContentLoaded', function () {
    const heart = document.getElementById('snip-like-checkbox')
    if (heart) {
        heart.addEventListener('click', function () {
            const snippet = heart.getAttribute('data-snippet');

            fetch('like_snip.php', {
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