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

$name = null;
$challengeName = null;

$foundEdit = false;
$foundClone = false;
$foundVariation = false;
$foundDraft = false;
$foundChallenge = false;
$foundSessionVariation = false;
if (isset($_GET['edit'])) {
    $name = $_GET['edit'];
    $foundEdit = true;
} else if (isset($_GET['clone'])) {
    $name = $_GET['clone'];
    $foundClone = true;
} else if (isset($_GET['draft'])) {
    $name = $_GET['draft'];
    $foundDraft = true;
}
if (isset($_GET['challenge'])) {
    $challengeName = $_GET['challenge'];
    $foundChallenge = true;
} else if (isset($_COOKIE['challengeCookie'])) {
    $challengeName = $_COOKIE['challengeCookie'];
    $foundChallenge = true;
    setcookie('challengeCookie', '', 0, '/');
} else if (isset($_GET['variation'])) {
    $name = $_GET['variation'];
    $foundVariation = true;
} else if (isset($_COOKIE['variationCookie'])) {
    $name = $_COOKIE['variationCookie'];
    $foundVariation = true;
    setcookie('variationCookie', '', 0, '/');
}

list($html, $css, $js) = null;
$creator = null;
$type = null;
$description = null;
$tags = null;

$permission = false;
$found = false;
$foundC = false;

if ($foundChallenge) {
    $dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or -1;
    if ($dbcon != -1) {
        $q1 = "SELECT * from challenges where name = $1";
        $result = pg_query_params($dbcon, $q1, array($challengeName));
        if ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $q2 = "SELECT * from challenges where name = $1 and date_end >= CURRENT_DATE";
            $result2 = pg_query_params($dbcon, $q2, array($challengeName));
            if ($tuple2 = pg_fetch_array($result2, null, PGSQL_ASSOC)) {
                $foundC = true;
            } else {
                //dije challenge terminata
                header('Location: challenge_selected.php?name=' . $tuple['name']);
            }
        } else {
            //dije challenge non trovate
            header('Location: challenges.php');
        }
    }
}

if ($name != '') {
    $filePath = __DIR__ . "\\snippets\\" . basename($name);
    if ($foundDraft) {
        $filePath = __DIR__ . "\\drafts\\" . basename($name);
    }
    $dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1");
    if (file_exists(filename: $filePath)) {
        $content = file_get_contents($filePath);
        list($html, $css, $js) = splitFileContent($content);

        if ($dbcon) {
            $q1 = "SELECT * from snips where file_location = $1";
            if ($foundDraft) {
                $q1 = "SELECT * from drafts where file_location = $1";
            }
            $result = pg_query_params($dbcon, $q1, array($name));
            if ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
                $found = true;
                $creator = $tuple['creator'];
                if ($foundDraft) {
                    $type = $tuple['type'];
                } else {
                    $type = $tuple['element_type'];
                }
                $description = $tuple['description'];
                $tags = ($t = trim($tuple['tags'], '{}')) === '' ? [] : explode(',', $t);

                if ($foundDraft && isset($tuple['variation_of'])) {
                    $foundVariation = true;
                }
                if ($foundDraft && isset($tuple['challenge_of'])) {
                    $foundChallenge = true;
                    $foundC = true;
                    $challengeName = $tuple['challenge_of'];
                }

                if ($foundEdit && (!isset($_SESSION['username']) || ($foundEdit && isset($_SESSION['username']) && $creator != $_SESSION['username']))) {
                    //maybe make a system that EVERY page can have it's own errors sent to it and here send back a permission error
                    header('Location: snippet.php?name=' . $name);
                    exit;
                }
            }
        }
    }
}
if ($name != '' && !$found) {
    header('Location: creator.php');
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSSnip - Create</title>
    <link rel="icon" href="assets/images/icon.png">
    <link rel="stylesheet" href="creator.css">
    <link rel="stylesheet" href="assets/NoveoSans-Book/style.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="login-signup.css">
    <link rel="stylesheet" href="checkbox.css"> <!-- Checkbox figa nel login -->
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="snippet-loader.css">
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
    if ($foundChallenge) { //not sure why I'm doing it to challenges aswell
        echo "<script>removeQueryParam('challenge')</script>";
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
                <?php if ($foundVariation && $found) {
                    echo '<div class="post-variation-container">';
                    echo '<span class="post-variation-subtext">Posting as variation of <span class="post-variation-text">' . $type . '</span> by</span>';
                    echo '<div class="post-variation-user" onclick="location.href = \'account.php?username=' . $creator . '\'">';
                    echo '<div class="post-variation-pfp"></div>';
                    echo '<span class="post-variation-text">' . $creator . '</span>';
                    echo '</div>';
                    echo '</div>';
                } else if ($foundChallenge && $foundC) {
                    echo '<div class="post-variation-container">';
                    echo '<span class="post-variation-subtext">Posting snippet for challenge <span class="post-variation-text">' . $challengeName . '</span></span>';
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
            <?php if ($foundDraft) { ?>
                <span class="post-info-delete-draft">This draft will be deleted automatically when posting the
                    snipped</span>
            <?php } ?>
            <div class="post-actions">
                <button class="post-action-button" onclick="closePost();">Cancel</button>
                <button class="post-action-button" onclick="postSnippet('<?php if ($foundDraft) {
                    echo $name;
                } ?>');">Post</button>
            </div>
        </div>
    </div>

    <div class="center-div load-center-div" id="load-center-div">
        <div class="load-page">
            <div class="load-title-container">
                <span class="load-title">Select action</span>
                <span class="load-subtitle">Chose whether you want to load a draft or create a new snippet from
                    scratch</span>
            </div>
            <div class="load-actions">
                <button class="load-action-button" onclick="openDrafts(event);">Load a draft</button>
                <div class="load-action-separator">
                    <hr class="load-action-hr">
                    <span class="load-action-or">or</span>
                    <hr class="load-action-hr">
                </div>
                <button class="load-action-button" onclick="closeLoad();">Create from scratch</button>
            </div>
        </div>
    </div>
    <div class="center-div drafts-center-div" id="drafts-center-div">
        <div class="drafts-page">
            <div class="drafts-title-container">
                <span class="drafts-title">Select a draft</span>
            </div>
            <?php
            if (isset($_SESSION['username'])) {
                $dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or -1;
                if ($dbcon != -1) { //se la connessione è correttamente stabilita
            
                    $q1 = "SELECT * FROM drafts WHERE creator = $1 ORDER BY created_at DESC";
                    $results = pg_query_params($dbcon, $q1, array($_SESSION['username']));
                    if (pg_num_rows($results) > 0) {
                        echo '<div class="drafts-search-output">';
                        while ($tuple = pg_fetch_assoc($results)) {
                            $id = (int) $tuple['id'];
                            $draftName = $tuple['file_location'];
                            ?>
                            <div class="drafts-output-snip" data-snippet-id="<?= $id ?>" id="drafts-output-snip-<?= $id ?>">
                                <div class="drafts-output-loader" id="drafts-output-loader-<?= $id ?>"></div>
                                <iframe id="drafts-output-snip-frame-<?= $id ?>" class="drafts-output-preview">
                                </iframe>
                                <div class="drafts-output-actions">
                                    <?php if (!$foundDraft || $tuple['file_location'] != $name) { ?>
                                        <div class="drafts-output-snip-opener"
                                            onclick="location.href='creator.php?draft=<?= urlencode($tuple['file_location']) ?>';">Select
                                            draft</div>
                                    <?php } else { ?>
                                        <div class="drafts-output-snip-opener">Currently selected</div>
                                    <?php } ?>
                                    <button class="drafts-output-delete" onclick="deleteDraft('<?= $draftName ?>')">
                                        <div class='drafts-output-delete-svg'>
                                            <svg viewBox='0 0 256 256'>
                                                <path
                                                    d='M197.5 41.6H151.2L148.6 36.5C146.6 32.4 142.6 30 138.2 30H94.5C90.2 30 86.1 32.4 81.4 41.6H35 M46.6 81.4 54.3 204.6C54.8 213.7 62.5 220.9 71.6 220.9H161C170.1 220.9 177.8 213.8 178.3 204.6L186.1 81.4Z'
                                                    stroke-width='20px' stroke='#FFF' fill='none'></path>
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </div>
                            <?php
                        }
                        echo '</div>';
                    } else {
                        echo '<p class="drafts-error">No drafts found</p>';
                    }
                } else {
                    echo '<p class="drafts-error">Error connecting to databse</p>';
                }
            }
            ?>
            <div class="drafts-actions">
                <button class="drafts-action-button" onclick="closeDrafts();">Cancel</button>
            </div>
        </div>
    </div>

    <div class="center-div confirm-delete-center-div" id="confirm-delete-center-div">
        <div class="confirm-delete-page">
            <div class="confirm-delete-title-container">
                <span class="confirm-delete-title">Confirm action</span>
                <span class="confirm-delete-subtitle">This action is irreversable - this draft will be deleted and can
                    NOT be restored</span>
            </div>
            <div class="confirm-delete-actions">
                <button class="confirm-delete-action-button confirm-delete"
                    onclick="deleteDraft('<?= $name ?>')">Delete</button>
                <button class="confirm-delete-action-button" onclick="closeConfirmDelete();">Cancel</button>
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
        onclick="closeLogin(); closeSignup(); closePost(); closeLoad(); closeDrafts(); closeConfirmDelete(); closeInfo();">
        <div class="snippet-page">
            <?php if (($foundEdit && $found)) { ?>
                <div class="variation-container">
                    <span class="variation-subtext">Editing <a href="snippet.php?name=<?php echo $name ?>"
                            class="variation-text"><?php echo $name ?></a></span>
                    <span id="editing-name" hidden><?php echo $name ?></span>
                    <button class="variation-remove" onclick="location = 'snippet.php?name=<?php echo $name ?>'">
                        <div class="variation-remove-checkmark">
                            <svg viewBox="0 0 256 256">
                                <path d="M51.2 51.2 204.8 204.8M204.8 51.2 51.2 204.8" stroke-width="20px" fill="none"
                                    stroke-linecap="round">
                                </path>
                            </svg>
                        </div>
                    </button>
                </div>
            <?php } else if ($foundVariation && $found) { ?>
                    <div class="variation-container">
                        <span class="variation-subtext">Creating a variation of <a href="snippet.php?name=<?php echo $name ?>"
                                class="variation-text"><?php echo $type ?></a> by</span>
                        <div class="variation-user" onclick="location.href = 'account.php?username=<?= $creator ?>'">
                            <div class="variation-pfp"></div>
                            <span class="variation-text"><?php echo $creator ?></span>
                        </div>
                        <span id="variation-name" hidden><?php echo $name ?></span>
                        <?php
                        $locationAfter = $foundDraft ? "creator.php?clone=$name" : "snippet.php?name=$name";
                        ?>
                        <button class="variation-remove" onclick="location = '<?= $locationAfter ?>'">
                            <div class="variation-remove-checkmark">
                                <svg viewBox="0 0 256 256">
                                    <path d="M51.2 51.2 204.8 204.8M204.8 51.2 51.2 204.8" stroke-width="20px" fill="none"
                                        stroke-linecap="round">
                                    </path>
                                </svg>
                            </div>
                        </button>
                    </div>
            <?php } else if ($foundChallenge && $foundC) { ?>
                        <div class="variation-container">
                            <span class="variation-subtext">Submitting snippet for challenge '<a
                                    href="challenge_selected.php?name=<?php echo urlencode($challengeName) ?>"
                                    class="variation-text"><?php echo $challengeName ?></a>'</span>
                            <span id="challenge-name" hidden><?php echo $challengeName ?></span>
                            <button class="variation-remove"
                                onclick="location = 'challenge_selected.php?name=<?php echo urlencode($challengeName) ?>'">
                                <div class="variation-remove-checkmark">
                                    <svg viewBox="0 0 256 256">
                                        <path d="M51.2 51.2 204.8 204.8M204.8 51.2 51.2 204.8" stroke-width="20px" fill="none"
                                            stroke-linecap="round">
                                        </path>
                                    </svg>
                                </div>
                            </button>
                        </div>
            <?php } ?>
            <div class="snippet-action-bar">
                <div class="left-action-buttons">
                    <div class="type-dropdown-div">
                        <button class="action-button" id="current-type">Type: button</button>
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
                    <?php if ($foundEdit && $found) { ?>
                        <!--EDIT BUTTONS-->
                        <button class="action-button" onclick="saveChanges();">
                            <div class='action-svg'>
                                <svg viewBox='0 0 256 256'>
                                    <path
                                        d='M31.8 86.92C31.8 74.2 31.8 68.9 33.92 63.6 36.04 60.42 39.22 57.24 42.4 55.12 47.7 53 53 53 65.72 53H101.76C107.06 53 110.24 53 112.36 53 114.48 54.06 116.6 54.06 118.72 55.12 120.84 57.24 121.9 58.3 126.14 62.54L127.2 63.6C131.44 67.84 132.5 68.9 134.62 71.02 136.74 72.08 138.86 72.08 140.98 73.14 143.1 74.2 146.28 74.2 151.58 74.2H188.68C200.34 74.2 205.64 74.2 210.94 76.32 214.12 78.44 217.3 81.62 219.42 84.8 222.6 90.1 222.6 95.4 222.6 108.12V167.48C222.6 179.14 222.6 184.44 219.42 189.74 217.3 192.92 214.12 196.1 210.94 198.22 205.64 201.4 200.34 201.4 188.68 201.4H65.72C53 201.4 47.7 201.4 42.4 198.22 39.22 196.1 36.04 192.92 33.92 189.74 31.8 184.44 31.8 179.14 31.8 167.48V86.92Z'
                                        stroke-width='20px' fill='none' stroke-linecap='round'></path>
                                </svg>
                            </div>
                            <span>Save changes</span>
                        </button>
                    <?php } else { ?>
                        <!--NORMAL BUTTONS-->
                        <button class="action-button" id="action-important" onclick="location.href = 'creator.php'">
                            <div class='action-svg'>
                                <svg viewBox='0 0 256 256'>
                                    <path
                                        d='M201.4 143.1C201.4 187.0063 165.8 222.6 121.9 222.6 77.9 222.6 42.4 187.0 42.4 143.1 42.4 99.1 77.9 63.6 121.9 63.6H212M212 63.6 180.2 31.8M212 63.6 180.2 95.4'
                                        stroke-width='20px' fill='none' stroke-linecap='round'></path>
                                </svg>
                            </div>
                            <span>Reset</span>
                        </button>
                        <?php
                        $actionSaveName = $foundDraft ? $name : "";
                        $actionSave = isset($_SESSION['username']) ? ("saveDraft('" . $actionSaveName . "');") : "openLogin(event);";
                        $actionPost = isset($_SESSION['username']) ? "openPost(event);" : "openLogin(event);";
                        $actionDrafts = isset($_SESSION['username']) ? "openDrafts(event);" : "openLogin(event);";
                        ?>
                        <?php if ($foundDraft) { ?>
                            <button class="action-button" type="button" onclick="openConfirmDelete(event);">
                                <div class='action-svg'>
                                    <svg viewBox='0 0 256 256'>
                                        <path
                                            d='M197.5 41.6H151.2L148.6 36.5C146.6 32.4 142.6 30 138.2 30H94.5C90.2 30 86.1 32.4 81.4 41.6H35 M46.6 81.4 54.3 204.6C54.8 213.7 62.5 220.9 71.6 220.9H161C170.1 220.9 177.8 213.8 178.3 204.6L186.1 81.4Z'
                                            stroke-width='20px' fill='none' stroke-linecap='round'></path>
                                    </svg>
                                </div>
                                <span>Delete draft</span>
                            </button>
                        <?php } ?>
                        <button class="action-button" type="button" onclick="<?php echo $actionDrafts ?>">
                            <div class='action-svg'>
                                <svg viewBox='0 0 256 256'>
                                    <path
                                        d='M63.6 222.6H190.8M127.2 31.8V180.2M127.2 180.2 180.2 127.2M127.2 180.2 74.2 127.2'
                                        stroke-width='20px' fill='none' stroke-linecap='round'></path>
                                </svg>
                            </div>
                            <span>Load draft</span>
                        </button>
                        <button class="action-button" type="button" onclick="<?php echo $actionSave ?>">
                            <div class='action-svg'>
                                <svg viewBox='0 0 256 256'>
                                    <path
                                        d='M31.8 86.92C31.8 74.2 31.8 68.9 33.92 63.6 36.04 60.42 39.22 57.24 42.4 55.12 47.7 53 53 53 65.72 53H101.76C107.06 53 110.24 53 112.36 53 114.48 54.06 116.6 54.06 118.72 55.12 120.84 57.24 121.9 58.3 126.14 62.54L127.2 63.6C131.44 67.84 132.5 68.9 134.62 71.02 136.74 72.08 138.86 72.08 140.98 73.14 143.1 74.2 146.28 74.2 151.58 74.2H188.68C200.34 74.2 205.64 74.2 210.94 76.32 214.12 78.44 217.3 81.62 219.42 84.8 222.6 90.1 222.6 95.4 222.6 108.12V167.48C222.6 179.14 222.6 184.44 219.42 189.74 217.3 192.92 214.12 196.1 210.94 198.22 205.64 201.4 200.34 201.4 188.68 201.4H65.72C53 201.4 47.7 201.4 42.4 198.22 39.22 196.1 36.04 192.92 33.92 189.74 31.8 184.44 31.8 179.14 31.8 167.48V86.92Z'
                                        stroke-width='20px' fill='none' stroke-linecap='round'></path>
                                </svg>
                            </div>
                            <?php if ($foundDraft) {
                                echo '<span>Update draft</span>';
                            } else {
                                echo '<span>Save draft</span>';
                            } ?>
                        </button>
                        <button class="action-button" type="button" onclick="<?php echo $actionPost ?>">
                            <div class='action-svg'>
                                <svg viewBox='0 0 256 256'>
                                    <path
                                        d='M109.18 144.16 213.06 40.28M111.3 149.46 135.68 196.1C140.98 207.76 144.16 213.06 147.34 215.18 150.52 216.24 153.7 216.24 156.88 214.12 160.06 212 162.18 206.7 166.42 193.98L210.94 63.6C214.12 53 216.24 47.7 215.18 44.52 214.12 41.34 212 39.22 208.82 38.16 205.64 37.1 200.34 39.22 189.74 42.4L59.36 86.92C46.64 91.16 41.34 93.28 39.22 96.46 37.1 99.64 37.1 102.82 38.16 106 40.28 109.18 45.58 112.36 57.24 117.66L103.88 142.04C106 143.1 107.06 143.1 108.12 144.16 108.12 144.16 109.18 145.22 109.18 145.22 110.24 146.28 110.24 147.34 111.3 149.46Z'
                                        stroke-width='20px' fill='none' stroke-linecap='round'></path>
                                </svg>
                            </div>
                            <span>Post snippet</span>
                        </button>
                    <?php } ?>
                </div>
            </div>
            <div class="snippet-container">
                <iframe id="output" class="output-container">

                </iframe>
                <div class="frame-actions">
                    <span class="frame-actions-color" id="frame-actions-color">#112343</span>
                    <label class="frame-actions-switch" for="switch">
                        <input id="switch" type="checkbox" class="circle" onchange="updateSnippetThemeS(this);">
                        <svg viewBox='0 0 256 256' class="moon svg">
                            <path
                                d='M127.2 233.2C185.7 233.2 233.2 185.7 233.2 127.2 233.2 122.2 225.8 121.4 223.3 125.6 211.2 145.6 189.3 159 164.3 159 126.2 159 95.4 128.1 95.4 90.1 95.4 65.0 108.7 43.1 128.7 31.0 132.9 28.5 132.1 21.2 127.2 21.2 68.6 21.2 21.2 68.6 21.2 127.2 21.2 185.7 68.6 233.2 127.2 233.2Z'
                                stroke-width='20px' stroke-linecap='round'></path>
                        </svg>
                        <svg viewBox='0 0 256 256' class="sun svg">
                            <path
                                d='M190.8 127.2C190.8 162.3 162.3 190.8 127.2 190.8 92.0 190.8 63.6 162.3 63.6 127.2 63.6 92.0 92.0 63.6 127.2 63.6 162.3 63.6 190.8 92.0 190.8 127.2ZM127.2 13.2C131.5 13.2 135.1 16.8 135.1 21.2V31.8C135.1 36.1 131.5 39.7 127.2 39.7 122.8 39.7 119.2 36.1 119.2 31.8V21.2C119.2 16.8 122.8 13.2 127.2 13.2ZM13.2 127.2C13.2 122.8 16.8 119.2 21.2 119.2H31.8C36.1 119.2 39.7 122.8 39.7 127.2 39.7 131.5 36.1 135.1 31.8 135.1H21.2C16.8 135.1 13.2 131.5 13.2 127.2ZM214.6 127.2C214.6 122.8 218.2 119.2 222.6 119.2H233.2C237.5 119.2 241.1 122.8 241.1 127.2 241.1 131.5 237.5 135.1 233.2 135.1H222.6C218.2 135.1 214.6 131.5 214.6 127.2ZM127.2 214.6C131.5 214.6 135.1 218.2 135.1 222.6V233.2C135.1 237.5 131.5 241.1 127.2 241.1 122.8 241.1 119.2 237.5 119.2 233.2V222.6C119.2 218.2 122.8 214.6 127.2 214.6ZM46.6 46.6C49.7 43.5 54.7 43.5 57.8 46.6L62.0 50.7C65.1 53.8 65.1 58.9 62.0 62.0 58.9 65.1 53.8 65.1 50.7 62.0L46.6 57.8C43.5 54.7 43.5 49.7 46.6 46.6ZM207.7 46.6C210.8 49.7 210.8 54.7 207.7 57.8L203.6 62.0C200.5 65.1 195.4 65.1 192.3 62.0 189.2 58.9 189.2 53.8 192.3 50.7L196.5 46.6C199.6 43.5 204.6 43.5 207.7 46.6ZM192.3 192.3C195.4 189.2 200.5 189.2 203.6 192.3L207.7 196.5C210.8 199.6 210.8 204.6 207.7 207.7 204.6 210.8 199.6 210.8 196.5 207.7L192.3 203.6C189.2 200.5 189.2 195.4 192.3 192.3ZM62.0 192.3C65.1 195.4 65.1 200.5 62.0 203.6L57.8 207.7C54.7 210.8 49.7 210.8 46.6 207.7 43.5 204.6 43.5 199.6 46.6 196.5L50.7 192.3C53.8 189.2 58.9 189.2 62.0 192.3Z'
                                stroke-width='20px' stroke-linecap='round'></path>
                        </svg>
                    </label>
                    <div class="frame-actions-select-wrapper">
                        <input type="color" id="frame-actions-select-color" oninput="updateSnippetThemeC(this)">
                        <div class="frame-actions-select" onclick="openColorPicker();"></div>
                    </div>
                </div>
                <div class="code-container">
                    <div class="code-buttons">
                        <div class="lang-buttons-container">
                            <button class="lang-button" id="html-button" onclick="switchLang('html')">Html</button>
                            <button class="lang-button" id="css-button" onclick="switchLang('css')">Css</button>
                            <button class="lang-button" id="js-button" onclick="switchLang('js')">Js</button>
                        </div>
                    </div>
                    <div class="code-div" id="code-div">
                        <div class="code-actions">
                            <svg viewBox='0 0 256 256' class="code-actions-svg">
                                <path d='M42.4 63.6H212M42.4 127.2H212M42.4 190.8H212' stroke-width='20px' fill='none'
                                    stroke-linecap='round'></path>
                            </svg>
                            <button class="code-actions-button" onclick="copyToClipboard();">
                                <span id="copy-span">Copy</span>
                            </button>
                            <button class="code-actions-button" onclick="formatArea(areas[currentLang], '    ');">
                                <span id="copy-span">Format</span>
                            </button>
                        </div>
                        <div class="line-numbers" id="line-numbers">
                            <div>1</div>
                        </div>
                        <textarea class="input-area" id="html-area" wrap="off"
                            oninput="updateLines(this); displayCode(); unsave();" onscroll="syncScroll(this);"
                            onkeydown="insertTab(event, this);" spellcheck="false" placeholder="Html code"><?php
                            if ($found) {
                                echo htmlspecialchars($html);
                            }
                            ?></textarea>
                        <textarea class="input-area" id="css-area" wrap="off"
                            oninput="updateLines(this); displayCode(); unsave();" onscroll="syncScroll(this);"
                            onkeydown="insertTab(event, this); insertBrackets(event, this);" spellcheck="false"
                            placeholder="Css code"><?php
                            if ($found) {
                                echo htmlspecialchars($css);
                            }
                            ?></textarea>
                        <textarea class="input-area" id="js-area" wrap="off"
                            oninput="updateLines(this); displayCode(); unsave();" onscroll="syncScroll(this);"
                            onkeydown="insertTab(event, this); insertBrackets(event, this);" spellcheck="false"
                            placeholder="JavaScript code"><?php
                            if ($found) {
                                echo htmlspecialchars($js);
                            }
                            ?></textarea>
                    </div>
                    <div class="code-unsaved" id="code-unsaved">Unsaved code</div>
                </div>
            </div>
            <div class="info-container">
                <div class="description-container">
                    <span class="description-title">Add a description</span>
                    <?php if (($found && $foundEdit || $found && $foundDraft)) {
                        echo '<textarea class="description-area" id="description-area" oninput="saved = false;" spellcheck="false">' . $description . '</textarea>';
                    } else {
                        echo '<textarea class="description-area" id="description-area" spellcheck="false"
                        oninput="unsave();">This is my new element</textarea>';
                    } ?>
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
                    <div id="tags-list"></div>
                </div>
            </div>
        </div>
        <?php include 'footer-code.php'; ?> <!--FOOTER-->
    </div>
</body>
<script src="assets/scripts/creator.js"></script>
<script src="assets/scripts/login.js"></script>
<script src="assets/scripts/signup.js"></script>
<?php if (($found && $foundEdit || $found && $foundDraft)) { ?>
    <script>
        tags = <?php echo json_encode($tags); ?>;
        renderTags();
    </script>
<?php }
if (isset($_SESSION['username']) && !$foundEdit && !$foundClone && !$foundVariation && !$foundDraft && !$foundChallenge) {
    echo '<script>if (!loadedFromStorage) { openLoad(); }</script>';
}
?>
<?php if (isset($_GET['info'])) { ?>
    <script>
        openInfo(null, "<?= $_GET['info'] ?>");
        removeQueryParam("info");
    </script>
<?php } ?>

</html>