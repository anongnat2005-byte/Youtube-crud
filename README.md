# Youtube-crud
# YouTube REST API (PHP + MySQL + XAMPP)

โปรเจกต์ทำตามโครงสร้างที่สอนในบทที่ 4 (REST Web Service) ใช้ XAMPP, PHP (PDO), MySQL, Postman และ VS Code

## วิธีติดตั้ง

1. เปิด **XAMPP Control Panel** กด Start ที่ **Apache** และ **MySQL**
2. เปิด `http://localhost/phpmyadmin/` → แท็บ **Import** → เลือกไฟล์ `database.sql` → กด Import
   (จะได้ฐานข้อมูล `youtube_db` พร้อมตาราง `videos` และข้อมูลตัวอย่าง)
3. คัดลอกโฟลเดอร์ `youtube_crud` ไปไว้ใน `C:\xampp\htdocs\` (Mac ใช้ `/Applications/XAMPP/htdocs/`)
   ถ้าโหลดจาก GitHub ให้แตก ZIP แล้วเปลี่ยนชื่อโฟลเดอร์เป็น `youtube_crud`
4. เปิดโฟลเดอร์ `youtube_crud` ด้วย VS Code
5. เปิดเบราว์เซอร์ที่ `http://localhost/youtube_crud/index.php`

ถ้า XAMPP ของคุณใช้พอร์ตอื่น (เช่น 8888) หรือ MySQL มีรหัสผ่าน ให้แก้ที่ไฟล์ `db.php` เพียงไฟล์เดียว

## โครงสร้างไฟล์

```
youtube_crud/
├── database.sql        สร้างฐานข้อมูลและตาราง
├── db.php              เชื่อมต่อฐานข้อมูล + ฟังก์ชันช่วย
├── api/videos/         ส่วน API (ตอบกลับเป็น JSON)
│   ├── read.php        GET     ดึงทั้งหมด (ค้นหาได้ ?q=)
│   ├── readone.php     GET     ดึง 1 รายการ ?id=
│   ├── create.php      POST    เพิ่ม
│   ├── update.php      PATCH   แก้ไข
│   └── delete.php      DELETE  ลบ
├── index.php           หน้าแรก (แสดงวิดีโอ)
├── watch.php           หน้าดูวิดีโอ
├── search.php          ค้นหาจาก YouTube API จริง (ต้องมี youtube_key.php)
├── youtube_key.example.php  ตัวอย่างไฟล์ใส่ API key
├── create.php          ฟอร์มเพิ่ม  → callInsert.php
├── edit.php            ฟอร์มแก้ไข  → callEdit.php
├── delete.php          ลบวิดีโอ
└── youtube-crud.postman_collection.json
```

## ค้นหาจาก YouTube จริง (YouTube Data API v3)

หน้า `search.php` เรียก YouTube Data API ผ่าน cURL และกด "บันทึกลงระบบ" เพื่อเก็บวิดีโอเข้า API ของเราได้

ขอ API key ฟรี:
1. เข้า `https://console.cloud.google.com/` แล้วล็อกอินบัญชี Google
2. สร้าง Project ใหม่ (เช่นชื่อ `youtube-crud`)
3. ไปที่ **APIs & Services → Library** ค้นหา **YouTube Data API v3** แล้วกด **Enable**
4. ไปที่ **APIs & Services → Credentials → Create credentials → API key**
5. คัดลอก `youtube_key.example.php` เป็น `youtube_key.php` แล้วใส่ key ลงไป

ห้ามนำไฟล์ `youtube_key.php` ที่ใส่ key จริงขึ้น GitHub (ใส่ไว้ใน `.gitignore` แล้ว)
โควตาฟรี 10,000 หน่วยต่อวัน การค้นหา 1 ครั้งใช้ 100 หน่วย

## ทดสอบด้วย Postman

Import ไฟล์ `youtube-crud.postman_collection.json` จะได้ 5 request:

| Request | Method | URL | Body |
|---|---|---|---|
| read | GET | `/api/videos/read.php` | - |
| readone | GET | `/api/videos/readone.php?id=1` | - |
| create | POST | `/api/videos/create.php` | JSON: title, channel, youtube_url, description |
| update | PATCH | `/api/videos/update.php` | JSON: id + ช่องที่ต้องการแก้ |
| delete | DELETE | `/api/videos/delete.php` | JSON: `{"id": 1}` |

ใน Postman ให้เลือก Body → raw → JSON สำหรับ create, update, delete

## การทำงาน

หน้าเว็บ (index, create, edit, delete) ไม่คุยกับฐานข้อมูลโดยตรง แต่เรียก API ผ่าน cURL
`เบราว์เซอร์ → หน้า PHP → cURL → API → MySQL → JSON กลับมา`