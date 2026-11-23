<?php
// edit.php : ฟอร์มแก้ไขวิดีโอ (ดึงข้อมูลเดิมจาก API readone.php แล้วส่งไป callEdit.php)
include 'db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;
list($code, $data) = callApi("GET", $apiUrl . "/readone.php?id=" . urlencode($id));

if ($code != 200) {
    echo "<script>alert('ไม่พบวิดีโอ'); window.location.href='index.php';</script>";
    die();
}

$pageTitle = "แก้ไขวิดีโอ";
include 'header.php';
?>

<div class="row justify-content-center">
  <div class="col-lg-9">
    <div class="form-card">
      <h4 class="fw-bold mb-4">แก้ไขวิดีโอ</h4>

      <form action="callEdit.php" method="post">
        <input type="hidden" name="id" value="<?php echo h($data['id']); ?>">

        <div class="row g-4">
          <div class="col-md-7">
            <div class="mb-3">
              <label class="form-label" for="title">ชื่อวิดีโอ</label>
              <input type="text" class="form-control" id="title" name="title" maxlength="150"
                     value="<?php echo h($data['title']); ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label" for="channel">ชื่อช่อง</label>
              <input type="text" class="form-control" id="channel" name="channel" maxlength="100"
                     value="<?php echo h($data['channel']); ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label" for="youtube_url">ลิงก์ YouTube</label>
              <input type="url" class="form-control" id="youtube_url" name="youtube_url"
                     value="<?php echo h($data['youtube_url']); ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label" for="description">คำอธิบาย</label>
              <textarea class="form-control" id="description" name="description" rows="3"><?php echo h($data['description']); ?></textarea>
            </div>
          </div>

          <div class="col-md-5">
            <label class="form-label">รูปปก</label>
            <div class="preview" id="preview"><img src="<?php echo h($data['thumbnail']); ?>" alt=""></div>
          </div>
        </div>

        <div class="mt-2">
          <button type="submit" class="btn btn-yt px-4">บันทึกการแก้ไข</button>
          <a href="index.php" class="btn btn-light px-4">ยกเลิก</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.getElementById('youtube_url').addEventListener('input', function () {
  var m = this.value.match(/(?:youtu\.be\/|v=|embed\/|shorts\/)([A-Za-z0-9_-]{11})/);
  if (m) {
    document.getElementById('preview').innerHTML =
      '<img src="https://img.youtube.com/vi/' + m[1] + '/hqdefault.jpg" alt="">';
  }
});
</script>

<?php include 'footer.php'; ?>