<?php
if (isset($_GET['id']) && isset($_GET['act']) && $_GET['act'] == 'edit') {
    // Query to get reservation details with joined user and product data
    $query = $condb->prepare("
        SELECT r.*, u.username, p.product_name, p.quantity as stock_quantity 
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
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-body">
                        <div class="card card-primary">
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
                                            <small class="form-text text-muted">
                                                ชื่อสินค้า: <?php echo htmlspecialchars($row['product_name']); ?>
                                                <br>จำนวนในคลัง: <?php echo htmlspecialchars($row['stock_quantity']); ?>
                                            </small>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">จำนวนที่ขอเบิก</label>
                                        <div class="col-sm-4">
                                            <input type="number" class="form-control" value="<?php echo htmlspecialchars($row['quantity'] ?? ''); ?>" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">จำนวนที่อนุมัติ</label>
                                        <div class="col-sm-4">
                                            <input type="number" name="approved_quantity" class="form-control" required 
                                                   value="<?php echo htmlspecialchars(min($row['quantity'], $row['stock_quantity'])); ?>" 
                                                   max="<?php echo htmlspecialchars($row['stock_quantity']); ?>" min="0">
                                            <?php if ($row['stock_quantity'] < $row['quantity']): ?>
                                                <small class="form-text text-warning">
                                                    สต็อกมีจำกัด อนุมัติได้สูงสุด <?php echo htmlspecialchars($row['stock_quantity']); ?> ชิ้น
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">สถานะ</label>
                                        <div class="col-sm-4">
                                            <select name="status" class="form-control mt-2" required>
                                                <!-- Current status (selected and disabled) -->
                                                <option value="<?php echo htmlspecialchars($row['status']); ?>" selected disabled>
                                                    <?php echo htmlspecialchars($row['status']); ?>
                                                </option>
                                                <!-- Divider -->
                                                <option value="" disabled>-- เลือกสถานะใหม่ --</option>
                                                <!-- Options for new status -->
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
                                            <a href="History_disbursement.php" class="btn btn-danger">ยกเลิก</a>
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserve_id']) && isset($_POST['user_id']) && isset($_POST['product_id']) && isset($_POST['status']) && isset($_POST['approved_quantity'])) {
    $reserve_id = $_POST['reserve_id'];
    $user_id = $_POST['user_id'];
    $product_id = $_POST['product_id'];
    $status = $_POST['status'];
    $approved_quantity = intval($_POST['approved_quantity']);

    // Check stock quantity before update
    $stock_check = $condb->prepare("SELECT quantity FROM products WHERE product_id = ?");
    $stock_check->execute([$product_id]);
    $current_stock = $stock_check->fetch(PDO::FETCH_ASSOC)['quantity'];

    if ($status === 'confirmed' && $approved_quantity > $current_stock) {
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "จำนวนที่อนุมัติเกินสต็อก",
                    text: "สามารถอนุมัติได้สูงสุด ' . $current_stock . ' ชิ้นเท่านั้น",
                    type: "error"
                });
            }, 1000);
        </script>';
    } else {
        // Update reservation
        $stmtUpdate = $condb->prepare("UPDATE reservations SET 
            user_id = :user_id,
            product_id = :product_id,
            quantity = :quantity,
            status = :status
            WHERE reserve_id = :reserve_id
        ");
        
        $stmtUpdate->bindParam(':reserve_id', $reserve_id, PDO::PARAM_INT);
        $stmtUpdate->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmtUpdate->bindParam(':product_id', $product_id, PDO::PARAM_STR);
        $stmtUpdate->bindParam(':quantity', $approved_quantity, PDO::PARAM_INT);
        $stmtUpdate->bindParam(':status', $status, PDO::PARAM_STR);
        
        // Update product quantity if confirmed
        if ($status === 'confirmed' && $approved_quantity > 0) {
            $new_stock = $current_stock - $approved_quantity;
            $stock_update = $condb->prepare("UPDATE products SET quantity = ? WHERE product_id = ?");
            $stock_update->execute([$new_stock, $product_id]);
        }

        $result = $stmtUpdate->execute();

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
    
    $condb = null; // Close connection
}
?>