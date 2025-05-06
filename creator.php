<?php
session_start();
$redirect = 'creator.php';

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

if (isset($_POST['remove-variation'])) {
    unset($_SESSION['variation']);
}

$name = null;
$foundClone = false;
$foundVariation = false;
$foundSessionVariation = false;
if (isset($_GET['clone'])) {
    $name = $_GET['clone'];
    $foundClone = true;
}
if (isset($_GET['variation'])) {
    $name = $_GET['variation'];
    $foundVariation = true;
    $_SESSION['variation'] = $name;
}
if (isset($_SESSION['variation'])) {
    $foundSessionVariation = true;
    $name = $_SESSION['variation'];
}

list($html, $css, $js) = null;
$creator = null;
$type = null;

$found = false;
if ($name != '') {
    $filePath = __DIR__ . "\\snippets\\" . basename($name);
    $dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1");
    if (file_exists(filename: $filePath)) {
        $content = file_get_contents($filePath);
        list($html, $css, $js) = splitFileContent($content);

        if ($dbcon) {
            $q1 = "SELECT * from snips where file_location = $1";
            $result = pg_query_params($dbcon, $q1, array($name));
            if ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
                $found = true;
                $creator = $tuple['creator'];
                $type = $tuple['element_type'];

                if (!$foundSessionVariation) {
                    echo '<script>localStorage.clear();</script>';
                }
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSSnip - Create</title>
    <link rel="stylesheet" href="creator.css">
    <link rel="stylesheet" href="assets/NoveoSans-Book/style.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="login-signup.css">
    <link rel="stylesheet" href="checkbox.css"> <!-- Checkbox figa nel login -->
    <script>
        function removeQueryParam(key) {
            const url = new URL(window.location);
            url.searchParams.delete(key);
            window.history.replaceState({}, '', url);
        }
    </script>
    <?php
    if ($foundClone) {
        echo "<script>removeQueryParam('clone')</script>";
    }
    if ($foundVariation) {
        echo "<script>removeQueryParam('variation')</script>";
    }
    ?>
</head>

<body>
    <?php include 'navbar-code.php'; ?> <!--NAVBAR-->
    <?php include 'login-signup-code.php'; ?> <!--LOGIN AND SIGNUP-->
    <div class="center-div post-center-div" id="post-center-div">
        <div class="post-page">
            <div class="post-title-container">
                <span class="post-title">Snippet preview</span>
                <span class="post-subtitle">Check your snippet before submitting the post</span>
            </div>
            <iframe id="post-preview"></iframe>
            <div class="post-server-error-container" id="post-server-error"></div>
            <div class="post-info">
                <?php if (($foundVariation && $found) || ($foundSessionVariation && $found)) {
                    echo '<div class="post-variation-container">';
                    echo '<span class="post-variation-subtext">Posting as variation of <span class="post-variation-text">' . $type . '</span> by</span>';
                    echo '<div class="post-variation-user">';
                    echo '<div class="post-variation-pfp"></div>';
                    echo '<span class="post-variation-text">' . $creator . '</span>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
                <div class="post-name-container">
                    <div class="post-name-title-container">
                        <span class="post-info-title">Snippet's name</span>
                        <span class="post-info-subtitle">Add a name to the snippet to save it as unique key</span>
                    </div>
                    <div class="post-name-input-container">
                        <input type="text" class="post-name-input" id="post-name" spellcheck="false"
                            oninput="checkNameAvailability();">
                        <div class='post-name-check-checkmark' id="post-name-check-success">
                            <svg viewBox='0 0 256 256'>
                                <path d='M51.2 140.8 102.4 192 204.8 64' stroke-width='20px' fill='none'
                                    stroke-linecap='round'>
                                </path>
                            </svg>
                        </div>
                        <div class='post-name-check-checkmark' id="post-name-check-failure">
                            <svg viewBox='0 0 256 256'>
                                <path d='M51.2 51.2 204.8 204.8M204.8 51.2 51.2 204.8' stroke-width='20px' fill='none'
                                    stroke-linecap='round'>
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
                <span id="post-type"></span>
                <div class="post-description-container">
                    <span class="post-info-title">Description</span>
                    <span id="post-description-content"></span>
                </div>
                <div class="post-tags-container">
                    <span class="post-info-title">Snippet's tags</span>
                    <div id="post-tags-list"></div>
                </div>
            </div>
            <div class="post-actions">
                <button class="post-action-button" onclick="closePost();">Cancel</button>
                <button class="post-action-button" onclick="postSnippet();">Post</button>
            </div>
        </div>
    </div>

    <div id="rest" onclick="closeLogin(); closeSignup(); closePost();">
        <div class="snippet-page">
            <?php if (($foundVariation && $found) || ($foundSessionVariation && $found)) {
                echo '<div class="variation-container">';
                echo '<span class="variation-subtext">Creating a variation of <a href="snippet.php?name=' . $name . '" class="variation-text">' . $type . '</a> by</span>';
                echo '<div class="variation-user">';
                echo '<div class="variation-pfp"></div>';
                echo '<span class="variation-text">' . $creator . '</span>';
                echo '</div>';
                echo '<span id="variation-name" hidden>' . $name . '</span>';
                echo '<form method="post" action="">';
                echo '<button class="variation-remove" type="submit" name="remove-variation">
                        <div class="variation-remove-checkmark">
                            <svg viewBox="0 0 256 256">
                                <path d="M51.2 51.2 204.8 204.8M204.8 51.2 51.2 204.8" stroke-width="20px" fill="none" stroke-linecap="round">
                                </path>
                            </svg>
                        </div>
                    </button>';
                echo '</form>';
                echo '</div>';
            }
            ?>
            <div class="snippet-action-bar">
                <div class="left-action-buttons">
                    <div class="type-dropdown-div">
                        <button class="action-button" id="current-type"></button>
                        <div class="type-dropdown-container">
                            <div class="type-dropdown-content">
                                <button class="type-dropdown-button"
                                    onclick="setElementType(this, event);">Button</button>
                                <button class="type-dropdown-button"
                                    onclick="setElementType(this, event);">Switch</button>
                                <button class="type-dropdown-button"
                                    onclick="setElementType(this, event);">Input</button>
                                <button class="type-dropdown-button"
                                    onclick="setElementType(this, event);">Form</button>
                                <button class="type-dropdown-button"
                                    onclick="setElementType(this, event);">Card</button>
                                <button class="type-dropdown-button"
                                    onclick="setElementType(this, event);">Loader</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="right-action-buttons">
                    <div class="toggle-switch">
                        <label class="switch-label">
                            <input type="checkbox" class="checkbox">
                            <span class="slider"></span>
                        </label>
                    </div>
                    <button class="action-button" id="action-reset" onclick="resetSnippet();">Reset snippet</button>
                    <?php
                    $action = isset($_SESSION['username']) ? "saveDraft(event);" : "openLogin(event);";
                    echo '<button class="action-button" type="button" onclick="' . $action . '">Save draft</button>';
                    $action = isset($_SESSION['username']) ? "openPost(event);" : "openLogin(event);";
                    echo '<button class="action-button" type="button" onclick="' . $action . '">Post snippet</button>';
                    ?>
                </div>
            </div>
            <div class="snippet-container">
                <iframe id="output" class="output-container">

                </iframe>
                <div class="code-container">
                    <div class="code-buttons">
                        <div class="lang-buttons-container">
                            <button class="lang-button" id="html-button" onclick="switchLang('html')">Html</button>
                            <button class="lang-button" id="css-button" onclick="switchLang('css')">Css</button>
                            <button class="lang-button" id="js-button" onclick="switchLang('js')">Js</button>
                        </div>
                    </div>
                    <div class="code-div">
                        <button class="copy-button" onclick="copyToClipboard();">
                            <img src="copy.png" class="copy-icon">
                            <span id="copy-span">Copy</span>
                        </button>
                        <div class="line-numbers" id="line-numbers">
                            <div>1</div>
                        </div>
                        <textarea class="input-area" id="html-area"
                            oninput="updateLines(this); displayCode(); saveInLocalStorage();"
                            onscroll="syncScroll(this);" onkeydown="insertTab(event, this)" spellcheck="false"
                            placeholder="Html code"><?php
                            if ($found) {
                                echo htmlspecialchars($html);
                            }
                            ?></textarea>
                        <textarea class="input-area" id="css-area"
                            oninput="updateLines(this); displayCode(); saveInLocalStorage();"
                            onscroll="syncScroll(this);" onkeydown="insertTab(event, this)" spellcheck="false"
                            placeholder="Css code"><?php
                            if ($found) {
                                echo htmlspecialchars($css);
                            }
                            ?></textarea>
                        <textarea class="input-area" id="js-area"
                            oninput="updateLines(this); displayCode(); saveInLocalStorage();"
                            onscroll="syncScroll(this);" onkeydown="insertTab(event, this)" spellcheck="false"
                            placeholder="JavaScript code"><?php
                            if ($found) {
                                echo htmlspecialchars($js);
                            }
                            ?></textarea>
                    </div>
                </div>
            </div>
            <div class="info-container">
                <div class="description-container">
                    <span class="description-title">Add a description</span>
                    <textarea class="description-area" id="description-area"
                        oninput="saveInLocalStorage();">This is my new element</textarea>
                </div>
                <div class="tags-container">
                    <div class="tags-title-container">
                        <span class="tags-title">Add tags to make the snip easier to find</span>
                        <span class="tags-subtitle">You are free to add up to 10 tags</span>
                    </div>
                    <div class="tags-input-container">
                        <input type="text" class="tags-input" id="tags-input" placeholder="Add a tag..."
                            spellcheck="false">
                        <button class="tags-add" onclick="addTag()">Add tag</button>
                    </div>
                    <span class="tags-subtitle">Current tags:</span>
                    <div id="tags-list">
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="assets/scripts/creator.js"></script>
<script src="assets/scripts/login.js"></script>
<script src="assets/scripts/signup.js"></script>

</html>