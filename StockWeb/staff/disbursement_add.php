<?php
// Query ข้อมูลสินค้าและผู้ใช้
try {
    $queryPro = $condb->prepare("SELECT product_id, product_name, quantity FROM products WHERE quantity > 0");
    $queryPro->execute();
    $rsPro = $queryPro->fetchAll();

    $queryUsers = $condb->prepare("SELECT user_id, username FROM users ORDER BY username ASC");
    $queryUsers->execute();
    $rsUsers = $queryUsers->fetchAll();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="far fa-clipboard"></i> ฟอร์มเบิก-ยืมวัสดุสำนักงาน</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary shadow">
                <div class="card-header">
                    <h3 class="card-title">กรอกข้อมูลการเบิก-ยืม</h3>
                </div>

                <form action="" method="post" id="reserveForm">
                    <div class="card-body">
                        <!-- รหัสการเบิก-ยืม -->
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">รหัสการเบิก-ยืม</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" disabled placeholder="สร้างอัตโนมัติ">
                            </div>
                        </div>

                        <!-- รายการสินค้า -->
                        <div id="products-container">
                            <div class="product-item card card-outline card-success mb-3">
                                <div class="card-body">
                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-2 col-form-label">ชื่อสินค้า</label>
                                        <div class="col-sm-5">
                                            <select name="products[0][product_id]" class="form-control select2" required>
                                                <option value="">-- เลือกสินค้า --</option>
                                                <?php foreach($rsPro as $row): ?>
                                                    <option value="<?= htmlspecialchars($row['product_id']) ?>">
                                                        <?= htmlspecialchars($row['product_name']) . " (คงเหลือ: " . $row['quantity'] . ")" ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="number" name="products[0][request_quantity]" 
                                                   class="form-control" required placeholder="จำนวน" 
                                                   min="1" max="999">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ปุ่มเพิ่มรายการ -->
                        <div class="form-group row">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-10">
                                <button type="button" class="btn btn-success btn-sm" id="add-product">
                                    <i class="fas fa-plus"></i> เพิ่มรายการสินค้า
                                </button>
                            </div>
                        </div>

                        <!-- ผู้ทำรายการ -->
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">ผู้ทำรายการ</label>
                            <div class="col-sm-4">
                                <?php if (isset($_SESSION['username'])): ?>
                                    <input type="text" class="form-control bg-light" 
                                           value="<?= htmlspecialchars($_SESSION['username']) ?>" 
                                           readonly>
                                <?php else: ?>
                                    <div class="alert alert-warning">กรุณาล็อกอินก่อนทำรายการ</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- สถานะ -->
                        <div class="form-group row">
                    <label class="col-sm-2 col-form-label">สถานะ</label>
                    <div class="col-sm-4">
                      <select name="status" class="form-control" required>
                        <option value="" disabled>-- เลือกสถานะ --</option> 
                        <option value="pending">เบิก</option>
                      </select>
                    </div>
                  </div>

                        <!-- ปุ่มควบคุม -->
                        <div class="form-group row">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fas fa-save"></i> บันทึก
                                </button>
                                <a href="index.php" class="btn btn-danger">
                                    <i class="fas fa-times"></i> ยกเลิก
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let productCount = 1;
    const container = document.getElementById('products-container');

    document.getElementById('add-product').addEventListener('click', function() {
        const newItem = document.createElement('div');
        newItem.className = 'product-item card card-outline card-success mb-3';
        newItem.innerHTML = `
            <div class="card-body">
                <div class="form-group row align-items-center">
                    <label class="col-sm-2 col-form-label">ชื่อสินค้า</label>
                    <div class="col-sm-5">
                        <select name="products[${productCount}][product_id]" class="form-control" required>
                            <option value="">-- เลือกสินค้า --</option>
                            <?php foreach($rsPro as $row): ?>
                                <option value="<?= htmlspecialchars($row['product_id']) ?>">
                                    <?= htmlspecialchars($row['product_name']) . " (คงเหลือ: " . $row['quantity'] . ")" ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <input type="number" name="products[${productCount}][request_quantity]" 
                               class="form-control" required placeholder="จำนวน" min="1" max="999">
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-danger btn-sm remove-product">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        container.appendChild(newItem);
        productCount++;

        newItem.querySelector('.remove-product').addEventListener('click', function() {
            container.removeChild(newItem);
        });
    });

    // Form validation
    document.getElementById('reserveForm').addEventListener('submit', function(e) {
        const selects = document.querySelectorAll('select[required]');
        selects.forEach(select => {
            if (!select.value) {
                e.preventDefault();
                Swal.fire('เกิดข้อผิดพลาด', 'กรุณากรอกข้อมูลให้ครบถ้วน', 'error');
                return;
            }
        });
    });
});
</script>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_SESSION['username']) || !isset($_SESSION['staff_id'])) {
            throw new Exception("กรุณาล็อกอินก่อนทำรายการ");
        }

        $condb->beginTransaction();
        
        foreach ($_POST['products'] as $index => $product) {
            $product_id = filter_var($product['product_id'], FILTER_SANITIZE_STRING);
            $quantity = filter_var($product['request_quantity'], FILTER_VALIDATE_INT);
            $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

            if (!$quantity || $quantity <= 0) {
                throw new Exception("จำนวนที่ขอไม่ถูกต้องสำหรับรายการที่ " . ($index + 1));
            }

            // Check stock availability
            $stmt = $condb->prepare("SELECT quantity FROM products WHERE product_id = :id FOR UPDATE");
            $stmt->execute([':id' => $product_id]);
            $available = $stmt->fetchColumn();

            if ($quantity > $available) {
                throw new Exception("สินค้า " . htmlspecialchars($product['product_id']) . " คงเหลือไม่เพียงพอ");
            }

            // Insert reservation
            $stmt = $condb->prepare(
                "INSERT INTO reservations (product_id, user_id, quantity, status, created_at) 
                VALUES (:product_id, :user_id, :quantity, :status, NOW())"
            );
            $stmt->execute([
                ':product_id' => $product_id,
                ':user_id' => $_SESSION['staff_id'],
                ':quantity' => $quantity,
                ':status' => $status
            ]);
        }

        $condb->commit();
        echo "<script>
            Swal.fire({
                title: 'สำเร็จ',
                text: 'รอการอนุมัติจากผู้ดูแลระบบ',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => window.location = 'History.php');
        </script>";

    } catch (Exception $e) {
        $condb->rollBack();
        echo "<script>
            Swal.fire({
                title: 'ข้อผิดพลาด',
                text: '" . htmlspecialchars($e->getMessage()) . "',
                icon: 'error'
            });
        </script>";
    }
}
?>