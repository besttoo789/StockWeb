<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>ฟอร์มเพิ่มสินค้า</h1>
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
                    <label class="col-sm-2 col-form-label">ชื่อสินค้า</label>
                    <div class="col-sm-4">
                      <input type="text" name="product_name" class="form-control" required placeholder="ชื่อสินค้า">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">ประเภท</label>
                    <div class="col-sm-4">
                      <input type="text" name="category" class="form-control" required placeholder="ประเภท">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">จำนวน</label>
                    <div class="col-sm-4">
                      <input type="number" name="quantity" class="form-control" required placeholder="จำนวน" min="0">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Barcode</label>
                    <div class="col-sm-4">
                      <input type="text" name="barcode" class="form-control" required placeholder="บาร์โค้ด">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">สถานะ</label>
                    <div class="col-sm-4">
                      <select name="stock_status" class="form-control" required>
                        <option value="" disabled selected>-- สถานะ --</option>
                        <option value="ใช้งานได้">ใช้งานได้</option>
                        <option value="ใช้งานไม่ได้">ใช้งานไม่ได้</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">วันที่นำเข้า</label>
                    <div class="col-sm-4">
                      <input type="datetime-local" name="created_at" class="form-control" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-4">
                      <button type="submit" class="btn btn-primary">บันทึก</button>
                      <a href="product.php" class="btn btn-danger">ยกเลิก</a>
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

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        $requiredFields = ['product_name', 'category', 'quantity', 'stock_status', 'created_at', 'barcode'];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                throw new Exception("กรุณากรอกข้อมูลให้ครบทุกช่อง");
            }
        }

        // Sanitize inputs
        $product_name = filter_var($_POST['product_name'], FILTER_SANITIZE_STRING);
        $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
        $quantity = filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT);
        $barcode = filter_var($_POST['barcode'], FILTER_SANITIZE_STRING);
        $stock_status = filter_var($_POST['stock_status'], FILTER_SANITIZE_STRING);
        $created_at = filter_var($_POST['created_at'], FILTER_SANITIZE_STRING);

        // Additional validation
        if (!is_numeric($quantity) || $quantity < 0) {
            throw new Exception("จำนวนต้องเป็นตัวเลขและไม่ติดลบ");
        }

        // Check for duplicate product name or barcode
        $stmtCheck = $condb->prepare("SELECT product_name, barcode FROM products WHERE product_name = :product_name OR barcode = :barcode");
        $stmtCheck->execute([
            ':product_name' => $product_name,
            ':barcode' => $barcode
        ]);

        if ($stmtCheck->rowCount() > 0) {
            $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            if ($row['product_name'] === $product_name) {
                throw new Exception("ชื่อสินค้านี้มีอยู่ในระบบแล้ว");
            }
            if ($row['barcode'] === $barcode) {
                throw new Exception("บาร์โค้ดนี้มีอยู่ในระบบแล้ว");
            }
        }

        // Insert new product
        $stmtInsert = $condb->prepare("INSERT INTO products 
            (product_name, category, quantity, barcode, stock_status, created_at)
            VALUES 
            (:product_name, :category, :quantity, :barcode, :stock_status, :created_at)");

        $params = [
            ':product_name' => $product_name,
            ':category' => $category,
            ':quantity' => $quantity,
            ':barcode' => $barcode,
            ':stock_status' => $stock_status,
            ':created_at' => $created_at
        ];

        $result = $stmtInsert->execute($params);

        $condb = null;
        if ($result) {
            echo '<script>
                setTimeout(function() {
                    swal({
                        title: "เพิ่มข้อมูลสำเร็จ",
                        type: "success"
                    }, function() {
                        window.location = "product.php";
                    });
                }, 1000);
            </script>';
        } else {
            throw new Exception("ไม่สามารถบันทึกข้อมูลได้");
        }
    } catch (Exception $e) {
        $condb = null;
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "เกิดข้อผิดพลาด",
                    text: "' . addslashes($e->getMessage()) . '",
                    type: "error"
                }, function() {
                    window.location = "product.php";
                });
            }, 1000);
        </script>';
    }
}
?>