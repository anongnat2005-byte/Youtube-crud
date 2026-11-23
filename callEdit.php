<?php
// callEdit.php : รับข้อมูลจากฟอร์ม edit.php แล้วเรียก API update.php (PATCH)
include 'db.php';

$body = array(
    "id"          => $_POST['id'],
    "title"       => $_POST['title'],
    "channel"     => $_POST['channel'],
    "youtube_url" => $_POST['youtube_url'],
    "description" => $_POST['description'],
);

list($code, $result) = callApi("PATCH", $apiUrl . "/update.php", $body);

$message = isset($result['status']) ? $result['status'] : "Cannot connect to API";
$next = ($code == 200) ? "window.location.href='index.php';" : "history.back();";
echo "<script>alert(" . json_encode($message) . "); " . $next . "</script>";
?>