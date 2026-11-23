<?php
// delete.php : ลบวิดีโอ (DELETE)  ส่ง {"id": 1} ใน body
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=utf-8");
include '../../db.php';

$data = json_decode(file_get_contents("php://input")); // ดึงข้อมูลจาก body ที่เป็น json

if ($_SERVER['REQUEST_METHOD'] != "DELETE") {
    http_response_code(405);
    echo json_encode(array("status" => "Error: Only DELETE method is allowed"));
    die();
}

if (empty($data->id)) {
    http_response_code(400);
    echo json_encode(array("status" => "Error: id is required"));
    die();
}

try {
    $stmt = $dbh->prepare("DELETE FROM videos WHERE id = ?");
    $stmt->bindParam(1, $data->id);

    if ($stmt->execute() && $stmt->rowCount() > 0) {
        echo json_encode(array("status" => "Video is deleted"));
    } else {
        http_response_code(404);
        echo json_encode(array("status" => "Video cannot deleted"));
    }
    $dbh = null;
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array("status" => "Connection failed: " . $e->getMessage()));
    die();
}
?>