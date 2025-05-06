<?php
//qua in pratica con session_start() pija le info dell'ultima sessione da un file che si è salvato
session_start();
$redirect = 'explorer.php';

function splitFileContent($content)
{
    preg_match('/<style>(.*?)<\/style>/s', $content, $matchesCss);
    preg_match('/<body>(.*?)<script>/s', $content, $matchesHtml);
    preg_match('/<script>(.*?)<\/script>/s', $content, $matchesJs);

    $css = isset($matchesCss[1]) ? trim($matchesCss[1]) : '';
    $html = isset($matchesHtml[1]) ? trim($matchesHtml[1]) : '';
    $js = isset($matchesJs[1]) ? trim($matchesJs[1]) : '';

    return [$html, $css, $js];
}
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
                <img src="assets/search.png" class="search-icon"
                    onclick="document.getElementById('search-form').submit();">
            </div>
        </form>
        <div class="search-output-div">
            <?php
            $search = $_GET['search'] ?? '';
            $dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or -1;
            if ($dbcon != -1) { //se la connessione è correttamente stabilita
                $q1 = "SELECT * FROM snips ORDER BY likes DESC, views DESC";
                $result = pg_query($dbcon, $q1);
                if ($search != '') {
                    $q1 = "SELECT *,
                        CASE
                            WHEN EXISTS (
                                SELECT 1 FROM unnest(tags) AS tag
                                WHERE tag ILIKE $1
                            ) THEN 3
                            WHEN element_type ILIKE $1 THEN 2
                            WHEN creator ILIKE $1 THEN 1
                            ELSE 0
                        END AS relevance
                        FROM snips
                        WHERE EXISTS (
                            SELECT 1 FROM unnest(tags) AS tag
                            WHERE tag ILIKE $1
                        )
                        OR element_type ILIKE $1
                        OR creator ILIKE $1
                        ORDER BY relevance DESC, likes DESC, views DESC;";
                    $result = pg_query_params($dbcon, $q1, array($search));
                }
                echo '<p class="search-results">' . pg_num_rows($result) . ' results</p>';
                if (pg_num_rows($result) > 0) {
                    echo '<div class="search-output">';
                    while ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
                        $fileLocation = $tuple['file_location'];
                        $fileContent = file_get_contents(__DIR__ . "\\snippets\\" . $fileLocation); //search for the file in the server
            
                        list($html, $css, $js) = splitFileContent($fileContent); //split file content into html, css, js
            
                        echo '<div class="output-snip">';
                        echo '<div class="output-snip-opener" onclick="location.href = \'snippet.php?name=' . $fileLocation . '\';">';
                        echo '<span>View code</span>';
                        echo '</div>';
                        echo '<iframe id="output-snip-frame-' . $tuple['id'] . '" class="output-preview"></iframe>';
                        /*here the strat is to create a js snippet containing the json data of the file divided into html css and js
                        and then retrieve it with another script that finds the first one and parses it into a js json object, to then place it inside the iframe*/
                        echo '<script id="snippet-data-' . $tuple['id'] . '" type="application/json">';
                        echo json_encode(['html' => $html, 'css' => $css, 'js' => $js], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
                        echo '</script>';

                        echo '<script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    const data = JSON.parse(document.getElementById("snippet-data-' . $tuple['id'] . '").textContent);
                                    assignIFrame("output-snip-frame-' . $tuple['id'] . '", data.html, data.css, data.js);
                                });
                            </script>';
                        echo '<div class="info">';
                        echo '<div class="info-creator">';
                        echo '<div class="info-pfp"></div>';
                        echo '<span>' . htmlspecialchars($tuple['creator']) . '</span>';
                        echo '</div>';
                        echo '<div class="info-views">';
                        echo '<p class="info-text">' . htmlspecialchars($tuple['views']);
                        echo '<p class="info-subtext"> views</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
            } else {
                echo '<p>Error connecting to databse</p>';
            }
            ?>
        </div>
    </div>
</body>
<script src="assets/scripts/login.js"></script>
<script src="assets/scripts/signup.js"></script>
<script src="assets/scripts/explorer.js"></script>

</html>