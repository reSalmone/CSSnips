<?php
session_start();

if (!isset($_SESSION['username'])) {
    deleteError('You need to be logged to do this');
}

$creator = $_SESSION['username'];
$id = $_GET['id'];


    $dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or deleteError('Error connecting to databse');
    if ($dbcon) {
        $q1 = "SELECT * from comments where id = $1 and creator = $2";

        $resultFileName = pg_query_params($dbcon, $q1, array($id, $creator));
        $tupleFileName = pg_fetch_array($resultFileName, null, PGSQL_ASSOC);
        if ($tupleFileName) {

            $q2 = "WITH RECURSIVE to_delete AS (
                    SELECT id
                    FROM comments
                    WHERE id = $1

                    UNION ALL

                    SELECT c.id
                    FROM comments AS c
                    JOIN to_delete AS td
                    ON c.child_of = td.id
                )
                DELETE FROM comments
                WHERE id IN (SELECT id FROM to_delete);";
            $data = pg_query_params($dbcon, $q2, array($id));
            if (!$data) {
                deleteError('Error during comment removal: ' . pg_last_error($dbcon));
            }

            echo json_encode(['success' => true]);
            exit();
        } else {
            deleteError('Comment not found in database (or your\'re not the owner)');
        }
    }

function deleteError($error)
{
    echo json_encode(['success' => false, 'error' => $error]);
    exit;
}
?>