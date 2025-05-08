<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account settings</title>
  <link rel="stylesheet" href="account_settings.css">
  <link rel="stylesheet" href="assets/NoveoSans-Book/style.css">
  <link rel="stylesheet" href="navbar.css">
  <script src="account_settings.js"></script>
</head>

<body>

  <header class="navbar">
    <div class="left-navbar">
        <a href="index.php" class="navbar-title">CSSnips</a>
        <button class="nbutton" onclick="location.href='explorer.php'" type="button">
            <span>Explore</span>
            <img src="assets/images/search.png" class="nicon">
        </button>
        <button class="nbutton" onclick="location.href = 'challenges.html'" type="button">
            <span>Challenges</span>
            <img src="assets/images/target.png" class="nicon">
        </button>
        <button class="nbutton" onclick="location.href = 'creator.php'" type="button">
            <span>Create</span>
            <img src="assets/images/add.png" class="nicon">
        </button>
        <div class="dropdown">
          <button class="nbutton">
            <span>Account</span>  
            <img src="assets/images/omino.png" class="nicon">
          </button>
          <div class="dropdown-content">
            <a href="account.html">Account</a>
            <a href="activity.html">Activity</a>
            <a href="watchlist.html">Watchlist</a>
            <a href="account_settings.html">Account settings</a>
            <a href="logout.php">Logout</a>
          </div> 
        </div>
    </div>
  </header>

  <main class="settings-container">
    
    <var class="main_var">

      <section class="contact">
        <div class="section">
          <h1>EDIT CONTACT DETAILS</h1>
          <form id="email-form">
            <label for="email">E-mail address</label>
            <input type="email" id="email" value="giuseppeciccone2003@gmail.com">
            <span id="email-error" class="error-message"></span>
            <button class="btn" onclick="return validateEmail()">UPDATE EMAIL ADDRESS</button>
          </form>
        </div>
      </section>
      
      <section class="sectionn">
        <div class="section">
          <h1>EDIT PASSWORD</h1>
          <form id="password-form">
            <label for="new-password">New password</label>
            <input type="password" id="password" name="password" placeholder="********" />
            <span id="password-error" class="error-message"></span>
          
            <label for="confirm-password">Confirm password</label>
            <input type="password" id="confirm-password">
            <span id="confirm-error" class="error-message"></span>
          
            <button type="submit" class="btn" onclick="return validatePassword()">UPDATE PASSWORD</button>
          </form>
        </div>
      </section> 
      
      <section class="sectionn">
        <div class="section">
          <h1>EDIT ACCOUNT DETAILS</h1>
          <form id="account-form">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="GiuseppeCiccone" />
    
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" rows="3">Appassionato di programmazione web.</textarea>
    
            <label for="fotoProfilo">Avatar</label>
            <input type="file" id="fotoProfilo" name="fotoProfilo" accept=".jpg, .jpeg" />
    
            <button type="submit" class="btn">UPDATE ACCOUNT SETTINGS</button>
          </form>
        </div>
      </section>


    </var>
  </main>
</body>
</html>