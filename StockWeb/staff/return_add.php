<?php
// Query ข้อมูลการจองสำหรับเลือก
$queryReserve = $condb->prepare("SELECT reserve_id, product_id, user_id, quantity, status FROM reservations WHERE status = 'confirmed'");
$queryReserve->execute();
$rsReserve = $queryReserve->fetchAll();

// Query ข้อมูลสินค้าสำหรับแสดงผล
$queryPro = $condb->prepare("SELECT product_id, product_name, quantity FROM products");
$queryPro->execute();
$rsPro = $queryPro->fetchAll();

// Query ข้อมูลผู้ใช้สำหรับแสดงผล
$queryUsers = $condb->prepare("SELECT user_id, username FROM users");
$queryUsers->execute();
$rsUsers = $queryUsers->fetchAll();
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>ฟอร์มคืนสินค้า</h1>
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
                    <label class="col-sm-2 col-form-label">รหัสการจอง</label>
                    <div class="col-sm-4">
                    <select name="reserve_id" class="form-control" required onchange="this.form.submit()">
  <option value="">-- เลือกรายการที่ต้องการคืน --</option>
  <?php foreach($rsReserve as $row): ?>
    <option value="<?php echo $row['reserve_id']; ?>" 
            <?php echo (isset($_POST['reserve_id']) && $_POST['reserve_id'] == $row['reserve_id']) ? 'selected' : ''; ?>>
      #<?php echo $row['reserve_id'] . " - User: " . $row['user_id'] . " - Product: " . $row['product_id'] . " (Qty: " . $row['quantity'] . ")"; ?>
    </option>
  <?php endforeach; ?>
</select>
                    </div>
                  </div>

                  <?php
                  if(isset($_POST['reserve_id']) && !empty($_POST['reserve_id'])) {
                    $reserve_id = $_POST['reserve_id'];
                    $stmt = $condb->prepare("SELECT * FROM reservations WHERE reserve_id = :reserve_id");
                    $stmt->bindParam(':reserve_id', $reserve_id);
                    $stmt->execute();
                    $reserve = $stmt->fetch(PDO::FETCH_ASSOC);
                  ?>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">รหัสผู้ใช้</label>
                    <div class="col-sm-4">
                      <input name="user_id" class="form-control" value="<?php echo $reserve['user_id']; ?>" disabled>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">ชื่อสินค้า</label>
                    <div class="col-sm-4">
                      <select name="product_id" class="form-control" disabled>
                        <?php foreach($rsPro as $row): ?>
                          <option value="<?php echo $row['product_id']; ?>" <?php echo ($row['product_id'] == $reserve['product_id']) ? 'selected' : ''; ?>>
                            <?php echo $row['product_name'] . " (คงเหลือ: " . $row['quantity'] . ")"; ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">จำนวนที่ยืม</label>
                    <div class="col-sm-4">
                      <input type="number" name="quantity" class="form-control" value="<?php echo $reserve['quantity']; ?>" disabled>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">จำนวนที่คืน</label>
                    <div class="col-sm-4">
                      <input type="number" name="returned_quantity" class="form-control" required placeholder="จำนวนที่คืน" min="1" max="<?php echo $reserve['quantity']; ?>">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">วันที่สร้าง</label>
                    <div class="col-sm-4">
                      <input type="datetime-local" name="created_at" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($reserve['created_at'])); ?>" disabled>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">วันหมดอายุ</label>
                    <div class="col-sm-4">
                      <input type="datetime-local" name="expires_at" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($reserve['created_at']. ' + 7 days')); ?>">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">สถานะ</label>
                    <div class="col-sm-4">
                      <select name="status" class="form-control" required>
                        <option value="returned">คืนแล้ว</option>
                        <option value="partial">คืนบางส่วน</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-4">
                      <button type="submit" name="submit_return" class="btn btn-primary">บันทึกการคืน</button>
                      <a href="return_list.php" class="btn btn-danger">ยกเลิก</a>
                    </div>
                  </div>
                  <?php } ?>
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
if (isset($_POST['submit_return'])) {
  $reserve_id = $_POST['reserve_id'];
  $returned_quantity = $_POST['returned_quantity'];
  $status = $_POST['status'];
  $expires_at = $_POST['expires_at'];

  // ดึงข้อมูลการจองเดิม
  $stmtCheck = $condb->prepare("SELECT quantity, product_id FROM reservations WHERE reserve_id = :reserve_id");
  $stmtCheck->bindParam(':reserve_id', $reserve_id);
  $stmtCheck->execute();
  $reserve = $stmtCheck->fetch(PDO::FETCH_ASSOC);

  if ($returned_quantity > $reserve['quantity']) {
    echo '<script>
           setTimeout(function() {
            swal({
                title: "จำนวนที่คืนเกิน",
                text: "คืนได้สูงสุด: ' . $reserve['quantity'] . ' ชิ้น",
                type: "error"
            });
          }, 1000);
      </script>';
  } else {
    // อัพเดทข้อมูลการคืน
    $stmtUpdate = $condb->prepare("UPDATE reservations SET 
      status = :status,
      expires_at = :expires_at,
      actual_quantity = :actual_quantity,
      returned_quantity = :returned_quantity
      WHERE reserve_id = :reserve_id");

    $actual_quantity = $reserve['quantity']; // จำนวนที่ยืมจริง
    $stmtUpdate->bindParam(':status', $status);
    $stmtUpdate->bindParam(':expires_at', $expires_at);
    $stmtUpdate->bindParam(':actual_quantity', $actual_quantity);
    $stmtUpdate->bindParam(':returned_quantity', $returned_quantity);
    $stmtUpdate->bindParam(':reserve_id', $reserve_id);
    
    $result = $stmtUpdate->execute();

    if($result) {
      // อัพเดทสต็อกสินค้า
      $stmtStock = $condb->prepare("UPDATE products SET quantity = quantity + :returned_quantity WHERE product_id = :product_id");
      $stmtStock->bindParam(':returned_quantity', $returned_quantity, PDO::PARAM_INT);
      $stmtStock->bindParam(':product_id', $reserve['product_id']);
      $stmtStock->execute();

      echo '<script>
           setTimeout(function() {
            swal({
                title: "บันทึกการคืนสำเร็จ",
                type: "success"
            }, function() {
                window.location = "History.php";
            });
          }, 1000);
      </script>';
    } else {
      echo '<script>
           setTimeout(function() {
            swal({
                title: "เกิดข้อผิดพลาด",
                text: "ไม่สามารถบันทึกข้อมูลได้",
                type: "error"
            });
          }, 1000);
      </script>';
    }
  }
  
  $condb = null;
}
?>