<?php
//qua in pratica con session_start() pija le info dell'ultima sessione da un file che si è salvato
session_start();
$redirect = 'explorer.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSSnips - Explorer</title>
    <link rel="stylesheet" href="explorer.css">
    <link rel="stylesheet" href="assets/NoveoSans-Book/style.css">
    <link rel="stylesheet" href="login-signup.css">
    <link rel="stylesheet" href="checkbox.css"> <!-- Checkbox figa nel login -->
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="snippet-loader.css">
</head>

<body>
    <?php include 'navbar-code.php'; ?> <!--NAVBAR-->
    <?php include 'login-signup-code.php'; ?> <!--LOGIN AND SIGNUP-->
    <div id="rest" onclick="closeLogin(); closeSignup();">
        <div class="title-container">
            <span class="title">Explorer</span>
            <span class="subtitle">Search for the perfect snippet to include in your project</span>
        </div>
        <form action="explorer.php" method="GET" class="search-form" id="search-form">
            <div class="search-bar">
                <input placeholder="Search for elements / tags / usernames" type="search" class="search-input"
                    name="search" spellcheck="false" <?php
                    if (isset($_GET['search'])) {
                        echo 'value="' . $_GET['search'] . '"';
                    }
                    ?>>
                <img src="assets/images/search.png" class="search-icon"
                    onclick="document.getElementById('search-form').submit();">
            </div>
        </form>
        <div class="search-output-div">
            <?php
            $search = $_GET['search'] ?? '';
            $dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or -1;
            if ($dbcon != -1) { //se la connessione è correttamente stabilita
                $q1 = "SELECT * FROM snips ORDER BY likes DESC, views DESC";
                $result = null;
                if ($search != '') {
                    $q1 = "SELECT *,
                        CASE
                            WHEN EXISTS (
                                SELECT 1 FROM unnest(tags) AS tag
                                WHERE tag ILIKE $1
                            ) THEN 4
                            WHEN challenge_type ILIKE $1 THEN 3
                            WHEN element_type ILIKE $1 THEN 2
                            WHEN creator ILIKE $1 THEN 1
                            ELSE 0
                        END AS relevance
                        FROM snips
                        WHERE EXISTS (
                            SELECT 1 FROM unnest(tags) AS tag
                            WHERE tag ILIKE $1
                        )
                        OR challenge_type ILIKE $1
                        OR element_type ILIKE $1
                        OR creator ILIKE $1
                        ORDER BY relevance DESC, likes DESC, views DESC;";
                    $result = pg_query_params($dbcon, $q1, array($search));
                } else {
                    $result = pg_query($dbcon, $q1);
                }
                echo '<p class="search-results">' . pg_num_rows($result) . ' results</p>';
                if (pg_num_rows($result) > 0) {
                    echo '<div class="search-output">';
                    while ($tuple = pg_fetch_assoc($result)) {
                        $id = (int) $tuple['id'];
                        ?>
                        <div class="output-snip" data-snippet-id="<?= $id ?>">
                            <div class="output-snip-opener"
                                onclick="location.href='snippet.php?name=<?= urlencode($tuple['file_location']) ?>';">
                                <span>View code</span>
                            </div>
                            <div class="output-loader" id="output-loader-<?= $id ?>"></div>
                            <iframe id="output-snip-frame-<?= $id ?>" class="output-preview">
                            </iframe>
                            <div class="info">
                                <div class="info-creator">
                                    <div class="info-pfp"></div>
                                    <span><?= htmlspecialchars($tuple['creator']) ?></span>
                                </div>
                                <div class="info-views">
                                    <p class="info-text"><?= htmlspecialchars($tuple['views']) ?>
                                        <span class="info-subtext"> views</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    echo '</div>';
                }
            } else {
                echo '<p>Error connecting to databse</p>';
            }
            ?>
        </div>
        <?php include 'footer-code.php'; ?> <!--FOOTER-->
    </div>
</body>
<script src="assets/scripts/login.js"></script>
<script src="assets/scripts/signup.js"></script>
<script src="assets/scripts/explorer.js"></script>

</html>