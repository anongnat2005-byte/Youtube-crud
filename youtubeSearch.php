<?php
// youtubeSearch.php : ค้นหาวิดีโอจาก YouTube Data API v3 ด้วย PHP + cURL

// ใส่ API key ที่ขอจาก Google Cloud ตรงนี้ (อย่านำ key จริงขึ้น GitHub)
$apikey = "PASTE_YOUR_KEY_HERE";

$endpoint = "https://www.googleapis.com/youtube/v3/search";
$query    = isset($_GET['query']) ? trim($_GET['query']) : "";
$results  = "";

if ($query != "") {
    // สร้าง URL สำหรับเรียก API
    $apicall = $endpoint . "?" . http_build_query(array(
        "part"       => "snippet",
        "type"       => "video",
        "maxResults" => 10,
        "q"          => $query,
        "key"        => $apikey,
    ));

    // เรียก API ด้วย cURL
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $apicall);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);

    // แปลง JSON เป็น array
    $data = json_decode($response, true);

    if (isset($data['items'])) {
        // สร้างแถวของตารางสำหรับแต่ละวิดีโอ
        foreach ($data['items'] as $item) {
            $videoId = $item['id']['videoId'];
            $title   = $item['snippet']['title'];
            $channel = $item['snippet']['channelTitle'];
            $pic     = $item['snippet']['thumbnails']['medium']['url'];
            $link    = "https://www.youtube.com/watch?v=" . $videoId;

            $results .= "<tr>";
            $results .= "<td><img src=\"$pic\"></td>";
            $results .= "<td><a href=\"$link\" target=\"_blank\">$title</a><br>$channel</td>";
            $results .= "</tr>";
        }
    } else {
        // ถ้า API ตอบ error เช่น key ผิด
        $msg = isset($data['error']['message']) ? $data['error']['message'] : "เรียก API ไม่สำเร็จ";
        $results = "<tr><td>เกิดข้อผิดพลาด: " . htmlspecialchars($msg) . "</td></tr>";
    }
}
?>
<html>
<head>
<meta charset="UTF-8">
<title>YouTube Search Results for <?php echo htmlspecialchars($query); ?></title>
<style type="text/css">body { font-family: arial, sans-serif; }</style>
</head>
<body>
<h1>YouTube Search Results for <?php echo htmlspecialchars($query); ?></h1>
<table>
<tr>
  <td>
    <form method="GET">
      <input type="text" name="query" value="<?php echo htmlspecialchars($query); ?>">
      <input type="submit" value="Search">
    </form>
  </td>
</tr>
<?php echo $results; ?>
</table>
</body>
</html>