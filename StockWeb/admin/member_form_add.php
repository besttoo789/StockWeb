<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Input validation
        $requiredFields = ['username', 'name', 'surname', 'password', 'role', 'email'];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                throw new Exception("กรุณากรอกข้อมูลให้ครบทุกช่อง");
            }
        }

        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $surname = filter_var($_POST['surname'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $role = $_POST['role'];
        
        // Email validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("รูปแบบอีเมลไม่ถูกต้อง");
        }

        // Password validation
        $password = $_POST['password'];
        if (strlen($password) < 8) {
            throw new Exception("รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร");
        }
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Check for duplicate username and email
        $stmtCheck = $condb->prepare("SELECT username, email FROM users WHERE username = :username OR email = :email");
        $stmtCheck->execute([':username' => $username, ':email' => $email]);
        
        if ($stmtCheck->rowCount() > 0) {
            $existing = $stmtCheck->fetch();
            if ($existing['username'] === $username) {
                throw new Exception("Username นี้มีอยู่ในระบบแล้ว");
            }
            if ($existing['email'] === $email) {
                throw new Exception("Email นี้มีอยู่ในระบบแล้ว");
            }
        }

        // Insert new member
        $stmtInsert = $condb->prepare("INSERT INTO users 
            (username, password, name, surname, email, role)
            VALUES 
            (:username, :password, :name, :surname, :email, :role)");

        $params = [
            ':username' => $username,
            ':password' => $hashed_password,
            ':name' => $name,
            ':surname' => $surname,
            ':email' => $email,
            ':role' => $role
        ];

        $result = $stmtInsert->execute($params);

        if ($result) {
            echo '<script>
                setTimeout(function() {
                    swal({
                        title: "เพิ่มข้อมูลสำเร็จ",
                        type: "success"
                    }, function() {
                        window.location = "member.php";
                    });
                }, 1000);
            </script>';
        }
    } catch (Exception $e) {
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "เกิดข้อผิดพลาด",
                    text: "' . addslashes($e->getMessage()) . '",
                    type: "error"
                });
            }, 1000);
        </script>';
    }
    $condb = null;
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>ฟอร์มเพิ่มข้อมูลสมาชิก</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-body">
                        <div class="card card-primary">
                            <form action="" method="post">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Username</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="username" class="form-control" required placeholder="Username" maxlength="50">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">ชื่อ</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="name" class="form-control" required placeholder="ชื่อ" maxlength="100">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">นามสกุล</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="surname" class="form-control" required placeholder="นามสกุล" maxlength="100">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-4">
                                            <input type="email" name="email" class="form-control" required placeholder="Email" maxlength="100">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">รหัสผ่าน</label>
                                        <div class="col-sm-4">
                                            <input type="password" name="password" class="form-control" required placeholder="Password" minlength="8">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Level</label>
                                        <div class="col-sm-4">
                                            <select name="role" class="form-control" required>
                                                <option value="" disabled selected>-- เลือกระดับ --</option>
                                                <option value="1">admin</option>
                                                <option value="2">staff</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-2"></div>
                                        <div class="col-sm-4">
                                            <button type="submit" class="btn btn-primary">บันทึก</button>
                                            <a href="member.php" class="btn btn-danger">ยกเลิก</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>