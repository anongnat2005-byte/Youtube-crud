<?php
// watch.php : หน้าดูวิดีโอ (เรียก readone.php แล้วเพิ่มยอดวิวด้วย update.php)
include 'db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;
list($code, $data) = callApi("GET", $apiUrl . "/readone.php?id=" . urlencode($id));

if ($code != 200) {
    echo "<script>alert('ไม่พบวิดีโอ'); window.location.href='index.php';</script>";
    die();
}

// เปิดดูแล้วยอดวิว +1
$data['views'] = $data['views'] + 1;
callApi("PATCH", $apiUrl . "/update.php", array("id" => $data['id'], "views" => $data['views']));

$pageTitle = $data['title'];
include 'header.php';
?>

<div class="row justify-content-center">
  <div class="col-xl-9">
    <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm bg-dark">
      <iframe src="https://www.youtube.com/embed/<?php echo h(youtubeId($data['youtube_url'])); ?>"
              title="<?php echo h($data['title']); ?>" allowfullscreen></iframe>
    </div>

    <h4 class="fw-bold mt-3"><?php echo h($data['title']); ?></h4>

    <div class="d-flex align-items-center gap-2 flex-wrap">
      <div class="avatar"><?php echo h(mb_substr($data['channel'], 0, 1)); ?></div>
      <div class="fw-bold"><?php echo h($data['channel']); ?></div>
      <div class="ms-auto actions">
        <a href="edit.php?id=<?php echo h($data['id']); ?>"><i class="bi bi-pencil-square"></i> แก้ไข</a>
        <a href="delete.php?id=<?php echo h($data['id']); ?>"
           onclick="return confirm('คุณต้องการลบวิดีโอนี้ใช่หรือไม่?');"><i class="bi bi-trash3"></i> ลบ</a>
      </div>
    </div>

    <div class="form-card mt-3">
      <div class="fw-bold mb-1">
        <?php echo number_format($data['views']); ?> ครั้งที่ดู ·
        <i class="bi bi-hand-thumbs-up"></i> <?php echo number_format($data['likes']); ?>
      </div>
      <div style="white-space: pre-line;"><?php echo h($data['description']); ?></div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>