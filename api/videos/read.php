<?php
// read.php : ดึงวิดีโอทั้งหมด (GET)  ค้นหาได้ด้วย ?q=คำค้น
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=utf-8");
include '../../db.php';

try {
    $q = isset($_GET['q']) ? $_GET['q'] : "";

    $stmt = $dbh->prepare("SELECT * FROM videos WHERE title LIKE ? OR channel LIKE ? ORDER BY id DESC");
    $stmt->execute(["%$q%", "%$q%"]);

    $videos = array();
    foreach ($stmt as $row) {
        array_push($videos, array(
            "id"          => $row['id'],
            "title"       => $row['title'],
            "channel"     => $row['channel'],
            "description" => $row['description'],
            "youtube_url" => $row['youtube_url'],
            "thumbnail"   => thumbnail($row['youtube_url']),
            "views"       => $row['views'],
            "likes"       => $row['likes'],
            "created_at"  => $row['created_at'],
        ));
    }

    echo json_encode($videos, JSON_UNESCAPED_UNICODE);
    $dbh = null;
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array("status" => "Connection failed: " . $e->getMessage()));
    die();
}
?>