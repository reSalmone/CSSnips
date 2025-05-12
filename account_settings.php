<?php
session_start();

if (!isset($_SESSION["username"])) {
  header("Location: index.php");
}

$redirect = 'account.php';
  ?>


<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account settings</title>
  <link rel="stylesheet" href="account_settings.css">
  <link rel="stylesheet" href="assets/NoveoSans-Book/style.css">
  <link rel="stylesheet" href="navbar.css">
  <link rel="stylesheet" href="login-signup.css">
  <link rel="stylesheet" href="checkbox.css">
  <script src="account_settings.js"></script>
</head>

<body>

  <!-- Header con il nome del sito e il menu a tendina -->
  <?php include 'navbar-code.php'; ?> <!--NAVBAR-->
  <?php include 'login-signup-code.php'; ?> <!--LOGIN AND SIGNUP-->
  <div id="rest" onclick="closeLogin(); closeSignup();">

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