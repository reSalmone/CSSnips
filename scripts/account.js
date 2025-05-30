document.addEventListener('DOMContentLoaded', () => {

  
  const menuIcon = document.getElementById('menu-icon');
  const navMenu = document.getElementById('nav');

  if (menuIcon && navMenu) {
    menuIcon.addEventListener('click', () => {
      navMenu.classList.toggle('active');
    });
  }

  let currentlyOpen = null;

  window.toggleList = function(type) {
  const thisList = document.getElementById(`${type}-list`);
  if (!thisList) return;

  if (thisList.classList.contains('active')) {
    thisList.classList.remove('active');
  } else {
    thisList.classList.add('active');
  }
};

  window.toggleFollow = function (username, isFollowing) {
    if (isFollowing) {
      const confirmUnfollow = confirm("Do you want to unfollow this user?");
      if (!confirmUnfollow) return;
    }

    fetch('handlers/account_follow_action.php', {
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