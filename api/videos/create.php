<?php
// create.php : เพิ่มวิดีโอใหม่ (POST)
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=utf-8");
include '../../db.php';

$data = json_decode(file_get_contents("php://input")); // ดึงข้อมูลจาก body ที่เป็น json

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    http_response_code(405);
    echo json_encode(array("status" => "Error: Only POST method is allowed"));
    die();
}

// ต้องส่ง title, channel, youtube_url มาครบ และลิงก์ต้องเป็นลิงก์ YouTube
if (empty($data->title) || empty($data->channel) || empty($data->youtube_url) || youtubeId($data->youtube_url) == "") {
    http_response_code(400);
    echo json_encode(array("status" => "Error: title, channel and a valid youtube_url are required"));
    die();
}

try {
    $stmt = $dbh->prepare("INSERT INTO videos (title, channel, description, youtube_url) VALUES (?, ?, ?, ?)");
    $stmt->bindParam(1, $data->title);
    $stmt->bindParam(2, $data->channel);
    $description = isset($data->description) ? $data->description : "";
    $stmt->bindParam(3, $description);
    $stmt->bindParam(4, $data->youtube_url);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("status" => "Video created", "id" => $dbh->lastInsertId()));
    } else {
        echo json_encode(array("status" => "Video cannot created"));
    }
    $dbh = null;
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array("status" => "Connection failed: " . $e->getMessage()));
    die();
}
?>