<?php
session_start();

$creator = $_SESSION['username'];
$type = $_POST['postType'];
$name = $_POST['postName'];
$description = $_POST['postDescription'];
$pgTagsArray = null;

if ($_POST['postTags'] != '') {
    $tags = json_decode($_POST['postTags'], true);
    $escapedTags = array_map(fn($tags) => '"' . addslashes($tags) . '"', $tags);
    $pgTagsArray = '{' . implode(',', $escapedTags) . '}';
}

if (isset($_FILES['postFile'])) {
    $tmpName = $_FILES['postFile']['tmp_name'];
    $content = file_get_contents($tmpName);

    $dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or postError('Error connecting to databse');
    if ($dbcon) {
        $q1 = "SELECT * from snips where file_location = $1";

        $resultFileName = pg_query_params($dbcon, $q1, array($name));
        $tupleFileName = pg_fetch_array($resultFileName, null, PGSQL_ASSOC);
        if ($tupleFileName) {
            
            $q2 = "UPDATE snips set description = $1, element_type = $2, tags = $3 WHERE file_location = $4";
            $data = pg_query_params($dbcon, $q2, array($description, $type, $pgTagsArray, $name));

            if (!$data) {
                postError('Error during post registration');
            }

            $path = __DIR__ . "\\snippets\\" . $name;
            file_put_contents($path, $content);
            echo json_encode(['success' => true]);
            exit();
        } else {
            postError('File not found in database');
        }
    }
} else {
    postError('File not found: contact an administrator, how did you even manage to get here');
}

function postError($error)
{
    echo json_encode(['success' => false, 'error' => $error]);
    exit;
}
?>