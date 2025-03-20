<?php
// Fetch user data for editing
if (isset($_GET['id']) && $_GET['act'] === 'editPwd') {
    try {
        $stmtMemberDetail = $condb->prepare("SELECT * FROM users WHERE user_id = :id");
        $stmtMemberDetail->execute([':id' => $_GET['id']]);
        $row = $stmtMemberDetail->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            exit('User not found');
        }
    } catch (PDOException $e) {
        exit('Database error: ' . $e->getMessage());
    }
}
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>ฟอร์มเเก้ไขรหัสผ่าน</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="card card-primary">
                            <!-- form start -->
                            <form action="" method="post"> 
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Username</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="username" class="form-control" value="<?php echo $row['username'];?>" >
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"> Email </label>
                                        <div class="col-sm-4">
                                            <input type="text" name="name" class="form-control" required placeholder="ชื่อ" value="<?php echo $row['email'];?>" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">New Password</label>
                                        <div class="col-sm-4">
                                            <input type="password" name="NewPassword" class="form-control" required placeholder="รหัสผ่านใหม่">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Confirm Password</label>
                                        <div class="col-sm-4">
                                            <input type="password" name="Confirm_Password" class="form-control" required placeholder="ยืนยันรหัสผ่านใหม่">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-2"></label>
                                        <div class="col-sm-4">
                                            <input type="hidden" name="id" value="<?php echo $row['user_id'];?>">
                                            <button type="submit" class="btn btn-primary">เเก้ไขรหัสผ่าน</button>
                                            <a href="member.php" class="btn btn-danger">ยกเลิก</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <?php
                        if (isset($_POST['id']) && isset($_POST['NewPassword']) && isset($_POST['Confirm_Password'])) {
                            $user_id = $_POST['id'];
                            $NewPassword = $_POST['NewPassword'];
                            $Confirm_Password = $_POST['Confirm_Password'];

                            // สร้างเงื่อนไขตรวจสอบรหัสผ่าน
                            if ($NewPassword != $Confirm_Password) {
                                echo '<script>
                                    setTimeout(function() {
                                        swal({
                                            title: "รหัสผ่านไม่ตรงกัน",
                                            text: "กรุณากรอกรหัสผ่านใหม่อีกครั้ง",
                                            type: "error"
                                        }, function() {
                                            window.location = "member.php?id='.$id.'&act=editPwd";
                                        });
                                    }, 1000);
                                </script>';
                            } else {
                                $hashedPassword = password_hash($NewPassword, PASSWORD_DEFAULT);

                                $stmtUpdate = $condb->prepare("UPDATE users SET password = :password WHERE user_id = :id");
                                $stmtUpdate->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
                                $stmtUpdate->bindParam(':id', $user_id, PDO::PARAM_INT);
                                $result = $stmtUpdate->execute();
                                $condb = null; 

                                if ($result) {
                                    echo '<script>
                                        setTimeout(function() {
                                            swal({
                                                title: "แก้ไขข้อมูลสำเร็จ",
                                                type: "success"
                                            }, function() {
                                                window.location = "member.php";
                                            });
                                        }, 1000);
                                    </script>';
                                } else {
                                    echo '<script>
                                        setTimeout(function() {
                                            swal({
                                                title: "เกิดข้อผิดพลาด",
                                                type: "error"
                                            }, function() {
                                                window.location = "member.php";
                                            });
                                        }, 1000);
                                    </script>';
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
