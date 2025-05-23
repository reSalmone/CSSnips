<?php
session_start();

if (!isset($_SESSION["username"])) {
  header("Location: index.php");
}
$redirect = 'account.php';

$dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1");

$username = $_SESSION['username'] ?? '';
$target_username = $_GET['username'] ?? '';
$esiste = true;

$real_username = null;
$email = null;
$bio = null;
$numero_codici = null;
$numero_liked = null;
$numero_saved = null;
$numero_followers = null;
$numero_following = null;
$result2 = null;
$result3 = null;
$result8 = null;
$result10 = null;
$found = false;


if ($target_username == "") {
  $utente = $username;
} else {
  $utente = $target_username;
}

if ($dbcon != -1) {
  $query1 = "SELECT * FROM users WHERE username ILIKE '$utente';";
  $query2 = "SELECT * FROM users WHERE username ILIKE '$username'";
  $query3 = "with this as (SELECT * FROM snips WHERE creator ILIKE '$utente' order by created_at desc) SELECT * FROM this limit 3";
  $query4 = "SELECT count(*) AS numero_codici FROM snips WHERE creator ILIKE '$utente'";
  $query5 = "SELECT cardinality(likedsnippets) AS numero_stringhe FROM users WHERE username ILIKE '$utente'";
  $query6 = "SELECT cardinality(savedsnippets) AS numero_stringhe FROM users WHERE username ILIKE '$utente'";
  $query7 = "SELECT cardinality(followers) AS numero_followers FROM users WHERE username ILIKE '$utente'";
  $query8 = "SELECT followers FROM users WHERE username ILIKE '$utente'";
  $query9 = "SELECT cardinality(following) AS numero_following FROM users WHERE username ILIKE '$utente'";
  $query10 = "SELECT following FROM users WHERE username ILIKE '$utente'";
  $result1 = pg_query($query1);
  $result2 = pg_query($query2);
  $result3 = pg_query($query3);
  $result4 = pg_query($query4);
  $result5 = pg_query($query5);
  $result6 = pg_query($query6);
  $result7 = pg_query($query7);
  $result8 = pg_query($query8);
  $result9 = pg_query($query9);
  $result10 = pg_query($query10);

  if ($result1 && $result2 && $result3 && $result4 && $result5 && $result6 && $result7 && $result8 && $result9 && $result10) {
    $found = true;
    $line1 = pg_fetch_array($result1, NULL, PGSQL_ASSOC);
    $line4 = pg_fetch_array($result4, NULL, PGSQL_ASSOC);
    $line5 = pg_fetch_array($result5, NULL, PGSQL_ASSOC);
    $line6 = pg_fetch_array($result6, NULL, PGSQL_ASSOC);
    $line7 = pg_fetch_array($result7, NULL, PGSQL_ASSOC);
    $line9 = pg_fetch_array($result9, NULL, PGSQL_ASSOC);
    if ($line1 && $line4 && $line5 && $line6 && $line7 && $line9) {
      $real_username = $line1['username'];
      $email = $line1['email'];
      $bio = $line1['bio'];
      $numero_codici = $line4['numero_codici'];
      $numero_liked = $line5['numero_stringhe'];
      $numero_saved = $line6['numero_stringhe'];
      $numero_followers = $line7['numero_followers'];
      $numero_following = $line9['numero_following'];
    }

    if (!$line1 && $utente == $target_username)
      $esiste = false;
  }
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profilo Utente</title>
  <link rel="stylesheet" href="account.css">
  <link rel="stylesheet" href="assets/NoveoSans-Book/style.css">
  <link rel="stylesheet" href="navbar.css">
  <link rel="stylesheet" href="login-signup.css">
  <link rel="stylesheet" href="checkbox.css">
  <link rel="stylesheet" href="footer.css">
  <script src="account.js"></script>
  <script src="assets/scripts/login.js"></script>
  <script src="assets/scripts/signup.js"></script>
  <script src="assets/scripts/explorer.js"></script>
</head>

<body>
  <!-- Header con il nome del sito e il menu a tendina -->
  <?php include 'navbar-code.php'; ?> <!--NAVBAR-->
  <?php include 'login-signup-code.php'; ?> <!--LOGIN AND SIGNUP-->
  <div id="rest" onclick="closeLogin(); closeSignup();">

    <?php
    if ($found && $esiste) {
      ?>
      <main class="profilo-container">

        <var class="main_var">
          <!-- Sezione delle informazioni dell'utente -->
          <section class="profilo-info">
            <div class="info-text">
              <!-- DEVE ESSERE CAMBIATO USERNAME STAMPATO-->
              <?php echo "<h2>" . htmlspecialchars($real_username) . "</h2>" ?>
              <?php echo "<p>Email:" . htmlspecialchars($email) . "</p>" ?>
              <?php echo "<p>Bio:" . htmlspecialchars($bio) . "</p>" ?>
              <!-- DA RIVEDERE -->
              <?php
              if ($username != $target_username && $target_username != '') {
                /*qua bisogna mettere il pulsante per seguire gli altri utenti*/
                $is_following = false;
                $line2 = pg_fetch_array($result2, NULL, PGSQL_ASSOC);
                $lista = $line2["following"];// PostgreSQL restituisce gli array come stringa tipo: {elem1,elem2,...}
                $lista = trim($lista, '{}'); // Rimuovi le parentesi graffe
                $elements = explode(',', $lista); // Split sugli elementi
            
                foreach ($elements as $item) {
                  if (strtolower(trim($item)) == strtolower($target_username)) {
                    $is_following = true;
                  }
                }
                ?>
                <button id="follow-btn" class="<?php echo $is_following ? 'following' : 'follow' ?>"
                  onclick="toggleFollow('<?php echo htmlspecialchars($target_username) ?>', <?php echo $is_following ? 'true' : 'false' ?>)">
                  <?php echo $is_following ? 'Following' : 'Follow' ?>
                </button>
                <?php
              }
              ?>
            </div>
          </section>

          <!-- Recent Activity (tre codici recenti in orizzontale) -->
          <section class="recent-activity">
            <h2>RECENT ACTIVITY</h2>
            <?php
            $line3 = pg_fetch_array($result3, NULL, PGSQL_ASSOC);
            $class = $line3 ? 'activity-container' : 'activity-container-nosnippets';
            ?>
            <div class=<?php echo $class ?>>
              <?php
              if ($line3) {
                while ($line3) {
                  $id = (int) $line3['id'];
                  ?>
                  <div class="output-snip" data-snippet-id="<?= $id ?>">
                    <div class="output-snip-opener"
                      onclick="location.href='snippet.php?name=<?= urlencode($line3['file_location']) ?>';">
                      <span>View code</span>
                    </div>
                    <div class="output-loader" id="output-loader-<?= $id ?>"></div>
                    <iframe id="output-snip-frame-<?= $id ?>" class="output-preview">
                    </iframe>
                    <div class="info">
                      <div class="info-creator">
                        <div class="info-pfp"></div>
                        <span><?= htmlspecialchars($line3['creator']) ?></span>
                      </div>
                      <div class="info-views">
                        <p class="info-text"><?= htmlspecialchars($line3['views']) ?>
                          <span class="info-subtext"> views</span>
                        </p>
                      </div>
                    </div>
                  </div>
                  <?php
                  $line3 = pg_fetch_array($result3, NULL, PGSQL_ASSOC);
                }
              } else {
                echo "<p class='centro'>Non ci sono risultati.</p>";
              }
              ?>
            </div>
          </section>

          <!-- Funzionalità sotto, ciascuna occupa una riga intera -->
          <section class="funzioni-container">
            <div class="funzione-box">
              <div class="funzione-text">Activity</div>
              <div class="funzione-count"><?php echo htmlspecialchars($numero_codici) ?></div>
            </div>
            <div class="funzione-box">
              <div class="funzione-text">Likes</div>
              <div class="funzione-count"><?php echo htmlspecialchars($numero_liked ?? 0) ?></div>
            </div>
            <div class="funzione-box">
              <div class="funzione-text">Watchlist</div>
              <div class="funzione-count"><?php echo htmlspecialchars($numero_saved ?? 0) ?></div>
            </div>

            <!-- FOLLOWERS -->
            <div class="funzione-box" onclick="toggleList('followers')">
              <div class="funzione-text">Followers</div>
              <div class="funzione-count"><?php echo htmlspecialchars($numero_followers ?? 0) ?></div>
            </div>
            <div class="list-container" id="followers-list">
              <?php
              if ($line8 = pg_fetch_array($result8, NULL, PGSQL_ASSOC)) {
                $lista = $line8["followers"];// PostgreSQL restituisce gli array come stringa tipo: {elem1,elem2,...}
                $lista = trim($lista, '{}'); // Rimuovi le parentesi graffe
                $elements = explode(',', $lista); // Split sugli elementi
            
                if (empty($lista) || $lista === '') {
                  echo "<h3>no followers.</h3>";
                } else {
                  foreach ($elements as $item) {
                    $clean_item = htmlspecialchars(trim($item));
                    echo "<div class='list-user' onclick=\"location.href = 'account.php?username=$clean_item'\">" . $clean_item . "</div>";
                  }
                }
              } else {
                echo "<h3>no followers.</h3>";
              }
              ?>
            </div>

            <!-- FOLLOWING -->
            <div class="funzione-box" onclick="toggleList('following')">
              <div class="funzione-text">Following</div>
              <div class="funzione-count"><?php echo htmlspecialchars($numero_following ?? 0) ?></div>
            </div>
            <div class="list-container" id="following-list">
              <?php
              if ($line10 = pg_fetch_array($result10, NULL, PGSQL_ASSOC)) {
                $lista = $line10["following"];// PostgreSQL restituisce gli array come stringa tipo: {elem1,elem2,...}
                $lista = trim($lista, '{}'); // Rimuovi le parentesi graffe
                $elements = explode(',', $lista); // Split sugli elementi
            
                if (empty($lista) || $lista === '') {
                  echo "<h3>no following.</h3>";
                } else {
                  foreach ($elements as $item) {
                    $clean_item = htmlspecialchars(trim($item));
                    echo "<div class='list-user' onclick=\"location.href = 'account.php?username=$clean_item'\">" . $clean_item . "</div>";
                  }
                }
              } else {
                echo "<h3>no following.</h3>";
              }
              ?>
            </div>
          </section>
        </var>
      </main> <?php
    } else { ?>
      <main class="profilo-container">
        <var class="main_var">
          <section class="profilo-info">
            <?php
            $testo1 = "ERROR: UNABLE TO CONNECT TO THE DATABASE. PLEASE TRY AGAIN LATER.";
            $testo2 = "ERROR: USER PROFILE NOT FOUND.";
            ?>
            <h2><?php echo $esiste ? $testo1 : $testo2 ?></h2>
          </section>
        </var>
      </main>
      <?php
    }
    include 'footer-code.php'; ?> <!--FOOTER-->
  </div>
  <?php
  pg_free_result($result1);
  pg_free_result($result2);
  pg_free_result($result3);
  pg_free_result($result4);
  pg_free_result($result5);
  pg_free_result($result6);
  pg_free_result($result7);
  pg_free_result($result8);
  pg_free_result($result9);
  pg_free_result($result10);
  pg_close($dbcon);
  ?>
</body>
<script src="assets/scripts/login.js"></script>
<script src="assets/scripts/signup.js"></script>

</html>