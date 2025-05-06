<?php
session_start();

$creator = $_SESSION['username'];
$type = $_POST['postType'];
$name = $_POST['postName'];
$description = $_POST['postDescription'];
if ($_POST['postTags'] != '') {
    $tags = json_decode($_POST['postTags'], true);
    $escapedTags = array_map(fn($tags) => '"' . addslashes($tags) . '"', $tags);
    $pgTagsArray = '{' . implode(',', $escapedTags) . '}';
}

if (isset($_FILES['postFile'])) {
    $tmpName = $_FILES['postFile']['tmp_name'];
    $content = file_get_contents($tmpName);

    $dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1");
    if ($dbcon) {
        $q1 = "SELECT * from snips where file_location = $1";

        $resultFileName = pg_query_params($dbcon, $q1, array($name));
        $tupleFileName = pg_fetch_array($resultFileName, null, PGSQL_ASSOC);
        if ($tupleFileName) {
            echo json_encode(['success' => false, 'error' => 'That name already exists']);
            exit;
        }

        $q2 = "insert into snips (creator, description, element_type, tags, file_location) values ($1, $2, $3, $4, $5)";
        $data = pg_query_params($dbcon, $q2, array($creator, $description, $type, $pgTagsArray, $name));
        if (!$data) {
            echo json_encode(['success' => false, 'error' => 'Error during post registration']);
            exit();
        }

        $path = __DIR__ . "\\snippets\\" . $name;
        file_put_contents($path, $content);
        echo json_encode(['success' => true]);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'error' => 'File not found']);
    exit;
}
?>