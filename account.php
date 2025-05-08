<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profilo Utente</title>
  <link rel="stylesheet" href="account.css">
  <link rel="stylesheet" href="assets/NoveoSans-Book/style.css">
  <link rel="stylesheet" href="navbar.css">
  <script src="account.js"></script> 
</head>
<body>

  <!-- Header con il nome del sito e il menu a tendina -->
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
          <button class="dropbtn">Account ▾</button>
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

  <main class="profilo-container"> 
    
    <var class="main_var">
      <!-- Sezione delle informazioni dell'utente -->
      <section class="profilo-info">
        <img src="fotoprofilo.jpg" alt="Foto Profilo" class="profilo-img">
        <div class="info-text">
          <h2>Nome Utente</h2>
          <p>Email: utente@email.com</p>
          <p>Data iscrizione: 01/01/2024</p>
          <p>Bio: Appassionato di programmazione web.</p>
        </div>
      </section>

      <!-- Recent Activity (quattro codici recenti in orizzontale) -->
      <section class="recent-activity">
        <h2>RECENT ACTIVITY</h2>
        <div class="activity-container">
        </div>
      </section>

      <!-- Funzionalità sotto, ciascuna occupa una riga intera -->
      <section class="funzioni-container">
        <div class="funzione-box">
          <div class="funzione-text">Activity</div>
          <div class="funzione-count">25</div>
        </div>
        <div class="funzione-box">
          <div class="funzione-text">Watchlist</div>
          <div class="funzione-count">10</div>
        </div>
        <div class="funzione-box">
          <div class="funzione-text">Followers</div>
          <div class="funzione-count">100</div>
        </div>
        <div class="funzione-box">
          <div class="funzione-text">Following</div>
          <div class="funzione-count">50</div>
        </div>
      </section>
    </var>

  </main>

</body>
</html>
