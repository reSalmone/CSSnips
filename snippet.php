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

function timeAgo($past, DateTime $now = null): string
{
    $past = $past instanceof DateTime
        ? $past
        : new DateTime($past);
    $now = $now ?: new DateTime;
    $diff = $now->diff($past);
    $d = $diff->days;
    $h = $diff->h;
    $m = $diff->i;
    $s = $diff->s;

    if ($d > 0) {
        return sprintf('%dd %02dh %02dm %02ds ago', $d, $h, $m, $s);
    }
    if ($h > 0) {
        return sprintf('%dh %02dm %02ds ago', $h, $m, $s);
    }
    if ($m > 0) {
        return sprintf('%dm %02ds ago', $m, $s);
    }
    return sprintf('%ds ago', $s);
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
if ($name != '' && file_exists(filename: $filePath)) {
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

$avatar = "https://robohash.org/" . urlencode($creator) . ".png?set=set1&bgset=bg1";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSSnip - View snip</title>
    <link rel="icon" href="assets/images/icon.png">
    <link rel="stylesheet" href="css/snippet.css">
    <link rel="stylesheet" href="assets/NoveoSans-Book/style.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/login-signup.css">
    <link rel="stylesheet" href="css/checkbox.css"> <!-- Checkbox figa nel login -->
    <link rel="stylesheet" href="css/footer.css">
</head>

<body>
    <?php include 'navbar-code.php'; ?> <!--NAVBAR-->
    <?php include 'login-signup-code.php'; ?> <!--LOGIN AND SIGNUP-->

    <div class="center-div report-center-div" id="report-center-div">
        <div class="report-page">
            <div class="report-title-container">
                <span class="report-title">Report snippet</span>
                <span class="report-subtitle">Select a report reason</span>
            </div>
            <form id="report-form" class="report-form" onsubmit="reportFormSubmit(event);">
                <div class="report-radio-container">
                    <label for="copyright" class="report-label">
                        <input type="radio" id="copyright" name="reportReason" class="report-radio">
                        <div class="report-radio-div"><span class="report-radio-span"></span></div>
                        <div class="radio-title-container">
                            <span class="radio-title">Copyright violation</span>
                            <span class="radio-subtitle">Choose this if you believe this post infringes on someone's
                                intellectual property or copyrighted content</span>
                        </div>
                    </label>
                    <label for="inappropriate" class="report-label">
                        <input type="radio" id="inappropriate" name="reportReason" class="report-radio">
                        <div class="report-radio-div"><span class="report-radio-span"></span></div>
                        <div class="radio-title-container">
                            <span class="radio-title">Misleading or Inappropriate Content</span>
                            <span class="radio-subtitle">Select this if this post contains false, misleading, or
                                inappropriate information that may deceive users or promote harmful actions</span>
                        </div>
                    </label>
                    <label for="spam" class="report-label">
                        <input type="radio" id="spam" name="reportReason" class="report-radio">
                        <div class="report-radio-div"><span class="report-radio-span"></span></div>
                        <div class="radio-title-container">
                            <span class="radio-title">Spam or Malicious Content</span>
                            <span class="radio-subtitle">Report this if this post seems to be spam, promotes phishing,
                                or contains malicious intent or links</span>
                        </div>
                    </label>
                    <label for="other" class="report-label">
                        <input type="radio" id="other" name="reportReason" class="report-radio">
                        <div class="report-radio-div"><span class="report-radio-span"></span></div>
                        <div class="radio-title-container">
                            <span class="radio-title">Other</span>
                            <span class="radio-subtitle">If your reason doesn't fit the categories above, select this
                                and provide more details in the subsequent field</span>
                        </div>
                    </label>
                </div>
                <div class="report-textarea-container">
                    <span class="report-textarea-title">Write anything that will help us verify your claim, like details
                        or links</span>
                    <textarea class="report-textarea" name="reportComment" spellcheck="false"
                        placeholder="Report comment..."></textarea>
                </div>
                <button type="submit" class="report-submit">Submit</button>
            </form>
        </div>
    </div>

    <div class="center-div confirm-delete-center-div" id="confirm-delete-center-div">
        <div class="confirm-delete-page">
            <div class="confirm-delete-title-container">
                <span class="confirm-delete-title">Confirm action</span>
                <span class="confirm-delete-subtitle">This action is irreversable - this snip will be deleted and can
                    NOT be restored</span>
            </div>
            <div class="confirm-delete-actions">
                <button class="confirm-delete-action-button confirm-delete"
                    onclick="location.href = 'handlers/delete.php?name=<?php echo $name ?>'">Delete</button>
                <button class="confirm-delete-action-button" onclick="closeConfirmDelete();">Cancel</button>
            </div>
        </div>
    </div>

    <div class="center-div confirm-delete-center-div" id="confirm-delete-comment-center-div">
        <div class="confirm-delete-page">
            <div class="confirm-delete-title-container">
                <span class="confirm-delete-title">Confirm action</span>
                <span class="confirm-delete-subtitle">Are you sure you want to delete this comment</span>
            </div>
            <div class="confirm-delete-actions">
                <button class="confirm-delete-action-button confirm-delete" id="confirm-delete-comment">Delete</button>
                <button class="confirm-delete-action-button" onclick="closeConfirmDeleteComment();">Cancel</button>
            </div>
        </div>
    </div>

    <div class="center-div info-center-div" id="info-center-div">
        <div class="info-page">
            <div class="info-title-container">
                <span class="info-title">Info</span>
                <span class="info-text" id="info-text"></span>
            </div>
            <div class="info-actions">
                <button class="info-action-button" onclick="closeInfo();">Okay</button>
            </div>
        </div>
    </div>

    <div id="rest"
        onclick="closeLogin(); closeSignup(); closeReport(); closeConfirmDelete(); closeInfo(); closeConfirmDeleteComment();">
        <div class="snippet-page">
            <?php if ($variationOf != null) {
                $v_creator = null;
                $v_type = null;
                $v_avatar = null;

                $v_q1 = "SELECT * from snips where file_location = $1";
                $v_result = pg_query_params($dbcon, $v_q1, array($variationOf));
                if ($v_tuple = pg_fetch_array($v_result, null, PGSQL_ASSOC)) {
                    $v_creator = $v_tuple['creator'];
                    $v_type = $v_tuple['element_type'];
                    $v_avatar = "https://robohash.org/" . urlencode($v_creator) . ".png?set=set1&bgset=bg1";
                }
                ?>

                <div class="variation-container">
                    <span class="variation-subtext">Variation of <a href="snippet.php?name=<?= $variationOf ?>"
                            class="variation-text"><?= $v_type ?></a> by</span>
                    <div class="variation-user" onclick="location.href = 'account.php?username=<?= $v_creator ?>'">
                        <div class="avatar-div">
                            <img src="<?= $v_avatar ?>" alt="Avatar" class="variation-avatar-img">
                        </div>
                        <span class="variation-text"><?= $v_creator ?></span>
                    </div>
                </div>
            <?php } else if ($challengeOf != null) { ?>
                    <div class="variation-container">
                        <span class="variation-subtext">Submission for challenge <a
                                href="challenge_selected.php?name=<?= urlencode($challengeOf) ?>"
                                class="variation-text"><?= $challengeOf ?></a></span>
                    </div>
            <?php } ?>
            <div class="snippet-container">
                <?php if ($found) { ?>
                    <iframe id="output" class="output-container"></iframe>
                <?php } else { ?>
                    <div id="output" class="output-container">
                        <span class="snippet-not-found">Snippet not found</span>
                    </div>
                <?php } ?>
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
                        <div class="code-actions">
                            <svg viewBox='0 0 256 256' class="code-actions-svg">
                                <path d='M42.4 63.6H212M42.4 127.2H212M42.4 190.8H212' stroke-width='20px' fill='none'
                                    stroke-linecap='round'></path>
                            </svg>
                            <button class="code-actions-button" onclick="copyToClipboard();">
                                <span id="copy-span">Copy</span>
                            </button>
                        </div>
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
                        <span class="data-subtext"><?php echo capitalizeProper($type) . ' by '; ?></span>
                        <div class="data-user" onclick="location.href = 'account.php?username=<?= $creator ?>'">
                            <div class="avatar-div">
                                <img src="<?= $avatar ?>" alt="Avatar" class="creator-avatar-img">
                            </div>
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
                            <span>Clone snippet</span>
                        </button>
                        <?php if ($variationOf == null) { ?>
                            <button class="actions-button"
                                onclick="window.location = 'creator.php?variation=<?php echo $name ?>'">
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
                            <button class="actions-button" id="actions-important" onclick="openConfirmDelete(event);">
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
                        <button class="actions-button" id="actions-important" onclick="openReport(event);">
                            <div class='actions-svg'>
                                <svg viewBox='0 0 256 256'>
                                    <path
                                        d='M127.2 159H127.306M127.2 127.2V95.4M52.8099 201.4H201.5897C217.9519 201.4 228.147 183.6514 219.9023 169.5184L145.5126 41.9922C137.3315 27.9683 117.0685 27.9683 108.8874 41.9922L34.4979 169.5184C26.2536 183.6514 36.448 201.4 52.8099 201.4Z'
                                        stroke-width='20px' stroke='#FFF' fill='none' stroke-linecap='round'></path>
                                </svg>
                            </div>
                            <span>Report snippet</span>
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
                                    <div class="tags-tag"
                                        onclick="location.href = 'explorer.php?search=<?= htmlspecialchars($tag) ?>'">
                                        <?= htmlspecialchars($tag) ?>
                                    </div>
                                <?php endforeach;
                            } ?>
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
                                            $va_avatar = "https://robohash.org/" . urlencode($va_tuple['creator']) . ".png?set=set1&bgset=bg1";
                                            $va_fileContent = file_get_contents(__DIR__ . "\\snippets\\" . $va_fileLocation); //search for the file in the server
                        
                                            list($va_html, $va_css, $va_js) = splitFileContent($va_fileContent); //split file content into html, css, js
                                            ?>

                                            <div class="info-variations-output-snip">
                                                <div class="info-variations-output-snip-opener"
                                                    onclick="location.href = 'snippet.php?name=<?= $va_fileLocation ?>'">
                                                    <span>View code</span>
                                                </div>
                                                <iframe id="info-variations-output-snip-frame-<?= $va_tuple['id'] ?>"
                                                    class="info-variations-output-preview"></iframe>
                                                <script id="info-variations-snippet-data-<?= $va_tuple['id'] ?>" type="application/json">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <?= json_encode(['html' => $va_html, 'css' => $va_css, 'js' => $va_js], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </script>

                                                <script>
                                                    document.addEventListener("DOMContentLoaded", function () {
                                                        const data = JSON.parse(document.getElementById("info-variations-snippet-data-<?= $va_tuple['id'] ?>").textContent);
                                                        assignIFrame("info-variations-output-snip-frame-<?= $va_tuple['id'] ?>", data.html, data.css, data.js);
                                                    });
                                                </script>
                                                <div class="info-variations-info">
                                                    <div class="info-variations-info-creator"
                                                        onclick="location.href = 'account.php?username=<?= $va_tuple['creator'] ?>'">
                                                        <div class="avatar-div">
                                                            <img src="<?= $va_avatar ?>" alt="Avatar" class="info-variations-avatar-img">
                                                        </div>
                                                        <span><?= $va_tuple['creator'] ?></span>
                                                    </div>
                                                    <div class="info-variations-info-views">
                                                        <div class='info-variations-info-views-svg'>
                                                            <svg viewBox='0 0 256 256'>
                                                                <path
                                                                    d='M31.8 148.4c0-23.1928 28.62-74.2 95.4-74.2s95.4 51.0178 95.4 74.2m-63.6 0a31.8 31.8 90 11-63.6 0 31.8 31.8 90 0163.6 0Z'
                                                                    stroke-width='20px' fill='none'></path>
                                                            </svg>
                                                        </div>
                                                        <p class="info-variations-info-text"><?= htmlspecialchars($va_tuple['views']) ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } else {
                                            echo 'Your server files aren\' synched with the database: file \'' . $va_fileLocation . '\' is missing';
                                        }
                                    }
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="comments-container" id="comments">
                        <div class="comments-title-container">
                            <span class="comments-title">Comments</span>
                        </div>
                        <form class="comments-form" id="comments-form" onsubmit="postComment(event);">
                            <div class="comment-form-reply" id="replying-to">
                                <span class="comment-form-reply-text">Replying to</span>
                                <span class="comment-form-reply-user" id="replying-to-text"></span>
                                <button type="button" class="comment-form-reply-remove"
                                    onclick="replyRemove();">Remove</button>
                            </div>
                            <div class="comment-form-div">
                                <input type="hidden" name="snippet" value="<?= htmlspecialchars($name) ?>">
                                <input type="hidden" name="childOf" id="child-of-input" value="-1">
                                <textarea spellcheck="false" placeholder="Add a comment..." name="content"
                                    class="comments-form-input" id="comment-area"></textarea>
                                <button type="submit" class="comments-form-button" onclick="postComment();">Send</button>
                            </div>
                        </form>
                        <div class="comments-output"></div>
                        <?php
                        $co_q1 = "SELECT * FROM comments WHERE post_name = $1 ORDER BY likes DESC, created_at DESC";
                        $co_result = pg_query_params($dbcon, $co_q1, array($name));

                        $comments = [];
                        $childrenMap = [];

                        while ($co_tuple = pg_fetch_assoc($co_result)) {
                            $id = $co_tuple['id'];

                            $comments[$id] = $co_tuple;
                            if ($co_tuple['child_of'] === null) {
                                $childrenMap[null][] = $id;
                            } else {
                                $childrenMap[$co_tuple['child_of']][] = $id;
                            }
                        }

                        function renderComment($id, $comments, $childrenMap, $dbcon, $depth = 0, $parentCreator = null, $parentId = null)
                        {
                            $co_tuple = $comments[$id];
                            $commentId = $co_tuple['id'];
                            $c_avatar = "https://robohash.org/" . urlencode($co_tuple['creator']) . ".png?set=set1&bgset=bg1";
                            ?>
                            <div class="comment <?= $depth > 0 ? 'reply' : '' ?> <?= $depth == 1 ? 'reply-to-parent' : '' ?> <?= $depth > 1 ? 'reply-to-reply' : '' ?>"
                                id="comment-<?= $commentId ?>">
                                <div class="comment-top-div">
                                    <div class="comment-top-left">
                                        <div class="comment-username-container"
                                            onclick="location.href = 'account.php?username=<?= $co_tuple['creator'] ?>'">
                                            <div class="avatar-div">
                                                <img src="<?= $c_avatar ?>" alt="Avatar" class="comment-avatar-img">
                                            </div>
                                            <span class="comment-username"><?= $co_tuple['creator'] ?></span>
                                        </div>
                                        <?php if ($parentCreator != null) { ?>
                                            <div class="comment-reply-container" onclick="highlightReply(<?= $parentId ?>)">
                                                <span class="comment-info-text">Replying to</span>
                                                <span class="comment-text"><?= $parentCreator ?></span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <span class="comment-info-text"
                                        id="comment-date-top"><?php echo timeAgo($co_tuple['created_at']); ?></span>
                                </div>
                                <div class="comment-rest-div">
                                    <div class="comment-content-div">
                                        <span class="comment-content"><?= htmlspecialchars($co_tuple['content']) ?></span>
                                    </div>
                                    <div class="comment-actions-div">
                                        <div class="comment-actions-left">
                                            <div class="comment-likes">
                                                <label class="comment-likes-div">
                                                    <?php
                                                    if (isset($_SESSION['username'])) {
                                                        $q1 = "SELECT * from users where username = $1";
                                                        $result = pg_query_params($dbcon, $q1, array($_SESSION['username']));
                                                        if ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
                                                            $likedComments = explode(',', trim($tuple['likedcomments'], '{}'));
                                                            $checkedAttribute = in_array($commentId, $likedComments) ? 'checked' : '';
                                                            echo "<input $checkedAttribute type='checkbox' data-snippet='$commentId' class='comment-like-checkbox'>";
                                                        }
                                                    } else {
                                                        echo "<input type='checkbox' onclick='event.preventDefault(); event.stopPropagation(); openLogin(event);'>";
                                                    }
                                                    ?>
                                                    <div class='comment-likes-checkmark'>
                                                        <svg viewBox='0 0 256 256'>
                                                            <path
                                                                d='M224.6,51.9a59.5,59.5,0,0,0-43-19.9,60.5,60.5,0,0,0-44,17.6L128,59.1l-7.5-7.4C97.2,28.3,59.2,26.3,35.9,47.4a59.9,59.9,0,0,0-2.3,87l83.1,83.1a15.9,15.9,0,0,0,22.6,0l81-81C243.7,113.2,245.6,75.2,224.6,51.9Z'
                                                                stroke-width='20px' fill='none'></path>
                                                        </svg>
                                                    </div>
                                                </label>
                                                <p class="comment-likes-text" id="comment-likes-text-<?= $commentId ?>">
                                                    <?= htmlspecialchars($co_tuple['likes']) ?>
                                                </p>
                                            </div>
                                            <button class="comment-action-button"
                                                onclick="replyToComment('<?= $commentId ?>', '<?= $co_tuple['creator'] ?>')">Reply</button>
                                            <?php if (isset($_SESSION['username']) && $co_tuple['creator'] == $_SESSION['username']) { ?>
                                                <button type="button" class="comment-action-button"
                                                    onclick="openConfirmDeleteComment(event, <?= $commentId ?>);">Delete</button>
                                            <?php } ?>
                                        </div>
                                        <div class="comment-actions-right">
                                            <span class="comment-info-text"
                                                id="comment-date-bottom"><?php echo timeAgo($co_tuple['created_at']); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php

                            if (isset($childrenMap[$id])) {
                                foreach ($childrenMap[$id] as $childId) {
                                    renderComment($childId, $comments, $childrenMap, $dbcon, ((int) $depth + 1), $co_tuple['creator'], $commentId);
                                }
                            }
                        }

                        echo '<p class="comments-search-results">' . count($comments) . ' comments</p>';
                        echo '<div class="comments-search-output">';
                        if (!empty($childrenMap[null])) {
                            foreach ($childrenMap[null] as $rootId) {
                                renderComment($rootId, $comments, $childrenMap, $dbcon);
                            }
                        }
                        echo '</div>';
                        ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php include 'footer-code.php'; ?> <!--FOOTER-->
    </div>
</body>
<script src="scripts/snippet.js"></script>
<script src="scripts/login.js"></script>
<script src="scripts/signup.js"></script>
<?php if (isset($_GET['info'])) { ?>
    <script>
        openInfo(null, "<?= $_GET['info'] ?>");
        const url = new URL(window.location);
        url.searchParams.delete('info');
        window.history.replaceState({}, '', url);
    </script>
<?php } ?>
<script>
    window.addEventListener('hashchange', () => {
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                history.replaceState(null, '', window.location.pathname + window.location.search);
            });
        });
    });
</script>

</html>