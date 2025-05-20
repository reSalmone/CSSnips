<?php
header('Content-Type: application/json');

if (empty($_GET['ids'])) {
    echo json_encode(['error' => 'Missing ids']);
    exit;
}

$dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or -1;
if ($dbcon != -1) {
    $idList = array_map('intval', explode(',', $_GET['ids']));
    if (!$idList) {
        echo json_encode(['error' => 'Invalid ids']);
        exit;
    }

    //check if the snippets are in the database
    $placeholders = [];
    foreach ($idList as $i => $_) {
        $placeholders[] = '$' . ($i + 1);
    }

    $sql = 'SELECT id, file_location FROM snips WHERE id IN (' . implode(',', $placeholders) . ')';
    if (isset($_GET['draft'])) {
        $sql = 'SELECT id, file_location FROM drafts WHERE id IN (' . implode(',', $placeholders) . ')';
    }
    $res = pg_query_params($dbcon, $sql, $idList);
    if (!$res) {
        echo json_encode(['error' => 'DB query failed']);
        exit;
    }

    //for each row split the file
    $snippets = [];
    while ($row = pg_fetch_assoc($res)) {
        $id = (int) $row['id'];
        $path = __DIR__ . '/snippets/' . $row['file_location'];
        if (isset($_GET['draft'])) {
            $path = __DIR__ . '/drafts/' . $row['file_location'];
        }
        if (!file_exists($path))
            continue;

        $content = file_get_contents($path);
        list($html, $css, $js) = splitFileContent($content);

        $snippets[] = [
            'id' => $id,
            'html' => $html,
            'css' => $css,
            'js' => $js
        ];
    }

    echo json_encode($snippets, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

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