<?php
// callInsert.php : รับข้อมูลจากฟอร์ม create.php แล้วเรียก API create.php (POST)
include 'db.php';

$body = array(
    "title"       => $_POST['title'],
    "channel"     => $_POST['channel'],
    "youtube_url" => $_POST['youtube_url'],
    "description" => $_POST['description'],
);

list($code, $result) = callApi("POST", $apiUrl . "/create.php", $body);

$message = isset($result['status']) ? $result['status'] : "Cannot connect to API";

// ถ้าสำเร็จ (201) กลับหน้าแรก ถ้าไม่สำเร็จ ย้อนกลับไปแก้ฟอร์ม
$next = ($code == 201) ? "window.location.href='index.php';" : "history.back();";
echo "<script>alert(" . json_encode($message) . "); " . $next . "</script>";
?>