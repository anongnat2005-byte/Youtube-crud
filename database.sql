-- ฐานข้อมูลสำหรับโปรเจกต์ YouTube REST API
-- วิธีใช้: เปิด phpMyAdmin -> Import -> เลือกไฟล์นี้

CREATE DATABASE IF NOT EXISTS youtube_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE youtube_db;

DROP TABLE IF EXISTS videos;

CREATE TABLE videos (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(150) NOT NULL,
  channel     VARCHAR(100) NOT NULL,
  description TEXT,
  youtube_url VARCHAR(255) NOT NULL,
  views       INT NOT NULL DEFAULT 0,
  likes       INT NOT NULL DEFAULT 0,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ข้อมูลตัวอย่าง
INSERT INTO videos (title, channel, description, youtube_url, views, likes) VALUES
('Never Gonna Give You Up', 'Rick Astley', 'มิวสิกวิดีโอเพลงดัง', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 100, 20),
('Me at the zoo', 'jawed', 'วิดีโอแรกของ YouTube', 'https://www.youtube.com/watch?v=jNQXAC9IVRw', 50, 10),
('PSY - GANGNAM STYLE', 'officialpsy', 'มิวสิกวิดีโอเพลง Gangnam Style', 'https://www.youtube.com/watch?v=9bZkp7q19f0', 80, 15);