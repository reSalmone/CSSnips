document.addEventListener('DOMContentLoaded', () => {

  // Toggle del menù a tendina
  const menuIcon = document.getElementById('menu-icon');
  const navMenu = document.getElementById('nav');

  if (menuIcon && navMenu) {
    menuIcon.addEventListener('click', () => {
      navMenu.classList.toggle('active');
    });
  }

  // parte per le finestre di followers e following
  let currentlyOpen = null;

  window.toggleList = function(type) {
  const thisList = document.getElementById(`${type}-list`);
  if (!thisList) return;

  // Toglie o aggiunge la classe 'active' indipendentemente dallo stato dell'altra lista
  if (thisList.classList.contains('active')) {
    thisList.classList.remove('active');
  } else {
    thisList.classList.add('active');
  }
};


  // Funzione follow/unfollow
  window.toggleFollow = function (username, isFollowing) {
    if (isFollowing) {
      const confirmUnfollow = confirm("Do you want to unfollow this user?");
      if (!confirmUnfollow) return;
    }

    fetch('account2_follow_action.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: `username=${encodeURIComponent(username)}&action=${isFollowing ? 'unfollow' : 'follow'}`
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const btn = document.getElementById('follow-btn');
          btn.textContent = data.newLabel;
          btn.className = data.newClass;
          btn.setAttribute('onclick', `toggleFollow('${username}', ${data.newClass === 'following'})`);
        } else {
          alert("Error: " + data.message);
        }
      })
      .catch(err => {
        alert("An unexpected error occurred.");
        console.error(err);
      });
  };

});