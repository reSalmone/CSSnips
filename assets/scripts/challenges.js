const Carosello=document.getElementById('s-carosello');
const SlideLeft=document.getElementById('s-left');
const SlideRight=document.getElementById('s-right');

SlideLeft.addEventListener('click',()=>{Carosello.scrollLeft -=500;});
SlideRight.addEventListener('click',()=>{Carosello.scrollLeft +=500;});