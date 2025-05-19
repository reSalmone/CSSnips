<?php
//qua in pratica con session_start() pija le info dell'ultima sessione da un file che si è salvato
session_start();
$redirect = 'explorer.php';

$search = $_GET['search'] ?? '';

$pageSize = 9;
$page = 1;
if (isset($_GET['page'])) {
    $page = intval($_GET['page']);
}
$pageOffset = ($page - 1) * $pageSize;
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
            <?php if ($search == '') { ?>
                <span class="subtitle">Search for the perfect snippet to include in your project</span>
            <?php } else { ?>
                <span class="subtitle">Searching for: <?= $search ?></span>
            <?php } ?>
        </div>
        <form action="explorer.php" method="GET" class="search-form" id="search-form">
            <div class="search-bar">
                <input placeholder="Search for elements / tags / usernames" type="search" class="search-input"
                    name="search" spellcheck="false" <?php
                    if ($search != '') {
                        echo 'value="' . $search . '"';
                    }
                    ?>>
                <img src="assets/images/search.png" class="search-icon"
                    onclick="document.getElementById('search-form').submit();">
            </div>
        </form>
        <div class="search-output-div">
            <?php
            $dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or -1;
            if ($dbcon != -1) { //se la connessione è correttamente stabilita
                $resultAll = null;
                $resultPage = null;
                if ($search != '') {
                    $q1Base = "WITH filtered_snips AS (
                            SELECT *,
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
                        )";
                    $q1 = $q1Base . " SELECT * FROM filtered_snips ORDER BY relevance DESC, likes DESC, views DESC LIMIT $2 OFFSET $3;"; //se viene effettuata una ricerca
                    $resultPage = pg_query_params($dbcon, $q1, array($search, $pageSize, $pageOffset));
                    $q1All = $q1Base . " SELECT COUNT(*) FROM filtered_snips;";
                    $resultAll = pg_query_params($dbcon, $q1All, array($search));
                } else {
                    $q1 = "SELECT * FROM snips ORDER BY likes DESC, views DESC LIMIT $1 OFFSET $2;"; //se non viene effettuata una ricerca
                    $resultPage = pg_query_params($dbcon, $q1, array($pageSize, $pageOffset));
                    $q1All = "SELECT COUNT(*) FROM snips;";
                    $resultAll = pg_query($dbcon, $q1All);
                }
                $totalResults = pg_fetch_row($resultAll)[0];
                $totalPages = ceil($totalResults / $pageSize);
                ?>
                <div class="search-results">
                    <div class="search-results-left">
                        <p class="search-results-subtext">Page </p>
                        <p class="search-results-text"><?= $page ?></p>
                        <p class="search-results-subtext"> of </p>
                        <p class="search-results-text"><?= $totalPages ?></p>
                    </div>
                    <div class="search-results-right">
                        <p class="search-results-subtext">Showing </p>
                        <p class="search-results-text"><?= pg_num_rows($resultPage) ?></p>
                        <p class="search-results-subtext"> out of </p>
                        <p class="search-results-text"><?= ($totalResults) ?></p>
                        <p class="search-results-subtext"> results</p>
                    </div>
                </div>
                <?php if ($totalResults > 0) {
                    if (pg_num_rows($resultPage) > 0) {
                        echo '<div class="search-output">';
                        while ($tuple = pg_fetch_assoc($resultPage)) {
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
                    if ($totalPages > 1 || $page > $totalPages) {
                        echo '<div class="page-buttons">';
                        if ($page > 1) {
                            echo '<div class="page-buttons-left">';
                            echo '<button class="page-button" onclick="updateUrlAndDirect(\'page\', ' . ($page - 1) . ');">Previous page</button>';
                            echo '</div>';
                        }
                        if ($page < $totalPages) {
                            echo '<div class="page-buttons-right">';
                            echo '<button class="page-button" onclick="updateUrlAndDirect(\'page\', ' . ($page + 1) . ');">Next page</button>';
                            echo '</div>';
                        }
                        echo '</div>';
                    }
                } else {
                    echo '<p class="no-snippets-text">No snippets found</p>';
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