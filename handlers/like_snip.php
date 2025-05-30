<?php
session_start();
header('Content-Type: application/json');


if (!isset($_SESSION['username']) || !isset($_POST['snippet'])) {
    echo json_encode(['success' => false, 'error' => 'Potrei impazzire']);
    exit;
}


$user = $_SESSION['username'];
$snip_id = $_POST['snippet'];

$dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1");
if ($dbcon != -1) {

    // Verifica se l'id utente è valido
    $q_user = "SELECT id FROM users WHERE username = $1";
    $res_user = pg_query_params($dbcon, $q_user, array($user));
    $user_data = pg_fetch_assoc($res_user);
    $user_id = $user_data['id'] ?? null;
    if (!$user_id) {
        echo json_encode(['success' => false, 'error' => 'Errore nell ID utente']);
        exit;
    }

    // Verifica se ha già messo like
    $q_check = "SELECT 1 FROM user_snip_likes WHERE user_id = $1 AND snip_id = $2";
    $res_check = pg_query_params($dbcon, $q_check, array($user_id, $snip_id));
    if (pg_num_rows($res_check) > 0) {
        $q_insert = "DELETE FROM user_snip_likes WHERE user_id = $1 AND snip_id = $2;";
        $res_insert = pg_query_params($dbcon, $q_insert, array($user_id, $snip_id));
        if (!$res_insert) {
            echo json_encode(['success' => false, 'error' => 'Errore nel eliminare il like in user_snip_likes']);
            exit;
        } 
    }else{
        // Inserisci like
        $q_insert = "INSERT INTO user_snip_likes (user_id, snip_id) VALUES ($1, $2)";
        $res_insert = pg_query_params($dbcon, $q_insert, array($user_id, $snip_id));
        if (!$res_insert) {
            echo json_encode(['success' => false, 'error' => 'Errore nel inserire il like in user_snip_likes']);
            exit;
        } 
    }
    $q_snip = "SELECT * FROM snips_with_likes WHERE id = $1";
    $res_snip = pg_query_params($dbcon, $q_snip,array($snip_id));
    $tuple_snip = (pg_fetch_array($res_snip, null, PGSQL_ASSOC));
    $snip_likes=$tuple_snip['challenge_likes'];

    // Stampa in formato JSON parte dell'array contenente le ricette
        echo json_encode(['success' => true, 'value' => $snip_likes]);


}
?>
