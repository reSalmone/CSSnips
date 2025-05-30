<?php
session_start();

if (!isset($_SESSION["username"]) && !isset($_GET["username"])) {
  header("Location: index.php");
}

if (isset($_GET["username"])) {
  $redirect = 'account.php?username=' . htmlspecialchars($_GET["username"]);
} else {
  $redirect = 'account.php';
}

$dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or -1;

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
  <link rel="icon" href="assets/images/icon.png">
  <link rel="stylesheet" href="css/account.css">
  <link rel="stylesheet" href="assets/NoveoSans-Book/style.css">
  <link rel="stylesheet" href="css/navbar.css">
  <link rel="stylesheet" href="css/login-signup.css">
  <link rel="stylesheet" href="css/checkbox.css">
  <link rel="stylesheet" href="css/footer.css">
  <script src="scripts/account.js"></script>
  <script src="scripts/login.js"></script>
  <script src="scripts/signup.js"></script>
  <script src="scripts/explorer.js"></script>
</head>

<body>
  <?php include 'navbar-code.php'; ?>
  <?php include 'login-signup-code.php'; ?>
  <div id="rest" onclick="closeLogin(); closeSignup();">

    <?php
    if ($found && $esiste) {
      $avatar_url = "https://robohash.org/" . urlencode($real_username) . ".png?set=set1&bgset=bg1";
      ?>
      <main class="profilo-container">

        <var class="main_var">
          <section class="profilo-info">
            <div class="profile-card">
              <div class="profile-header">
                <div class="avatar-wrapper">
                  <img src="<?= $avatar_url ?>" alt="Avatar" class="avatar-img">
                  <?php if ($username != $target_username && $target_username != '' && $username != '') {
                    $is_following = false;
                    $line2 = pg_fetch_array($result2, NULL, PGSQL_ASSOC);
                    $lista = $line2["following"];
                    $lista = trim($lista, '{}');
                    $elements = explode(',', $lista);

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
                  <?php } ?>
                </div>

                <div class="profile-main">
                  <h2 class="profile-username"><?= htmlspecialchars($real_username) ?></h2>
                  <div class="profile-meta">
                    <?php if (!empty($email)) { ?>
                      <div class="meta-item">
                        <svg class="meta-icon" viewBox="0 0 24 24" width="16" height="16">
                          <path fill="currentColor"
                            d="M4,4H20A2,2 0 0,1 22,6V18A2,2 0 0,1 20,20H4C2.89,20 2,19.1 2,18V6C2,4.89 2.89,4 4,4M12,11L20,6H4L12,11M4,18H20V8.37L12,13.36L4,8.37V18Z" />
                        </svg>
                        <span><?= htmlspecialchars($email) ?></span>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>

              <?php if (!empty($bio)){ ?>
                <div class="profile-bio">
                  <h3 class="bio-title">About</h3>
                  <p class="bio-content"><?= htmlspecialchars($bio) ?></p>
                </div>
              <?php } ?>

              <div class="profile-stats">
                <div class="stat-item"
                  onclick="location.href='activity.php?username=<?= urlencode($target_username ?: $username) ?>&type=activity'">
                  <span class="stat-number"><?= htmlspecialchars($numero_codici) ?></span>
                  <span class="stat-label">Snippets</span>
                </div>
                <div class="stat-item">
                  <span class="stat-number"><?= htmlspecialchars($numero_followers ?? 0) ?></span>
                  <span class="stat-label">Followers</span>
                </div>
                <div class="stat-item">
                  <span class="stat-number"><?= htmlspecialchars($numero_following ?? 0) ?></span>
                  <span class="stat-label">Following</span>
                </div>
              </div>
            </div>
          </section>

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
                      <div class="info-creator" onclick="location.href = 'account.php?username=<?= $real_username ?>'">
                        <div class="info-pfp">
                          <img src="<?= $avatar_url ?>" alt="Avatar" class="avatar-img">
                        </div>
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
                echo "<p class='centro'>No activity.</p>";
              }
              ?>
            </div>
          </section>

          <?php $a = $target_username == '' ? $username : $target_username; ?>
          <section class="funzioni-container">
            <div class="funzione-box"
              onclick="location.href = 'activity.php?username=<?php echo urlencode($a); ?>&type=activity'">
              <div class="funzione-text">Activity</div>
              <div class="funzione-count"><?php echo htmlspecialchars($numero_codici) ?></div>
            </div>
            <div class="funzione-box"
              onclick="location.href = 'activity.php?username=<?php echo urlencode($a); ?>&type=liked'">
              <div class="funzione-text">Likes</div>
              <div class="funzione-count"><?php echo htmlspecialchars($numero_liked ?? 0) ?></div>
            </div>
            <div class="funzione-box"
              onclick="location.href = 'activity.php?username=<?php echo urlencode($a); ?>&type=watchlist'">
              <div class="funzione-text">Saved</div>
              <div class="funzione-count"><?php echo htmlspecialchars($numero_saved ?? 0) ?></div>
            </div>

            <div class="funzione-box" onclick="toggleList('followers')">
              <div class="funzione-text">Followers</div>
              <div class="funzione-count"><?php echo htmlspecialchars($numero_followers ?? 0) ?></div>
            </div>
            <div class="list-container" id="followers-list">
              <?php
              if ($line8 = pg_fetch_array($result8, NULL, PGSQL_ASSOC)) {
                $lista = $line8["followers"];
                $lista = trim($lista, '{}');
                $elements = explode(',', $lista);

                if (empty($lista) || $lista === '') {
                  echo "<h3>no followers.</h3>";
                } else {
                  foreach ($elements as $item) {
                    $clean_item = htmlspecialchars(trim($item));
                    $avatar_url = "https://robohash.org/" . urlencode($clean_item) . ".png?set=set1&bgset=bg1";
                    echo "<div class='list-user' onclick=\"location.href = 'account.php?username=$clean_item'\"> 
                      <span class='user-name'>$clean_item</span>
                      <img src='$avatar_url' alt='Avatar' class='avatar-img'>
                    </div>";
                  }
                }
              } else {
                echo "<h3>no followers.</h3>";
              }
              ?>
            </div>

            <div class="funzione-box" onclick="toggleList('following')">
              <div class="funzione-text">Following</div>
              <div class="funzione-count"><?php echo htmlspecialchars($numero_following ?? 0) ?></div>
            </div>
            <div class="list-container" id="following-list">
              <?php
              if ($line10 = pg_fetch_array($result10, NULL, PGSQL_ASSOC)) {
                $lista = $line10["following"];
                $lista = trim($lista, '{}');
                $elements = explode(',', $lista);

                if (empty($lista) || $lista === '') {
                  echo "<h3>no following.</h3>";
                } else {
                  foreach ($elements as $item) {
                    $clean_item = htmlspecialchars(trim($item));
                    $avatar_url = "https://robohash.org/" . urlencode($clean_item) . ".png?set=set1&bgset=bg1";
                    echo "<div class='list-user' onclick=\"location.href = 'account.php?username=$clean_item'\"> 
                      <span class='user-name'>$clean_item</span>
                      <img src='$avatar_url' alt='Avatar' class='avatar-img'>
                    </div>";
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
    include 'footer-code.php'; ?>
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
<script src="scripts/login.js"></script>
<script src="scripts/signup.js"></script>

</html>