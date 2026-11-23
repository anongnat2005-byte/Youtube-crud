<?php
// readone.php : ดึงวิดีโอ 1 รายการ (GET)  ตัวอย่าง readone.php?id=1
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=utf-8");
include '../../db.php';

try {
    $stmt = $dbh->prepare("SELECT * FROM videos WHERE id = ?");
    $stmt->execute([isset($_GET['id']) ? $_GET['id'] : 0]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        http_response_code(404);
        echo json_encode(array("status" => "Video not found"));
        die();
    }

    $video = array(
        "id"          => $row['id'],
        "title"       => $row['title'],
        "channel"     => $row['channel'],
        "description" => $row['description'],
        "youtube_url" => $row['youtube_url'],
        "thumbnail"   => thumbnail($row['youtube_url']),
        "views"       => $row['views'],
        "likes"       => $row['likes'],
        "created_at"  => $row['created_at'],
    );

    // ใส่ JSON_UNESCAPED_UNICODE เพื่อให้แสดงภาษาไทยได้ถูกต้อง
    echo json_encode($video, JSON_UNESCAPED_UNICODE);
    $dbh = null;
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array("status" => "Connection failed: " . $e->getMessage()));
    die();
}
?>