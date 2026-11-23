<?php
// update.php : แก้ไขวิดีโอ (PATCH)  แก้เฉพาะช่องที่ส่งมา
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=utf-8");
include '../../db.php';

$data = json_decode(file_get_contents("php://input"), true); // true = แปลงเป็น array

if ($_SERVER['REQUEST_METHOD'] != "PATCH") {
    http_response_code(405);
    echo json_encode(array("status" => "Error: Only PATCH method is allowed"));
    die();
}

if (empty($data['id'])) {
    http_response_code(400);
    echo json_encode(array("status" => "Error: id is required"));
    die();
}

// ช่องที่อนุญาตให้แก้ได้
$allowed = array("title", "channel", "description", "youtube_url", "views", "likes");
$sets = array();
$values = array();

foreach ($allowed as $field) {
    if (isset($data[$field])) {
        $sets[] = "$field = ?";
        $values[] = $data[$field];
    }
}

if (count($sets) == 0) {
    http_response_code(400);
    echo json_encode(array("status" => "Error: nothing to update"));
    die();
}

try {
    $values[] = $data['id'];
    $stmt = $dbh->prepare("UPDATE videos SET " . implode(", ", $sets) . " WHERE id = ?");

    if ($stmt->execute($values)) {
        echo json_encode(array("status" => "Video updated"));
    } else {
        echo json_encode(array("status" => "Video cannot updated"));
    }
    $dbh = null;
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array("status" => "Connection failed: " . $e->getMessage()));
    die();
}
?>