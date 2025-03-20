<?php
session_start();
date_default_timezone_set("Asia/Bangkok");
// ตรวจสอบว่ามี session หรือไม่ ถ้าไม่มีให้ไปหน้า logout
if (empty($_SESSION['m_level']) && empty($_SESSION['staff_id']) && empty($_SESSION['username'])) {
    header('Location: ../logout.php');
    exit();
}

// ตรวจสอบว่าเป็น staff หรือไม่ (role = 2) ถ้าไม่ใช่ staff ให้ไปหน้า logout
if (isset($_SESSION['m_level']) && isset($_SESSION['staff_id']) && $_SESSION['m_level'] != 2) {
    header('Location: ../logout.php');
    exit();
    
}

require_once '../config/condb.php';
// echo "<pre>";
// echo "Session: " . print_r($_SESSION, true) . "\n";
// echo "Username: " . $row['username'] . "\n";
// echo "username: " . $_SESSION['username'] . "\n";
// echo "Stored Password: " . $row['password'] . "\n";
// echo "Input Password: " . $password . "\n";
// echo "Password Verify Result: " . (password_verify($password, $row['password']) ? "True" : "False") . "\n";
// exit;
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Stock</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="https://i.pinimg.com/736x/9d/2c/88/9d2c8838e2fd0329ed77e83dca4c5c6b.jpg">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
  <!-- Summernote -->
  <link rel="stylesheet" href="../assets/plugins/summernote/summernote-bs4.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Sweet Alert -->
  <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
</head>

<body class="sidebar-mini layout-fixed sidebar-collapse">
  <div class="wrapper">