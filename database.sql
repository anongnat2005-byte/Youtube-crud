<?php
// ไฟล์เชื่อมต่อฐานข้อมูล (ทุกไฟล์ที่ต้องใช้ฐานข้อมูลจะ include ไฟล์นี้)

$servername = "localhost";
$username   = "root";
$password   = "";            // XAMPP ค่าเริ่มต้นไม่มีรหัสผ่าน (ถ้าใช้ MAMP ให้ใส่ "root")
$dbname     = "youtube_db";

// ที่อยู่ API ของเรา (หน้าเว็บจะเรียกผ่าน cURL)
// ถ้า XAMPP ใช้พอร์ตอื่น เช่น 8888 ให้เปลี่ยนเป็น http://localhost:8888/...
$apiUrl = "http://localhost/youtube_crud/api/videos";

$dbh = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ดึงรหัสวิดีโอ 11 ตัวอักษรออกจากลิงก์ YouTube
function youtubeId($url)
{
    if (preg_match('/(?:youtu\.be\/|v=|embed\/|shorts\/)([A-Za-z0-9_-]{11})/', $url, $m)) {
        return $m[1];
    }
    return "";
}

// ลิงก์รูปปกของวิดีโอ
function thumbnail($url)
{
    return "https://img.youtube.com/vi/" . youtubeId($url) . "/hqdefault.jpg";
}

// เรียก API ด้วย cURL (ใช้ในหน้าเว็บทุกหน้า)
// $method = GET / POST / PATCH / DELETE   $body = array ที่จะส่งเป็น JSON
// คืนค่า array: [รหัสสถานะ HTTP, ข้อมูลที่ตอบกลับ]
function callApi($method, $url, $body = null)
{
    $curl = curl_init();
    $options = array(
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST  => $method,
        CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
    );
    if ($body !== null) {
        $options[CURLOPT_POSTFIELDS] = json_encode($body, JSON_UNESCAPED_UNICODE);
    }
    curl_setopt_array($curl, $options);

    $response = curl_exec($curl);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    return array($code, json_decode($response, true));
}

// ป้องกัน XSS เวลาแสดงข้อความบนหน้าเว็บ
function h($text)
{
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}
?>