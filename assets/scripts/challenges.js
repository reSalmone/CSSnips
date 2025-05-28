const Carosello=document.getElementById('s-carosello');
const SlideLeft=document.getElementById('s-left');
const SlideRight=document.getElementById('s-right');

SlideLeft.addEventListener('click',()=>{Carosello.scrollLeft -=500;});
SlideRight.addEventListener('click',()=>{Carosello.scrollLeft +=500;});SlideRight.addEventListener('click',()=>{Carosello.scrollLeft +=500;});
SlideRight.addEventListener('click',()=>{Carosello.scrollLeft +=500;});



const Leaderboard_container = document.getElementById('id-leaderboard-container');
const Carosello_leaderboard = document.getElementById('id-user-output');
let direction = 1;
let scrolling = true;
const scrollSpeed = 0.3;


Leaderboard_container.addEventListener('mouseenter', () => {
    scrolling = false;
});
Leaderboard_container.addEventListener('mouseleave', () => {
    scrolling = true;
    autoScroll();
});

function autoScroll() {
    if(!scrolling) return;
    if (direction==1) {Carosello_leaderboard.scrollTop += scrollSpeed;}
    else{ Carosello_leaderboard.scrollTop -= scrollSpeed;}

    if (Carosello_leaderboard.scrollTop + Carosello_leaderboard.clientHeight >= Carosello_leaderboard.scrollHeight) {
        
        direction = -1; 
    } else if (Carosello_leaderboard.scrollTop <= 0) {
        direction = 1; 
    }

    requestAnimationFrame(autoScroll);
}

autoScroll();

