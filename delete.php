<?php
// delete.php : รับ id จาก URL แล้วเรียก API delete.php (DELETE)
include 'db.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

list($code, $result) = callApi("DELETE", $apiUrl . "/delete.php", array("id" => $id));

$message = isset($result['status']) ? $result['status'] : "Cannot connect to API";
echo "<script>alert(" . json_encode($message) . "); window.location.href='index.php';</script>";
?>