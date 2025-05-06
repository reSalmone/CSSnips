<?php
session_start();

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

function capitalizeProper($str)
{
    if (empty($str))
        return $str;
    return strtoupper($str[0]) . strtolower(substr($str, 1));
}

$name = $_GET['name'] ?? '';

$redirect = 'snippet.php';
if ($name != '') {
    $redirect = $redirect . '?name=' . $name;
}
$filePath = __DIR__ . "\\snippets\\" . basename($name);
$dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1");

$content = null;
list($html, $css, $js) = null;
$creator = null;
$views = null;
$likes = null;
$saved = null;
$description = null;
$date = null;
$type = null;
$tags = null;
$id = null;

$found = false;
if (isset($_GET['name']) && file_exists(filename: $filePath)) {
    $found = true;
    $content = file_get_contents($filePath);
    list($html, $css, $js) = splitFileContent($content);

    if ($dbcon != -1) { //se la connessione è correttamente stabilita
        //update views
        $q2 = "UPDATE snips SET views = views + 1 WHERE file_location = $1;";
        $data = pg_query_params($dbcon, $q2, array($name));

        $q1 = "SELECT * from snips where file_location = $1";
        $result = pg_query_params($dbcon, $q1, array($name));
        if ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $creator = $tuple['creator'];
            $views = $tuple['views'];
            $likes = $tuple['likes'];
            $saved = $tuple['saved'];
            $description = $tuple['description'];
            $type = $tuple['element_type'];
            $tags = explode(',', trim($tuple['tags'], '{}'));
            $date = date('d-m-Y', strtotime($tuple['created_at']));
            $id = $tuple['id'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSSnip - View snip</title>
    <link rel="stylesheet" href="snippet.css">
    <link rel="stylesheet" href="assets/NoveoSans-Book/style.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="login-signup.css">
    <link rel="stylesheet" href="checkbox.css"> <!-- Checkbox figa nel login -->
</head>

<body>
    <?php include 'navbar-code.php'; ?> <!--NAVBAR-->
    <?php include 'login-signup-code.php'; ?> <!--LOGIN AND SIGNUP-->
    <div id="rest" onclick="closeLogin(); closeSignup();">
        <div class="snippet-page">
            <div class="snippet-container">
                <?php
                if ($found) {
                    echo '<iframe id="output" class="output-container"></iframe>';
                } else {
                    echo '<div id="output" class="output-container">';
                    echo '<span class="snippet-not-found">Snippet not found</span>';
                    echo '</div>';
                }
                ?>
                <script id="snippet-data" type="application/json">
                    <?= json_encode(['html' => $html, 'css' => $css, 'js' => $js], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>
                </script>

                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const data = JSON.parse(document.getElementById("snippet-data").textContent);
                        assignIFrame("output", data.html, data.css, data.js);
                    });
                </script>
                <div class="code-container">
                    <div class="code-buttons">
                        <div class="lang-buttons-container">
                            <button class="lang-button" id="html-button" onclick="switchLang('html')">HTML</button>
                            <button class="lang-button" id="css-button" onclick="switchLang('css')">CSS</button>
                            <button class="lang-button" id="js-button" onclick="switchLang('js')">JS</button>
                        </div>
                    </div>
                    <div class="code-div">
                        <button class="copy-button" onclick="copyToClipboard();">
                            <img src="copy.png" class="copy-icon">
                            <span id="copy-span">Copy</span>
                        </button>
                        <div class="line-numbers" id="line-numbers">
                        </div>
                        <pre style="margin:0" class="input-area" id="html-area"
                            onscroll="syncScroll(this);"><?php echo htmlspecialchars($html) ?></pre>
                        <pre style="margin:0" class="input-area" id="css-area"
                            onscroll="syncScroll(this);"><?php echo htmlspecialchars($css) ?></pre>
                        <pre style="margin:0" class="input-area" id="js-area"
                            onscroll="syncScroll(this);"><?php echo htmlspecialchars($js) ?></pre>
                    </div>
                </div>
            </div>
            <?php if ($found): ?>
                <div class="data-container">
                    <div class="data-left">
                        <div class="data-user">
                            <span class="data-subtext"><?php echo capitalizeProper($type) . ' by '; ?></span>
                            <div class="data-pfp"></div>
                            <span class="data-text"><?php echo $creator; ?></span>
                        </div>
                    </div>
                    <div class="data-right">
                        <div class="data-likes">
                            <label class='data-like-save-container'>
                                <?php
                                if (isset($_SESSION['username'])) {
                                    if ($dbcon != -1) {
                                        $q1 = "SELECT * from users where username = $1";
                                        $result = pg_query_params($dbcon, $q1, array($_SESSION['username']));
                                        if ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
                                            $likedSnippets = explode(',', trim($tuple['likedsnippets'], '{}'));
                                            $checkedAttribute = in_array($name, $likedSnippets) ? 'checked' : '';
                                            echo "<input $checkedAttribute type='checkbox' data-snippet='$name' id='data-like-checkbox'>";
                                        }
                                    }
                                } else {
                                    echo "<input type='checkbox' onclick='event.preventDefault(); event.stopPropagation(); openLogin(event);'>";
                                }
                                ?>
                                <div class='data-like-checkmark'>
                                    <svg viewBox='0 0 256 256'>
                                        <path
                                            d='M224.6,51.9a59.5,59.5,0,0,0-43-19.9,60.5,60.5,0,0,0-44,17.6L128,59.1l-7.5-7.4C97.2,28.3,59.2,26.3,35.9,47.4a59.9,59.9,0,0,0-2.3,87l83.1,83.1a15.9,15.9,0,0,0,22.6,0l81-81C243.7,113.2,245.6,75.2,224.6,51.9Z'
                                            stroke-width='20px' stroke='#FFF' fill='none'></path>
                                    </svg>
                                </div>
                            </label>
                            <span class="data-text" id="data-liked-value"><?php echo $likes; ?></span>
                        </div>
                        <div class="data-saved">
                            <label class='data-like-save-container'>
                                <?php
                                if (isset($_SESSION['username'])) {
                                    if ($dbcon != -1) {
                                        $q1 = "SELECT * from users where username = $1";
                                        $result = pg_query_params($dbcon, $q1, array($_SESSION['username']));
                                        if ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
                                            $savedSnippets = explode(',', trim($tuple['savedsnippets'], '{}'));
                                            $checkedAttribute = in_array($name, $savedSnippets) ? 'checked' : '';
                                            echo "<input $checkedAttribute type='checkbox' data-snippet='$name' id='data-save-checkbox'>";
                                        }
                                    }
                                } else {
                                    echo "<input type='checkbox' onclick='event.preventDefault(); event.stopPropagation(); openLogin(event);'>";
                                }
                                ?>
                                <div class='data-save-checkmark'>
                                    <svg viewBox='0 0 256 256'>
                                        <path
                                            d='M198.2 51v172.8a6.4 6.4 90 01-10.4 4.9984L127.8 180.7984l-60 48A6.4 6.4 90 0157.4 223.8V51a25.6 25.6 90 0125.6-25.6h89.6a25.6 25.6 90 0125.6 25.6z'
                                            stroke-width='20px' stroke='#FFF' fill='none'></path>
                                    </svg>
                                </div>
                            </label>
                            <span class="data-text" id="data-saved-value"><?php echo $saved; ?></span>
                        </div>
                        <div class="data-views">
                            <img src="assets/images/view.png" class="data-icon">
                            <span class="data-text"><?php echo $views; ?></span>
                        </div>
                    </div>
                </div>
                <div class="info-container">
                    <div class="description-container">
                        <span class="description-title">Description</span>
                        <span class="description-content"><?php echo $description; ?></span>
                    </div>
                    <div class="tags-container">
                        <span class="tags-title">Tags</span>
                        <div id="tags-list">
                            <?php foreach ($tags as $tag): ?>
                                <div class="tags-tag"><?= htmlspecialchars($tag) ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
<script src="assets/scripts/snippet.js"></script>
<script src="assets/scripts/login.js"></script>
<script src="assets/scripts/signup.js"></script>
</html>