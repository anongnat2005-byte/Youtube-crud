<?php
// ส่วนหัวของทุกหน้า (แถบเมนู + CSS)  ตั้งค่า $pageTitle ก่อน include
if (!isset($pageTitle)) $pageTitle = "MyTube";
if (!isset($q)) $q = "";
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo h($pageTitle); ?> - MyTube</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    :root { --yt-red: #ff0033; --yt-bg: #f9f9f9; --yt-text: #0f0f0f; --yt-muted: #606060; }
    body { font-family: 'Sarabun', sans-serif; background: var(--yt-bg); color: var(--yt-text); }

    /* แถบด้านบน */
    .topbar { background: #fff; border-bottom: 1px solid #e5e5e5; position: sticky; top: 0; z-index: 10; }
    .brand { display: flex; align-items: center; gap: 6px; font-weight: 700; font-size: 1.35rem;
             color: var(--yt-text); text-decoration: none; letter-spacing: -0.5px; }
    .brand .logo { background: var(--yt-red); color: #fff; border-radius: 8px; width: 34px; height: 24px;
                   display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem; }
    .search-box { max-width: 520px; width: 100%; }
    .search-box .form-control { border-radius: 40px 0 0 40px; border-color: #ccc; padding-left: 18px; }
    .search-box .btn { border-radius: 0 40px 40px 0; border: 1px solid #ccc; border-left: 0; background: #f8f8f8; }
    .btn-yt { background: var(--yt-red); color: #fff; border-radius: 40px; font-weight: 500; }
    .btn-yt:hover { background: #d60029; color: #fff; }

    /* การ์ดวิดีโอ */
    .video-card { background: transparent; border: 0; }
    .video-card .thumb { position: relative; display: block; border-radius: 14px; overflow: hidden;
                         aspect-ratio: 16 / 9; background: #ddd; transition: border-radius .2s, transform .2s; }
    .video-card .thumb img { width: 100%; height: 100%; object-fit: cover; }
    .video-card:hover .thumb { border-radius: 4px; transform: translateY(-2px); }
    .video-card .play { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
                        background: rgba(0,0,0,.25); opacity: 0; transition: opacity .2s; color: #fff; font-size: 3rem; }
    .video-card:hover .play { opacity: 1; }
    .video-title { font-weight: 700; font-size: 1rem; line-height: 1.35; color: var(--yt-text); text-decoration: none;
                   display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .video-meta { color: var(--yt-muted); font-size: .875rem; }
    .avatar { width: 38px; height: 38px; border-radius: 50%; background: var(--yt-red); color: #fff; flex-shrink: 0;
              display: flex; align-items: center; justify-content: center; font-weight: 700; }
    .actions a { color: var(--yt-muted); font-size: 1.05rem; margin-left: 10px; text-decoration: none; }
    .actions a:hover { color: var(--yt-red); }

    /* ฟอร์ม */
    .form-card { background: #fff; border-radius: 16px; border: 1px solid #e5e5e5; padding: 28px; }
    .form-control:focus { border-color: var(--yt-red); box-shadow: 0 0 0 .2rem rgba(255,0,51,.15); }
    .preview { aspect-ratio: 16 / 9; border-radius: 12px; background: #eee; overflow: hidden;
               display: flex; align-items: center; justify-content: center; color: #999; }
    .preview img { width: 100%; height: 100%; object-fit: cover; }
  </style>
</head>
<body>

<nav class="topbar py-2">
  <div class="container-xl d-flex align-items-center gap-3 flex-wrap">
    <a href="index.php" class="brand"><span class="logo"><i class="bi bi-play-fill"></i></span>MyTube</a>

    <form class="search-box mx-auto d-flex" method="GET" action="index.php">
      <input class="form-control" type="search" name="q" value="<?php echo h($q); ?>" placeholder="ค้นหาชื่อวิดีโอหรือช่อง">
      <button class="btn" type="submit"><i class="bi bi-search"></i></button>
    </form>

    <a href="search.php" class="btn btn-outline-secondary rounded-pill"><i class="bi bi-youtube"></i> ค้นหาจาก YouTube</a>
    <a href="create.php" class="btn btn-yt"><i class="bi bi-plus-lg"></i> เพิ่มวิดีโอ</a>
  </div>
</nav>

<main class="container-xl py-4">