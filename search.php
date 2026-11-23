<?php
// search.php : ค้นหาวิดีโอจาก YouTube Data API v3 (เรียกผ่าน cURL แล้ว json_decode)
// ผลที่ได้กด "บันทึกลงระบบ" เพื่อส่งไป callInsert.php -> API create.php ของเรา
include 'db.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : "";

// อ่าน API key จากไฟล์ youtube_key.php (ไฟล์นี้ไม่ขึ้น GitHub)
$youtubeApiKey = "";
if (file_exists(__DIR__ . '/youtube_key.php')) {
    include __DIR__ . '/youtube_key.php';
}
$hasKey = ($youtubeApiKey != "" && $youtubeApiKey != "PASTE_YOUR_KEY_HERE");

$items = array();
$error = "";

if ($hasKey && $q != "") {
    $url = $youtubeApiUrl . "?" . http_build_query(array(
        "part"       => "snippet",
        "type"       => "video",
        "maxResults" => 12,
        "q"          => $q,
        "key"        => $youtubeApiKey,
    ));

    list($code, $resp) = callApi("GET", $url);

    if ($code == 200 && isset($resp['items'])) {
        $items = $resp['items'];
    } else {
        // ข้อความผิดพลาดจาก Google เช่น key ผิด หรือยังไม่ได้เปิดใช้ API
        $error = isset($resp['error']['message']) ? $resp['error']['message'] : "เรียก YouTube API ไม่สำเร็จ";
    }
}

$pageTitle = "ค้นหาจาก YouTube";
include 'header.php';
?>

<h4 class="fw-bold mb-3"><i class="bi bi-youtube text-danger"></i> ค้นหาจาก YouTube</h4>

<form class="d-flex gap-2 mb-4" method="GET" action="search.php" style="max-width: 640px;">
  <input class="form-control rounded-pill" type="search" name="q" value="<?php echo h($q); ?>"
         placeholder="พิมพ์คำค้นหา เช่น แมว, lofi, ทำอาหาร" required>
  <button class="btn btn-yt px-4" type="submit">ค้นหา</button>
</form>

<?php if (!$hasKey) { ?>
  <div class="form-card">
    <h6 class="fw-bold">ยังไม่ได้ตั้งค่า API key</h6>
    <ol class="mb-0">
      <li>คัดลอกไฟล์ <code>youtube_key.example.php</code> แล้วตั้งชื่อใหม่เป็น <code>youtube_key.php</code></li>
      <li>เปิดไฟล์ใหม่ แล้วใส่ API key ของคุณแทน <code>PASTE_YOUR_KEY_HERE</code></li>
      <li>รีเฟรชหน้านี้</li>
    </ol>
  </div>
<?php } elseif ($error != "") { ?>
  <div class="alert alert-danger"><?php echo h($error); ?></div>
<?php } elseif ($q != "" && count($items) == 0) { ?>
  <p class="text-muted">ไม่พบผลลัพธ์</p>
<?php } ?>

<div class="row g-4">
  <?php foreach ($items as $item) {
      $videoId = $item['id']['videoId'];
      // ข้อความจาก YouTube มีรหัส HTML เช่น &amp; จึงแปลงกลับก่อน
      $title   = html_entity_decode($item['snippet']['title'], ENT_QUOTES, 'UTF-8');
      $channel = html_entity_decode($item['snippet']['channelTitle'], ENT_QUOTES, 'UTF-8');
      $desc    = html_entity_decode($item['snippet']['description'], ENT_QUOTES, 'UTF-8');
      $thumb   = $item['snippet']['thumbnails']['medium']['url'];
  ?>
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
      <div class="video-card">
        <a class="thumb" href="https://www.youtube.com/watch?v=<?php echo h($videoId); ?>" target="_blank">
          <img src="<?php echo h($thumb); ?>" alt="">
          <span class="play"><i class="bi bi-play-circle-fill"></i></span>
        </a>
        <div class="mt-2">
          <div class="video-title"><?php echo h($title); ?></div>
          <div class="video-meta mb-2"><?php echo h($channel); ?></div>

          <!-- ส่งต่อไป callInsert.php เพื่อบันทึกลงระบบของเรา -->
          <form action="callInsert.php" method="post">
            <input type="hidden" name="title" value="<?php echo h(mb_substr($title, 0, 150)); ?>">
            <input type="hidden" name="channel" value="<?php echo h(mb_substr($channel, 0, 100)); ?>">
            <input type="hidden" name="description" value="<?php echo h($desc); ?>">
            <input type="hidden" name="youtube_url" value="https://www.youtube.com/watch?v=<?php echo h($videoId); ?>">
            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
              <i class="bi bi-plus-lg"></i> บันทึกลงระบบ
            </button>
          </form>
        </div>
      </div>
    </div>
  <?php } ?>
</div>

<?php include 'footer.php'; ?>