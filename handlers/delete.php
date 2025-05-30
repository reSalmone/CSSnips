<?php
session_start();

if (!isset($_SESSION['username'])) {
    deleteError('You need to be logged to do this');
}

$creator = $_SESSION['username'];
$name = $_GET['name'];


if (file_exists(__DIR__ . "\\snippets\\" . $name)) {
    $dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or deleteError('Error connecting to databse');
    if ($dbcon) {
        $q1 = "SELECT * from snips where file_location = $1 and creator = $2";

        $resultFileName = pg_query_params($dbcon, $q1, array($name, $creator));
        $tupleFileName = pg_fetch_array($resultFileName, null, PGSQL_ASSOC);
        if ($tupleFileName) {

            //remove liked
            $qremoveLike = "UPDATE users SET likedsnippets = array_remove(likedsnippets, $1) WHERE $1 = ANY(likedsnippets);;";
            $dataRemoveLike = pg_query_params($dbcon, $qremoveLike, array($name));
            if (!$dataRemoveLike) {
                deleteError('Error during like removal from all users removal: ' . pg_last_error($dbcon));
            }

            //remove saved
            $qremoveSave = "UPDATE users SET savedsnippets = array_remove(savedsnippets, $1) WHERE $1 = ANY(savedsnippets);";
            $dataRemoveSave = pg_query_params($dbcon, $qremoveSave, array($name));
            if (!$dataRemoveSave) {
                deleteError('Error during save removal from all users removal: ' . pg_last_error($dbcon));
            }

            $q2 = "UPDATE drafts SET variation_of = NULL WHERE variation_of = $1";
            $data = pg_query_params($dbcon, $q2, array($name));
            if (!$data) {
                deleteError('Error during update of drafts that are a variation of this snippet: ' . pg_last_error($dbcon));
            }

            $q2 = "UPDATE snips SET variation_of = NULL WHERE variation_of = $1";
            $data = pg_query_params($dbcon, $q2, array($name));
            if (!$data) {
                deleteError('Error during update of snippets that are a variation of this snippet: ' . pg_last_error($dbcon));
            }

            $q2 = "DELETE FROM snips WHERE file_location = $1";
            $data = pg_query_params($dbcon, $q2, array($name));
            if (!$data) {
                deleteError('Error during snippet removal: ' . pg_last_error($dbcon));
            }

            $path = __DIR__ . "\\snippets\\" . $name;
            unlink($path);
            header("Location: explorer.php?info=Snippet deleted");
            exit();
        } else {
            deleteError('File not found in database (or your\'re not the owner)');
        }
    }
} else {
    deleteError('File not found in server files');
}

function deleteError($error)
{
    global $name;
    header("Location: snippet.php?name=" . $name . "&info=" . $error);
    exit;
}
?>