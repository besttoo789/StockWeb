<?php
// เริ่มต้น session
session_start();
require_once("config/condb.php");

if (isset($_POST['username']) && isset($_POST['password']) && $_POST['action'] == 'login') {

    // รับค่าจากฟอร์ม
    $username = $_POST['username'];
    $password = $_POST['password']; // รหัสผ่านที่ผู้ใช้ป้อน

    // Query ดึงข้อมูลจากตาราง users
    $stmtLogin = $condb->prepare("SELECT user_id, username, email, phone, password, role_id FROM users WHERE username = :username");
    $stmtLogin->bindParam(':username', $username, PDO::PARAM_STR);
    $stmtLogin->execute();

    if ($stmtLogin->rowCount() == 1) {
        $row = $stmtLogin->fetch(PDO::FETCH_ASSOC);

        // Debug: แสดงข้อมูลที่ดึงมา (ยกเลิกคอมเมนต์เพื่อดู)
            // echo "<pre>";
            // echo "Username: " . $row['username'] . "\n";
            // echo "Stored Password: " . $row['password'] . "\n";
            // echo "Input Password: " . $password . "\n";
            // echo "Password Verify Result: " . (password_verify($password, $row['password']) ? "True" : "False") . "\n";
            // exit;

        // ตรวจสอบรหัสผ่านด้วย password_verify (สำหรับ bcrypt)
        if (password_verify($password, $row['password'])) {
            session_unset();
            $_SESSION['staff_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['phone'] = $row['phone'];
            $_SESSION['m_level'] = $row['role_id'];

            $condb = null;

            // ปรับเงื่อนไข role: 1 = admin, 2 = staff
            if ($row['role_id'] == 1) { // admin
                header('Location: admin/');
                exit();
            } else if ($row['role_id'] == 2) { // staff
                header('Location: staff/');
                exit();
            } else {
                // ถ้า role ไม่ใช่ 1 หรือ 2
                echo '<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>';
                echo '<script>
                    setTimeout(function() {
                        swal({
                            title: "เกิดข้อผิดพลาด",
                            text: "Role ไม่ถูกต้อง",
                            type: "warning"
                        }, function() {
                            window.location = "login.php";
                        });
                    }, 1000);
                </script>';
            }
        } else {
            // รหัสผ่านไม่ถูกต้อง
            echo '<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>';
            echo '<script>
                setTimeout(function() {
                    swal({
                        title: "เกิดข้อผิดพลาด",
                        text: "Password ไม่ถูกต้อง ลองใหม่อีกครั้ง",
                        type: "warning"
                    }, function() {
                        window.location = "login.php";
                    });
                }, 1000);
            </script>';
        }
    } else {
        // ไม่พบ username
        echo '<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>';
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "เกิดข้อผิดพลาด",
                    text: "Username ไม่ถูกต้อง ลองใหม่อีกครั้ง",
                    type: "warning"
                }, function() {
                    window.location = "login.php";
                });
            }, 1000);
        </script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="https://i.pinimg.com/736x/9d/2c/88/9d2c8838e2fd0329ed77e83dca4c5c6b.jpg">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/dist/css/adminlte.min.css?v=3.2.0">
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <b>Stock</b>Login</a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">ล็อคอินเข้าใช้งานระบบยืม - คืน</p>
                <form action="" method="post">
                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" name="action" value="login" class="btn btn-outline-success">Login</button>
                        </div>
                    </div>
                </form>
                <div class="social-auth-link text-center mb-3">
                    <p></p>
                    <p>- ติดต่อ -</p>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/plugins/jquery/jquery.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/dist/js/adminlte.min.js?v=3.2.0"></script>
</body>
</html>