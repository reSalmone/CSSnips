<?php
//qua in pratica con session_start() pija le info dell'ultima sessione da un file che si è salvato
session_start();
if (isset($_SERVER['REQUEST_URI'])) {
    $redirect = $_SERVER['REQUEST_URI'];
}


$search = $_GET['search'] ?? '';

$pageSize = 12;
$page = 1;
if (isset($_GET['page'])) {
    $page = intval($_GET['page']);
}
$pageOffset = ($page - 1) * $pageSize;

$likedSnippets = null;
if (isset($_SESSION['username'])) {
    $dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or -1;
    if ($dbcon != -1) {
        $q1 = "SELECT * from users where username = $1";
        $result = pg_query_params($dbcon, $q1, array($_SESSION['username']));
        if ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $likedSnippets = explode(',', trim($tuple['likedsnippets'], '{}'));
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSSnips - Explorer</title>
    <link rel="icon" href="assets/images/icon.png">
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

    <div class="center-div info-dialog-center-div" id="info-center-div">
        <div class="info-dialog-page">
            <div class="info-dialog-title-container">
                <span class="info-dialog-title">Info</span>
                <span class="info-dialog-text" id="info-text"></span>
            </div>
            <div class="info-dialog-actions">
                <button class="info-dialog-action-button" onclick="closeInfo();">Okay</button>
            </div>
        </div>
    </div>

    <div id="rest" onclick="closeLogin(); closeSignup();">
        <div class="title-container">
            <span class="title" id="wave">Explorer</span>
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
        <div class="search-output-div" id="search-output">
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
                            WHEN challenge_of ILIKE $1 THEN 3
                            WHEN element_type ILIKE $1 THEN 2
                            WHEN creator ILIKE $1 THEN 1
                            ELSE 0
                        END AS relevance
                        FROM snips
                        WHERE EXISTS (
                            SELECT 1 FROM unnest(tags) AS tag
                            WHERE tag ILIKE $1
                        )
                        OR challenge_of ILIKE $1
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
                                    <div class="info-creator" onclick="location.href = 'account.php?username=<?= $tuple['creator'] ?>'">
                                        <div class="info-pfp"></div>
                                        <span><?= htmlspecialchars($tuple['creator']) ?></span>
                                    </div>
                                    <div class="info-right">
                                        <div class="info-likes">
                                            <label class="info-likes-div">
                                                <?php
                                                if (isset($_SESSION['username'])) {
                                                    if ($dbcon != -1) {
                                                        $snippetName = $tuple['file_location'];
                                                        $checkedAttribute = in_array($snippetName, $likedSnippets) ? 'checked' : '';
                                                        echo "<input $checkedAttribute type='checkbox' data-snippet='$snippetName' class='info-like-checkbox'>";
                                                    }
                                                } else {
                                                    echo "<input type='checkbox' onclick='event.preventDefault(); event.stopPropagation(); openLogin(event);'>";
                                                }
                                                ?>
                                                <div class='info-likes-checkmark'>
                                                    <svg viewBox='0 0 256 256'>
                                                        <path
                                                            d='M224.6,51.9a59.5,59.5,0,0,0-43-19.9,60.5,60.5,0,0,0-44,17.6L128,59.1l-7.5-7.4C97.2,28.3,59.2,26.3,35.9,47.4a59.9,59.9,0,0,0-2.3,87l83.1,83.1a15.9,15.9,0,0,0,22.6,0l81-81C243.7,113.2,245.6,75.2,224.6,51.9Z'
                                                            stroke-width='20px' fill='none'></path>
                                                    </svg>
                                                </div>
                                            </label>
                                            <p class="info-text" id="info-likes-<?= $id ?>"><?= htmlspecialchars($tuple['likes']) ?></p>
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
<?php if (isset($_GET['info'])) { ?>
    <script>
        openInfo(null, "<?= $_GET['info'] ?>");
        const url = new URL(window.location);
        url.searchParams.delete('info');
        window.history.replaceState({}, '', url);
    </script>
<?php } ?>
<?php if ($search != '') { ?>
    <script>location.hash = "#search-output";</script>
<?php } ?>
<script>
    const waveElement = document.getElementById("wave");
    const text = waveElement.textContent;
    waveElement.innerHTML = "";

    [...text].forEach((char, i) => {
      const span = document.createElement("span");
      span.textContent = char;
      span.style.animationDelay = `${i * 0.1}s`;
      waveElement.appendChild(span);
    });
</script>
</html>