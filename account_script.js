document.addEventListener('DOMContentLoaded', () => {
    
  });
  
  // Toggle del menù a tendina
  const menuIcon = document.getElementById('menu-icon');
  const navMenu = document.getElementById('nav');
  
  menuIcon.addEventListener('click', () => {
    navMenu.classList.toggle('active');
  });
  