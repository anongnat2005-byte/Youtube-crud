<?php
// index.php : หน้าแรก แสดงวิดีโอทั้งหมด (เรียก API read.php ผ่าน cURL)
include 'db.php';

$q = isset($_GET['q']) ? $_GET['q'] : "";
list($code, $data) = callApi("GET", $apiUrl . "/read.php?q=" . urlencode($q));
if (!is_array($data)) $data = array();

$pageTitle = "หน้าแรก";
include 'header.php';
?>

<?php if (count($data) == 0) { ?>
  <div class="text-center py-5 text-muted">
    <i class="bi bi-camera-video-off" style="font-size: 3rem;"></i>
    <p class="mt-2">ไม่พบวิดีโอ</p>
  </div>
<?php } else { ?>
  <div class="row g-4">
    <?php foreach ($data as $row) { ?>
      <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
        <div class="video-card">
          <a class="thumb" href="watch.php?id=<?php echo h($row['id']); ?>">
            <img src="<?php echo h($row['thumbnail']); ?>" alt="">
            <span class="play"><i class="bi bi-play-circle-fill"></i></span>
          </a>

          <div class="d-flex gap-2 mt-2">
            <div class="avatar"><?php echo h(mb_substr($row['channel'], 0, 1)); ?></div>
            <div class="flex-grow-1">
              <a class="video-title" href="watch.php?id=<?php echo h($row['id']); ?>"><?php echo h($row['title']); ?></a>
              <div class="video-meta"><?php echo h($row['channel']); ?></div>
              <div class="d-flex justify-content-between align-items-center">
                <span class="video-meta">
                  <?php echo number_format($row['views']); ?> ครั้ง · <i class="bi bi-hand-thumbs-up"></i> <?php echo number_format($row['likes']); ?>
                </span>
                <span class="actions">
                  <a href="edit.php?id=<?php echo h($row['id']); ?>" title="แก้ไข"><i class="bi bi-pencil-square"></i></a>
                  <a href="delete.php?id=<?php echo h($row['id']); ?>" title="ลบ"
                     onclick="return confirm('คุณต้องการลบวิดีโอนี้ใช่หรือไม่?');"><i class="bi bi-trash3"></i></a>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
<?php } ?>

<?php include 'footer.php'; ?>