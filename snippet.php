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

if (!isset($_GET['name'])) {
    header('Location: explorer.php');
}

$name = $_GET['name'] ?? '';

$redirect = 'snippet.php';
if ($name != '') {
    $redirect = $redirect . '?name=' . $name;
}
$filePath = __DIR__ . "\\snippets\\" . basename($name);
$dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1");

list($html, $css, $js) = null;
$creator = null;
$views = null;
$likes = null;
$saved = null;
$description = null;
$date = null;
$type = null;
$tags = null;
$variationOf = null;
$challengeOf = null;

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
            $tags = ($t = trim($tuple['tags'], '{}')) === '' ? [] : explode(',', $t);
            $date = date('d-m-Y', strtotime($tuple['created_at']));
            $variationOf = $tuple['variation_of'];
            $challengeOf = $tuple['challenge_of'];
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
    <link rel="stylesheet" href="footer.css">
</head>

<body>
    <?php include 'navbar-code.php'; ?> <!--NAVBAR-->
    <?php include 'login-signup-code.php'; ?> <!--LOGIN AND SIGNUP-->
    <div id="rest" onclick="closeLogin(); closeSignup();">
        <div class="snippet-page">
            <?php if ($variationOf != null) {
                $v_creator = null;
                $v_type = null;

                $v_q1 = "SELECT * from snips where file_location = $1";
                $v_result = pg_query_params($dbcon, $v_q1, array($variationOf));
                if ($v_tuple = pg_fetch_array($v_result, null, PGSQL_ASSOC)) {
                    $v_creator = $v_tuple['creator'];
                    $v_type = $v_tuple['element_type'];
                }

                echo '<div class="variation-container">';
                echo '<span class="variation-subtext">Variation of <a href="snippet.php?name=' . $variationOf . '" class="variation-text">' . $v_type . '</a> by</span>';
                echo '<div class="variation-user" onclick="location.href = \'account2.php?username=' . $v_creator . '\'">';
                echo '<div class="variation-pfp"></div>';
                echo '<span class="variation-text">' . $v_creator . '</span>';
                echo '</div>';
                echo '</div>';
            } else if ($challengeOf != null) {
                echo '<div class="variation-container">';
                echo '<span class="variation-subtext">Submission for challenge <a href="challenge_selected.php?name=' . urlencode($challengeOf) . '" class="variation-text">' . $challengeOf . '</a></span>';
                echo '</div>';
            }
            ?>
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
                        <div class="data-user" onclick="location.href = 'account2.php?username=<?= $creator ?>'">
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
                                            stroke-width='20px' fill='none'></path>
                                    </svg>
                                </div>
                            </label>
                            <span class="data-text" id="data-saved-value"><?php echo $saved; ?></span>
                        </div>
                        <div class="data-views">
                            <div class="data-like-save-container">
                                <div class='data-views-checkmark'>
                                    <svg viewBox='0 0 256 256'>
                                        <path
                                            d='M31.8 148.4c0-23.1928 28.62-74.2 95.4-74.2s95.4 51.0178 95.4 74.2m-63.6 0a31.8 31.8 90 11-63.6 0 31.8 31.8 90 0163.6 0Z'
                                            stroke-width='20px' fill='none'></path>
                                    </svg>
                                </div>
                            </div>
                            <span class="data-text"><?php echo $views; ?></span>
                        </div>
                    </div>
                </div>
                <div class="actions-container">
                    <div class="left-actions-container">
                        <button class="actions-button" onclick="window.location = 'creator.php?clone=<?php echo $name ?>'">
                            <div class='actions-svg'>
                                <svg viewBox='0 0 256 256'>
                                    <path
                                        d='M106 74.2A21.2 21.2 90 0084.8 95.4V201.4A21.2 21.2 90 00106 222.6H190.8A21.2 21.2 90 00212 201.4V95.4A21.2 21.2 90 00190.8 74.2H106ZM169.6 53A21.2 21.2 90 00148.4 31.8H63.6A21.2 21.2 90 0042.4 53V159A21.2 21.2 90 0063.4 180.2'
                                        stroke-width='20px' fill='none'></path>
                                </svg>
                            </div>
                            <span>Clone</span>
                        </button>
                        <?php if ($variationOf == null) { ?>
                        <button class="actions-button" onclick="window.location = 'creator.php?variation=<?php echo $name ?>'">
                            <div class='actions-svg'>
                                <svg viewBox='0 0 256 256'>
                                    <path
                                        d='M106 74.2A21.2 21.2 90 0084.8 95.4V201.4A21.2 21.2 90 00106 222.6H190.8A21.2 21.2 90 00212 201.4V95.4A21.2 21.2 90 00190.8 74.2H106ZM169.6 53A21.2 21.2 90 00148.4 31.8H63.6A21.2 21.2 90 0042.4 53V159A21.2 21.2 90 0063.4 180.2'
                                        stroke-width='20px' stroke='#FFF' fill='none'></path>
                                </svg>
                            </div>
                            <span>Add variation</span>
                        </button>
                        <?php } ?>
                        <?php if (isset($_SESSION['username']) && $creator == $_SESSION['username']) { ?>
                            <button class="actions-button" id="actions-important"
                                onclick="window.location = 'creator.php?edit=<?php echo $name ?>'">
                                <div class='actions-svg'>
                                    <svg viewBox='0 0 256 256'>
                                        <path
                                            d='M225.5 67.8 124.4 168.9C114.3 179.0 84.4 183.6 77.8 177.0 71.1 170.3 75.6 140.4 85.7 130.3L186.9 29.1C189.4 26.4 192.5 24.2 195.8 22.7 199.2 21.1 202.8 20.3 206.5 20.2 210.2 20.2 213.9 20.8 217.3 22.2 220.7 23.6 223.8 25.7 226.5 28.3 229.1 30.9 231.1 34.0 232.5 37.4 233.9 40.9 234.5 44.5 234.4 48.2 234.3 51.9 233.5 55.6 232.0 58.9 230.4 62.3 228.2 65.3 225.5 67.8V67.8ZM116.6 42.4H63.6C52.3 42.4 41.5 46.8 33.6 54.8 25.6 62.7 21.2 73.5 21.2 84.8V190.8C21.2 202.0 25.6 212.8 33.6 220.7 41.5 228.7 52.3 233.2 63.6 233.2H180.2C203.6 233.2 212 214.1 212 190.8V137.8'
                                            stroke-width='20px' stroke='#FFF' fill='none'></path>
                                    </svg>
                                </div>
                                <span>Edit snippet</span>
                            </button>
                            <button class="actions-button" id="actions-important" onclick="location.href = 'delete.php?name=<?php echo $name ?>'">
                                <div class='actions-svg'>
                                    <svg viewBox='0 0 256 256'>
                                        <path
                                            d='M197.5 41.6H151.2L148.6 36.5C146.6 32.4 142.6 30 138.2 30H94.5C90.2 30 86.1 32.4 81.4 41.6H35 M46.6 81.4 54.3 204.6C54.8 213.7 62.5 220.9 71.6 220.9H161C170.1 220.9 177.8 213.8 178.3 204.6L186.1 81.4Z'
                                            stroke-width='20px' stroke='#FFF' fill='none'></path>
                                    </svg>
                                </div>
                                <span>Delete snippet</span>
                            </button>
                        <?php } ?>
                    </div>
                    <div class="right-actions-container">
                        <button class="actions-button" id="actions-important">
                            <div class='actions-svg'>
                                <svg viewBox='0 0 256 256'>
                                    <path
                                        d='M127.2 159H127.306M127.2 127.2V95.4M52.8099 201.4H201.5897C217.9519 201.4 228.147 183.6514 219.9023 169.5184L145.5126 41.9922C137.3315 27.9683 117.0685 27.9683 108.8874 41.9922L34.4979 169.5184C26.2536 183.6514 36.448 201.4 52.8099 201.4Z'
                                        stroke-width='20px' stroke='#FFF' fill='none' stroke-linecap='round'></path>
                                </svg>
                            </div>
                            <span>Report</span>
                        </button>
                    </div>
                </div>
                <div class="info-container">
                    <div class="description-container">
                        <span class="description-title">Description</span>
                        <span class="description-content"><?php echo htmlspecialchars($description); ?></span>
                    </div>
                    <div class="tags-container">
                        <span class="tags-title">Tags</span>
                        <div id="tags-list">
                            <?php
                            if (empty($tags)) {
                                echo '<span class="tags-no-tags">No tags</span>';
                            } else { 
                                foreach ($tags as $tag): ?>
                                <div class="tags-tag"><?= htmlspecialchars($tag) ?></div>
                            <?php endforeach; } ?>
                        </div>
                    </div>
                    <!--IF IT'S NOT A VARIATION, IT CAN THEN HAVE OTHER VARIATIONS-->
                    <?php if ($variationOf == null) { ?>
                        <div class="info-variations-container">
                            <span class="info-variations-title">Variations</span>
                            <div class="info-variations-output">
                                <?php
                                $va_q1 = "SELECT * FROM snips WHERE variation_of = $1 ORDER BY likes DESC, views DESC";
                                $va_result = pg_query_params($dbcon, $va_q1, array($name));
                                echo '<p class="info-variations-search-results">' . pg_num_rows($va_result) . ' variations</p>';
                                if (pg_num_rows($va_result) > 0) {
                                    echo '<div class="info-variations-search-output">';
                                    while ($va_tuple = pg_fetch_array($va_result, null, PGSQL_ASSOC)) {
                                        $va_fileLocation = $va_tuple['file_location'];
                                        if (file_exists(__DIR__ . "\\snippets\\" . $va_fileLocation)) {
                                            $va_fileContent = file_get_contents(__DIR__ . "\\snippets\\" . $va_fileLocation); //search for the file in the server
                        
                                            list($va_html, $va_css, $va_js) = splitFileContent($va_fileContent); //split file content into html, css, js
                        
                                            echo '<div class="info-variations-output-snip">';
                                            echo '<div class="info-variations-output-snip-opener" onclick="location.href = \'snippet.php?name=' . $va_fileLocation . '\';">';
                                            echo '<span>View code</span>';
                                            echo '</div>';
                                            echo '<iframe id="info-variations-output-snip-frame-' . $va_tuple['id'] . '" class="info-variations-output-preview"></iframe>';
                                            echo '<script id="info-variations-snippet-data-' . $va_tuple['id'] . '" type="application/json">';
                                            echo json_encode(['html' => $va_html, 'css' => $va_css, 'js' => $va_js], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
                                            echo '</script>';

                                            echo '<script>
                                                        document.addEventListener("DOMContentLoaded", function() {
                                                            const data = JSON.parse(document.getElementById("info-variations-snippet-data-' . $va_tuple['id'] . '").textContent);
                                                            assignIFrame("info-variations-output-snip-frame-' . $va_tuple['id'] . '", data.html, data.css, data.js);
                                                        });
                                                    </script>';
                                            echo '<div class="info-variations-info">';
                                            echo '<div class="info-variations-info-creator" onclick="location.href = \'account2.php?username=' . $va_tuple['creator'] . '\'">';
                                            echo '<div class="info-variations-info-pfp"></div>';
                                            echo '<span>' . $va_tuple['creator'] . '</span>';
                                            echo '</div>';
                                            echo '<div class="info-variations-info-views">';
                                            echo '<p class="info-variations-info-text">' . htmlspecialchars($va_tuple['views']);
                                            echo '<p class="info-variations-info-subtext"> views</p>';
                                            echo '</div>';
                                            echo '</div>';
                                            echo '</div>';
                                        } else {
                                            echo 'Your server files aren\' synched with the database: file \'' . $va_fileLocation . '\' is missing';
                                        }
                                    }
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php endif; ?>
        </div>
        <?php include 'footer-code.php'; ?> <!--FOOTER-->
    </div>
</body>
<script src="assets/scripts/snippet.js"></script>
<script src="assets/scripts/login.js"></script>
<script src="assets/scripts/signup.js"></script>

</html>