<?php
// Fetch user data for editing
if (isset($_GET['id']) && $_GET['act'] === 'edit') {
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

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    try {
        $stmtUpdate = $condb->prepare("
            UPDATE users 
            SET username = :username,
                phone = :phone,
                role_id = :role_id
            WHERE user_id = :user_id
        ");

        $stmtUpdate->execute([
            ':user_id' => $_POST['id'],
            ':username' => $_POST['username'],
            ':phone' => $_POST['phone'],
            ':role_id' => $_POST['role_id']
        ]);

        echo '<script>
            setTimeout(function() {
                swal({
                    title: "แก้ไขข้อมูลสำเร็จ",
                    text: "กำลังกลับไปยังหน้าหลัก...",
                    type: "success",
                    timer: 2000,
                    showConfirmButton: false
                }, function() {
                    window.location = "member.php";
                });
            }, 100);
        </script>';
    } catch (PDOException $e) {
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "เกิดข้อผิดพลาด",
                    text: "' . addslashes($e->getMessage()) . '",
                    type: "error",
                    showConfirmButton: true
                });
            }, 100);
        </script>';
    }
    $condb = null;
}
?>

<style>
    .edit-form-container {
        padding: 20px;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }

    .card-header {
        background: linear-gradient(45deg, #007bff, #00b4db);
        color: white;
        border-radius: 10px 10px 0 0;
    }

    .form-group label {
        font-weight: 600;
        color: #333;
    }

    .form-control {
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0,123,255,0.3);
    }

    .btn-primary {
        background: #007bff;
        border: none;
        padding: 8px 25px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: #0056b3;
        transform: translateY(-2px);
    }

    .btn-danger {
        padding: 8px 25px;
        transition: all 0.3s ease;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-sm-6">
                    <h1 class="m-0" style="color: #2c3e50;">
                        <i class="fas fa-user-edit mr-2"></i>ฟอร์มปรับปรุงข้อมูลสมาชิก
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card edit-form-container">
                        <div class="card-header">
                            <h3 class="card-title mb-0">แก้ไขข้อมูลผู้ใช้</h3>
                        </div>
                        <form method="post" action="">
                            <div class="card-body">
                                <!-- Username -->
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">
                                        <i class="fas fa-user mr-2"></i>Username
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" 
                                               name="username" 
                                               class="form-control" 
                                               value="<?= htmlspecialchars($row['username'] ?? '') ?>"
                                               required>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">
                                        <i class="fas fa-envelope mr-2"></i>Email
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="email" 
                                               name="email" 
                                               class="form-control" 
                                               value="<?= htmlspecialchars($row['email'] ?? '') ?>"
                                               disabled>
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">
                                        <i class="fas fa-phone mr-2"></i>เบอร์โทร
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="tel" 
                                               name="phone" 
                                               class="form-control" 
                                               value="<?= htmlspecialchars($row['phone'] ?? '') ?>"
                                               required>
                                    </div>
                                </div>

                                <!-- Role -->
                                <div class="form-group row align-items-center">
                                    <label class="col-sm-3 col-form-label">
                                        <i class="fas fa-user-shield mr-2"></i>Level
                                    </label>
                                    <div class="col-sm-9">
                                        <select name="role_id" class="form-control" required>
                                            <?php
                                            $roleOptions = [
                                                '1' => 'Admin',
                                                '2' => 'Staff'
                                            ];
                                            $currentRole = $row['role_id'] ?? '';
                                            $currentRoleName = $roleOptions[$currentRole] ?? 'เลือกระดับ';
                                            ?>
                                            <option value="" disabled selected><?= $currentRoleName ?></option>
                                            <?php foreach ($roleOptions as $value => $label): ?>
                                                <option value="<?= $value ?>"><?= $label ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Buttons -->
                                <div class="form-group row">
                                    <div class="col-sm-9 offset-sm-3">
                                        <input type="hidden" 
                                               name="id" 
                                               value="<?= htmlspecialchars($row['user_id'] ?? '') ?>">
                                        <button type="submit" 
                                                class="btn btn-primary mr-2">
                                            <i class="fas fa-save mr-2"></i>ปรับปรุงข้อมูล
                                        </button>
                                        <a href="member.php" 
                                           class="btn btn-danger">
                                            <i class="fas fa-times mr-2"></i>ยกเลิก
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>