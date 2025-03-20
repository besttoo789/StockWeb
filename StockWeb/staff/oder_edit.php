<?php
if (isset($_GET['id']) && isset($_GET['act']) && $_GET['act'] == 'edit') {
    // Query to get reservation details with joined user and product data
    $query = $condb->prepare("
        SELECT r.*, u.username, p.product_name 
        FROM reservations r 
        JOIN users u ON r.user_id = u.user_id
        JOIN products p ON r.product_id = p.product_id 
        WHERE r.reserve_id = ?
    ");
    $query->execute([$_GET['id']]);
    $row = $query->fetch(PDO::FETCH_ASSOC);
    
    if (!$row) {
        $row = null; // No record found
    }
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>อนุมัติการเบิกจ่าย</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-body">
                        <div class="card card-primary">
                            <!-- form start -->
                            <?php if ($row): ?>
                            <form action="" method="post"> 
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">รหัสการเบิก-จ่าย</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="reserve_id" class="form-control" value="<?php echo htmlspecialchars($row['reserve_id']); ?>" disabled>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">ชื่อผู้ทำรายการ</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="user_id" class="form-control" required placeholder="user_id" value="<?php echo htmlspecialchars($row['user_id'] ?? ''); ?>">
                                            <small class="form-text text-muted">ชื่อผู้ใช้: <?php echo htmlspecialchars($row['username']); ?></small>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">เลขสินค้า</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="product_id" class="form-control" required placeholder="product_id" value="<?php echo htmlspecialchars($row['product_id'] ?? ''); ?>">
                                            <small class="form-text text-muted">ชื่อสินค้า: <?php echo htmlspecialchars($row['product_name']); ?></small>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">สถานะ</label>
                                        <div class="col-sm-4">
                                        <select name="status" class="form-control mt-2" required>
                                        <optiondisabled value="<?php echo htmlspecialchars($row['status']); ?>" selected><?php echo htmlspecialchars($row['status']); ?></option disabled>
                                        <option value="" disabled>-- เลือกสถานะใหม่ --</option>
                                        <option value="confirmed">อนุมัติ</option>
                                        <option value="cancelled">ไม่อนุมัติ</option>
                                        <option value="pending">รอดำเนินการ</option>
                                        <option value="returned">คืนสำเร็จ</option>
                                        
                                    </select>
                                        </div>
                                    </div>

                                   

                                    <div class="form-group row">
                                        <label class="col-sm-2"></label>
                                        <div class="col-sm-4">
                                            <input type="hidden" name="reserve_id" value="<?php echo htmlspecialchars($row['reserve_id']); ?>">
                                            <button type="submit" class="btn btn-primary">ยืนยัน</button>
                                            <a href="oder.php" class="btn btn-danger">ยกเลิก</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <?php else: ?>
                                <div class="alert alert-danger">ไม่พบข้อมูลการเบิก-จ่ายที่ต้องการแก้ไข</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserve_id']) && isset($_POST['user_id']) && isset($_POST['product_id']) && isset($_POST['status'])) {
    $reserve_id = $_POST['reserve_id'];
    $user_id = $_POST['user_id'];
    $product_id = $_POST['product_id'];
    $status = $_POST['status'];

    // SQL update
    $stmtUpdate = $condb->prepare("UPDATE reservations SET 
        user_id = :user_id,
        product_id = :product_id,
        status = :status
        WHERE reserve_id = :reserve_id
    ");
    
    // bindParam
    $stmtUpdate->bindParam(':reserve_id', $reserve_id, PDO::PARAM_INT);
    $stmtUpdate->bindParam(':user_id', $user_id, PDO::PARAM_STR);
    $stmtUpdate->bindParam(':product_id', $product_id, PDO::PARAM_STR);
    $stmtUpdate->bindParam(':status', $status, PDO::PARAM_STR);
    $result = $stmtUpdate->execute();

    $condb = null; // Close connection to the database

    if ($result) {
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "แก้ไขข้อมูลสำเร็จ",
                    type: "success"
                }, function() {
                    window.location = "disbursement.php";
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
                    window.location = "disbursement.php";
                });
            }, 1000);
        </script>';
    }
}
?>