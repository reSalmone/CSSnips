<?php
session_start();

if (!isset($_SESSION['username']) && $_SESSION['username'] === "admin") {
    die('You are not authorized to do this');
}

$dir = __DIR__ . "/snippets";

$dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or die('Error connecting to databse');
if ($dbcon) {
    $files = array_diff(scandir($dir), array('.', '..'));

    $q = "SELECT file_location FROM snips";
    $result = pg_query($dbcon, $q);

    $dbFiles = [];
    while ($row = pg_fetch_assoc($result)) {
        $dbFiles[] = $row['file_location'];
    }

    $deletedFiles = [];
    foreach ($files as $file) {
        if (!in_array($file, $dbFiles)) {
            $filePath = $dir . "/" . $file;
            if (is_file($filePath)) {
                unlink($filePath);
                $deletedFiles[] = $file;
            }
        }
    }
}
?>