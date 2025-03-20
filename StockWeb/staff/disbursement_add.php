<?php
// Query ข้อมูลสินค้าสำหรับ dropdown
$queryPro = $condb->prepare("SELECT product_id, product_name, quantity FROM products");
$queryPro->execute();          
$rsPro = $queryPro->fetchAll();

// Query ข้อมูลผู้ใช้สำหรับ dropdown
$queryUsers = $condb->prepare("SELECT user_id, username FROM users"); // Fixed duplicate username
$queryUsers->execute();
$rsUsers = $queryUsers->fetchAll();
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>ฟอร์มเบิก - ยืมวัสดุสำนักงาน</h1>
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
                <input type="hidden" name="username" value="<?php echo $_SESSION['username'] ?? ''; ?>">
                <div class="card-body">
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">รหัสการเบิก-ยืม</label>
                    <div class="col-sm-4">
                      <input name="reserve_id" class="form-control" disabled placeholder="สร้างอัตโนมัติ">
                    </div>
                  </div> 
                  <!-- Container สำหรับรายการสินค้า -->
                  <div id="products-container">
                    <div class="product-item">
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label">ชื่อสินค้า</label>
                        <div class="col-sm-4">
                          <select name="products[0][product_id]" class="form-control" required>
                            <option value="">-- เลือกสินค้า --</option>
                            <?php foreach($rsPro as $row): ?>
                              <option value="<?php echo htmlspecialchars($row['product_id']); ?>">
                                <?php echo htmlspecialchars($row['product_name']) . " (คงเหลือ: " . $row['quantity'] . ")"; ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="col-sm-2">
                          <input type="number" name="products[0][request_quantity]" class="form-control" required placeholder="จำนวน" min="1">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-4">
                      <button type="button" class="btn btn-success" id="add-product">เพิ่มรายการสินค้า</button>
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">ผู้ทำรายการ</label>
                    <div class="col-sm-4">
                      <?php if (isset($_SESSION['username'])): ?>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" disabled>
                      <?php else: ?>
                        <p class="text-danger">กรุณาล็อกอินก่อนทำรายการ</p>
                      <?php endif; ?>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">สถานะ</label>
                    <div class="col-sm-4">
                      <select name="status" class="form-control" required>
                        <option value="" disabled>-- เลือกสถานะ --</option> 
                        <option value="pending">เบิก</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-4">
                      <button type="submit" class="btn btn-primary">บันทึก</button>
                      <a href="reserve_list.php" class="btn btn-danger">ยกเลิก</a>
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

<!-- JavaScript สำหรับเพิ่มรายการสินค้า -->
<script>
let productCount = 1;

document.getElementById('add-product').addEventListener('click', function() {
    const container = document.getElementById('products-container');
    const newItem = document.createElement('div');
    newItem.className = 'product-item';
    newItem.innerHTML = `
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">ชื่อสินค้า</label>
            <div class="col-sm-4">
                <select name="products[${productCount}][product_id]" class="form-control" required>
                    <option value="">-- เลือกสินค้า --</option>
                    <?php foreach($rsPro as $row): ?>
                        <option value="<?php echo htmlspecialchars($row['product_id']); ?>">
                            <?php echo htmlspecialchars($row['product_name']) . " (คงเหลือ: " . $row['quantity'] . ")"; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-2">
                <input type="number" name="products[${productCount}][request_quantity]" class="form-control" required placeholder="จำนวน" min="1">
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-danger btn-sm remove-product">ลบ</button>
            </div>
        </div>
    `;
    container.appendChild(newItem);
    productCount++;

    // Add remove functionality
    newItem.querySelector('.remove-product').addEventListener('click', function() {
        container.removeChild(newItem);
    });
});
</script>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['products']) && isset($_POST['status'])) {
    try {
        if (!isset($_SESSION['username'])) {
            throw new Exception("กรุณาล็อกอินก่อนทำรายการ");
        }

        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
        $created_at = date('Y-m-d H:i:s');
        $products = $_POST['products'];
        
        $condb->beginTransaction();
        
        // Verify user
        $stmtUserCheck = $condb->prepare("SELECT username FROM users WHERE username = :username");
        $stmtUserCheck->execute([':username' => $username]);
        
        if ($stmtUserCheck->rowCount() === 0) {
            throw new Exception("ไม่พบข้อมูลผู้ใช้");
        }

        foreach ($products as $index => $product) {
            $product_id = filter_var($product['product_id'], FILTER_SANITIZE_STRING);
            $request_quantity = filter_var($product['request_quantity'], FILTER_VALIDATE_INT);

            if ($request_quantity === false || $request_quantity <= 0) {
                throw new Exception("จำนวนที่ขอไม่ถูกต้องสำหรับรายการที่ " . ($index + 1));
            }

            // Check stock with FOR UPDATE to prevent race conditions
            $stmtCheck = $condb->prepare("SELECT quantity FROM products WHERE product_id = :product_id FOR UPDATE");
            $stmtCheck->execute([':product_id' => $product_id]);
            $productData = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if (!$productData) {
                throw new Exception("ไม่พบสินค้าสำหรับรายการที่ " . ($index + 1));
            }

            if ($request_quantity > $productData['quantity']) {
                throw new Exception("จำนวนสินค้าไม่เพียงพอสำหรับรายการที่ " . ($index + 1) . 
                    " (ขอ: $request_quantity, คงเหลือ: {$productData['quantity']})");
            }

            // Insert reservation
            $stmtInsert = $condb->prepare("INSERT INTO reservations 
                (product_id, user_id, quantity, status, created_at)
                VALUES (:product_id, :user_id, :quantity, :status, :created_at)");
                
            $stmtInsert->execute([
                ':product_id' => $product_id,
                ':user_id' => $_SESSION['staff_id'],
                ':quantity' => $request_quantity,
                ':status' => $status,
                ':created_at' => $created_at
            ]);
        }

        $condb->commit();
        
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "บันทึกข้อมูลสำเร็จ",
                    text: "รอการอนุมัติจากผู้ดูแลระบบ",
                    type: "success"
                }, function() {
                    window.location = "History.php";
                });
            }, 1000);
        </script>';

    } catch (Exception $e) {
        $condb->rollBack();
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "เกิดข้อผิดพลาด",
                    text: "' . htmlspecialchars($e->getMessage()) . '",
                    type: "error"
                }, function() {
                    window.location = "reserve_form.php";
                });
            }, 1000);
        </script>';
    }
    
    $condb = null;
}

?>

