<?php
session_start();

if (isset($_GET["username"]) && isset($_GET["type"])) {
    $redirect = 'activity.php?username=' . $_GET['username'] . '&type=' . $_GET['type'];
} else {
    $redirect = 'activity.php?username=' . ($_GET['username'] ?? '') . '&type=' . ($_GET['type'] ?? '');
}

$type = $_GET['type'] ?? '';
$username = $_GET['username'] ?? '';

$pageSize = 9;
$page = 1;
if (isset($_GET['page'])) {
    $page = intval($_GET['page']);
}
$pageOffset = ($page - 1) * $pageSize;

$dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or -1;
if ($username != "" && $dbcon != -1) {
    $query = "select * from users where username ILIKE '$username'";
    if ($result = pg_query($dbcon, $query)) {
        if ($row = pg_fetch_array($result)) {
            $username = $row["username"];
        }
    }
}
if ($type != "") {
    $type = strtolower($type);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSSnips - Explorer</title>
    <link rel="stylesheet" href="activity.css">
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
        <?php
        if ($username != '' && $type != '' && $dbcon != -1 && ($type == "activity" || $type == "liked" || $type == "watchlist")) {
            ?>
            <div class="title-container">
                <?php
                if ($type == 'watchlist') {
                    $t = "saved ";
                } else if ($type == "liked") {
                    $t = "liked ";
                } else {
                    $t = " ";
                }
                ?>
                <span class="title1"><?= $username . "'s " . $t . "snippets" ?></span>
            </div>
            <div class="search-output-div">
                <?php
                $resultAll = null;
                $resultPage = null;
                if ($type == 'activity') {
                    $q1 = "SELECT * FROM snips WHERE creator ILIKE '$username' ORDER BY likes DESC, views DESC, created_at DESC LIMIT $1 OFFSET $2;";
                    $q1All = "SELECT COUNT(*) FROM snips WHERE creator ILIKE '$username';";
                } else if ($type == 'watchlist') {
                    $q1 = "with abc as(SELECT unnest(savedsnippets) AS names FROM users WHERE username ILIKE '$username') SELECT * FROM snips x join abc y on x.file_location = y.names ORDER BY likes DESC, views DESC, created_at DESC LIMIT $1 OFFSET $2;";
                    $q1All = "with abc as(SELECT unnest(savedsnippets) AS names FROM users WHERE username ILIKE '$username') SELECT count(*) FROM snips x join abc y on x.file_location = y.names;";
                } else if ($type == 'liked') {
                    $q1 = "with abc as(SELECT unnest(likedsnippets) AS names FROM users WHERE username ILIKE '$username') 
                            SELECT * FROM snips x join abc y on x.file_location = y.names ORDER BY likes DESC, views DESC, created_at DESC LIMIT $1 OFFSET $2;";
                    $q1All = "with abc as(SELECT unnest(likedsnippets) AS names FROM users WHERE username ILIKE '$username') SELECT count(*) FROM snips x join abc y on x.file_location = y.names;";
                }
                $resultPage = pg_query_params($dbcon, $q1, array($pageSize, $pageOffset));
                $resultAll = pg_query($dbcon, $q1All);
                $totalResults = pg_fetch_row($resultAll)[0];
                $totalPages = ceil($totalResults / $pageSize);
                if ($totalResults > 0) {
                    echo '<div class="search-results">
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
                        </div>';
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
                                    <div class="info-creator" onclick="location.href = 'account.php?username=<?= $tuple['creator'] ?>'">
                                        <div class="info-pfp"></div>
                                        <span><?= htmlspecialchars($tuple['creator']) ?></span>
                                    </div>
                                    <div class="info-views">
                                        <div class='data-views-checkmark'>
                                            <svg viewBox='0 0 256 256'>
                                                <path
                                                    d='M31.8 148.4c0-23.1928 28.62-74.2 95.4-74.2s95.4 51.0178 95.4 74.2m-63.6 0a31.8 31.8 90 11-63.6 0 31.8 31.8 90 0163.6 0Z'
                                                    stroke-width='20px' fill='none'></path>
                                            </svg>
                                        </div>
                                        <p class="info-text"><?= htmlspecialchars($tuple['views']) ?></p>
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
                    echo '<p class="no-snippets-text" style="display: flex; justify-content: center; align-items: center; height: 100%; text-align: center;">No snippets found</p>';
                }
                ?>
            </div>
            <?php
        } else { ?>
            <main class="profilo-container">
                <var class="main_var">
                    <section class="profilo-info">
                        <?php
                        if ($username == "" || $type == "") {
                            $text = "Missing username or type.";
                        } else if ($type != "activity" && $type != "liked" && $type != "watchlist") {
                            $text = "Invalid type specified";
                        } else {
                            $text = "Unable to connect to the database. Please try again later.";
                        }
                        ?>
                        <h2><?php echo $text ?></h2>
                    </section>
                </var>
            </main>
            <?php
        }
        ?>
        <?php include 'footer-code.php'; ?> <!--FOOTER-->
    </div>
</body>
<script src="assets/scripts/login.js"></script>
<script src="assets/scripts/signup.js"></script>
<script src="assets/scripts/explorer.js"></script>

</html>