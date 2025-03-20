<?php
if (isset($_GET['id']) && $_GET['act'] == 'editPwd') {
    try {
        // Single row query to display only one record
        $stmtMemberDetail = $condb->prepare("SELECT * FROM tbl_member WHERE id = ?");
        $stmtMemberDetail->execute([$_GET['id']]);
        $row = $stmtMemberDetail->fetch(PDO::FETCH_ASSOC);

        if (!$row || $stmtMemberDetail->rowCount() != 1) {
            header("Location: member.php");
            exit();
        }
    } catch (PDOException $e) {
        // Log error in production
        header("Location: member.php");
        exit();
    }
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>ฟอร์มแก้ไขรหัสผ่าน</h1>
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
                                        <label class="col-sm-2 col-form-label">Email/Username</label>
                                        <div class="col-sm-4">
                                            <input type="email" name="username" class="form-control" value="<?= htmlspecialchars($row['username']) ?>" disabled>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">ชื่อ - นามสกุล</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name'] . ' ' . $row['surname']) ?>" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">รหัสผ่านใหม่</label>
                                        <div class="col-sm-4">
                                            <input type="password" name="new_password" class="form-control" required placeholder="รหัสผ่านใหม่" minlength="8">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">ยืนยันรหัสผ่าน</label>
                                        <div class="col-sm-4">
                                            <input type="password" name="confirm_password" class="form-control" required placeholder="ยืนยันรหัสผ่านใหม่" minlength="8">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-2"></label>
                                        <div class="col-sm-4">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                                            <button type="submit" class="btn btn-primary">แก้ไขรหัสผ่าน</button>
                                            <a href="member.php" class="btn btn-danger">ยกเลิก</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
                            try {
                                $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
                                $newPassword = trim($_POST['new_password']);
                                $confirmPassword = trim($_POST['confirm_password']);

                                // Validate password
                                if (strlen($newPassword) < 8) {
                                    throw new Exception("รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร");
                                }

                                if ($newPassword !== $confirmPassword) {
                                    throw new Exception("รหัสผ่านไม่ตรงกัน");
                                }

                                // Hash password
                                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                                // SQL update
                                $stmtUpdate = $condb->prepare("UPDATE tbl_member SET password = :password WHERE id = :id");
                                $stmtUpdate->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
                                $stmtUpdate->bindParam(':id', $id, PDO::PARAM_INT);

                                if ($stmtUpdate->execute()) {
                                    echo '<script>
                                        setTimeout(function() {
                                            swal({
                                                title: "แก้ไขรหัสผ่านสำเร็จ",
                                                type: "success"
                                            }, function() {
                                                window.location = "member.php";
                                            });
                                        }, 1000);
                                    </script>';
                                }
                            } catch (Exception $e) {
                                $errorMessage = $e->getMessage();
                                echo '<script>
                                    setTimeout(function() {
                                        swal({
                                            title: "เกิดข้อผิดพลาด",
                                            text: "' . htmlspecialchars($errorMessage) . '",
                                            type: "error"
                                        }, function() {
                                            window.location = "member.php?id=' . htmlspecialchars($id) . '&act=editPwd";
                                        });
                                    }, 1000);
                                </script>';
                            } finally {
                                $condb = null; // Close connection
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>