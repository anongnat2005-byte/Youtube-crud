<?php
// create.php : ฟอร์มเพิ่มวิดีโอ (ส่งข้อมูลไปที่ callInsert.php)
include 'db.php';
$pageTitle = "เพิ่มวิดีโอ";
include 'header.php';
?>

<div class="row justify-content-center">
  <div class="col-lg-9">
    <div class="form-card">
      <h4 class="fw-bold mb-4">เพิ่มวิดีโอ</h4>

      <form action="callInsert.php" method="post">
        <div class="row g-4">
          <div class="col-md-7">
            <div class="mb-3">
              <label class="form-label" for="title">ชื่อวิดีโอ</label>
              <input type="text" class="form-control" id="title" name="title" maxlength="150" required>
            </div>
            <div class="mb-3">
              <label class="form-label" for="channel">ชื่อช่อง</label>
              <input type="text" class="form-control" id="channel" name="channel" maxlength="100" required>
            </div>
            <div class="mb-3">
              <label class="form-label" for="youtube_url">ลิงก์ YouTube</label>
              <input type="url" class="form-control" id="youtube_url" name="youtube_url"
                     placeholder="https://www.youtube.com/watch?v=..." required>
            </div>
            <div class="mb-3">
              <label class="form-label" for="description">คำอธิบาย</label>
              <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
          </div>

          <div class="col-md-5">
            <label class="form-label">ตัวอย่างรูปปก</label>
            <div class="preview" id="preview"><i class="bi bi-image" style="font-size:2.5rem;"></i></div>
          </div>
        </div>

        <div class="mt-2">
          <button type="submit" class="btn btn-yt px-4">บันทึก</button>
          <a href="index.php" class="btn btn-light px-4">ยกเลิก</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// แสดงรูปปกทันทีเมื่อวางลิงก์ YouTube
document.getElementById('youtube_url').addEventListener('input', function () {
  var m = this.value.match(/(?:youtu\.be\/|v=|embed\/|shorts\/)([A-Za-z0-9_-]{11})/);
  var box = document.getElementById('preview');
  if (m) {
    box.innerHTML = '<img src="https://img.youtube.com/vi/' + m[1] + '/hqdefault.jpg" alt="">';
  } else {
    box.innerHTML = '<i class="bi bi-image" style="font-size:2.5rem;"></i>';
  }
});
</script>

<?php include 'footer.php'; ?>