<?php
header('Content-Type: application/json');
session_start();

$creator = $_SESSION['username'];
$type = $_POST['postType'];
$name = $_POST['postName'];
$description = $_POST['postDescription'];
$variation = null;
if (isset($_POST['postVariation'])) {
    $variation = $_POST['postVariation'];
}
$challenge = null;
if (isset($_POST['postChallenge'])) {
    $challenge = $_POST['postChallenge'];
}

if (!ctype_alpha($type)) {
    postDraftError('Invalid type');
}
if (!preg_match('/^[a-zA-Z0-9_]{3,32}$/', $name)) {
    postDraftError('Invalid name');
}

if ($_POST['postTags'] != '') {
    $tags = json_decode($_POST['postTags'], true);
    $escapedTags = array_map(fn($tags) => '"' . addslashes($tags) . '"', $tags);
    $pgTagsArray = '{' . implode(',', $escapedTags) . '}';
}

if (isset($_FILES['postFile'])) {
    $tmpName = $_FILES['postFile']['tmp_name'];
    $content = file_get_contents($tmpName);

    $dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or postDraftError('Error connecting to databse');
    if ($dbcon) {
        $q1 = "SELECT * from drafts where file_location = $1";

        $resultFileName = pg_query_params($dbcon, $q1, array($name));
        $tupleFileName = pg_fetch_array($resultFileName, null, PGSQL_ASSOC);
        $q2 = "";
        $data = null;
        if ($tupleFileName) { //update
            $q2 = "UPDATE drafts SET creator = $1, description = $2, type = $3, tags = $4 WHERE file_location = $5";
            $data = pg_query_params($dbcon, $q2, array($creator, $description, $type, $pgTagsArray, $name));
        } else { //insert
            $columns = ["creator", "description", "type", "tags", "file_location"];
            $placeholders = ["$1", "$2", "$3", "$4", "$5"];
            $params = [$creator, $description, $type, $pgTagsArray, $name];
            $paramIndex = 6;
            if ($variation !== null) {
                $columns[] = "variation_of";
                $placeholders[] = "\$$paramIndex";
                $params[] = $variation;
                $paramIndex++;
            }

            if ($challenge !== null) {
                $columns[] = "challenge_of";
                $placeholders[] = "\$$paramIndex";
                $params[] = $challenge;
                $paramIndex++;
            }

            $q2 = "INSERT INTO drafts (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $placeholders) . ")";
            $data = pg_query_params($dbcon, $q2, $params);
        }

        if (!$data) {
            postDraftError('Error during post registration');
        }
        $path = __DIR__ . "\\drafts\\" . $name;
        file_put_contents($path, $content);
        echo json_encode(['success' => true]);
        exit();
    }
} else {
    postDraftError('File not found');
}

function postDraftError($error)
{
    echo json_encode(['success' => false, 'error' => $error]);
    exit;
}
?>