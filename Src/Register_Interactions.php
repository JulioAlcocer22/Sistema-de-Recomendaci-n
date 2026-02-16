<?php
require 'Utils.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like'], $_POST['user_id'], $_POST['title'])) {
    $like = $_POST['like'];
    $user_id = (int)$_POST['user_id'];

    $talk_id = get_talk_id_by_title(urldecode($_POST['title'])) ?? null;
    if($talk_id === null){
        http_response_code(400);
        echo "Invalid request.";
        return;
    }

    $rating = 5;
    $percentageWatched = 100;

    $logFile = '../Data/Interactions.csv';
    $entry = [$user_id, $talk_id, $rating, $percentageWatched];

    $fp = fopen($logFile, 'a');
    if ($fp) {
        fputcsv($fp, $entry);
        fclose($fp);
        http_response_code(200);
        exit;
    } else {
        http_response_code(500);
        echo "Could not write to log file.";
        exit;
    }
}

http_response_code(400);
echo "Invalid request.";

?>