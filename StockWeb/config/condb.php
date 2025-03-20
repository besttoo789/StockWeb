<?php
// $servername = "localhost";
// $username = "root";
// $password = ""; //ถ้าไม่ได้ตั้งรหัสผ่านให้ลบ yourpassword ออก



$servername = "192.168.1.45";
$username = "newmos1";
$password = "newmos1"; //ถ้าไม่ได้ตั้งรหัสผ่านให้ลบ yourpassword ออก
 
try {
  $condb = new PDO("mysql:host=$servername;dbname=test;charset=utf8", $username, $password);
  // set the PDO error mode to exception
  $condb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>