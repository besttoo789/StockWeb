<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>ฟอร์มเพิ่มสินค้า</h1>
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
                    <label class="col-sm-2 col-form-label">ชื่อสินค้า</label>
                    <div class="col-sm-4">
                      <input type="text" name="product_name" class="form-control" required placeholder="ชื่อสินค้า">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">ประเภท</label>
                    <div class="col-sm-4">
                      <input type="text" name="category" class="form-control" required placeholder="category">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">จำนวน</label>
                    <div class="col-sm-4">
                      <input type="text" name="quantity" class="form-control" required placeholder="quantity">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Barcode</label>
                    <div class="col-sm-4">
                      <input type="text" name="barcode" class="form-control" required placeholder="barcode">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">สถานะ</label>
                    <div class="col-sm-4">
                      <select name="stock_status" class="form-control" required>
                        <option value="" disabled>-- สถานะ --</option>
                        <option value="ใช้งานได้">ใช้งานได้</option>
                        <option value="ใช้งานไม่ได้">ใช้งานไม่ได้</option>
                      </select>
                    </div>
                  </div>
                
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">วันที่นำเข้า</label>
                    <div class="col-sm-4">
                    <input type="datetime-local" name="created_at" class="form-control" required placeholder="CreatedAt">
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
              <?php
            //   echo"<pre>";
            //   print_r($_POST);
            //   exit;
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php
if (isset($_POST['product_name']) && isset($_POST['category']) && isset($_POST['quantity']) && isset($_POST['stock_status']) && isset($_POST['created_at'])) {

    $product_name = $_POST['product_name'];
    $product_category = $_POST['category'];
    $product_quantity = $_POST['quantity'];
    $stock_status = $_POST['stock_status'];
    $created_at = $_POST['created_at'];
  // echo'<pre>';
  // print_r($_POST);
  // exit;

  // Check for duplicate product name
  $stmtProduct = $condb->prepare("SELECT product_name FROM products WHERE product_name = :product_name");
  $stmtProduct->bindParam(':product_name', $product_name, PDO::PARAM_STR);
  $stmtProduct->execute();

  $row = $stmtProduct->fetch(PDO::FETCH_ASSOC);

  if($stmtProduct->rowCount() == 1){
    echo '<script>
           setTimeout(function() {
            swal({
                title: "ชื่อสินค้า ซ้ำ",
                text: "กรุณาเพิ่มข้อมูลใหม่อีกครั้ง",
                type: "error"
            }, function() {
                window.location = "product.php?act=add"; //หน้าที่ต้องการให้กระโดดไป
            });
          }, 1000);
      </script>';
    } else {
    // Insert new product data
    $stmtProduct = $condb->prepare("INSERT INTO products
    (
      product_name,
      category,
      quantity,
      stock_status,
      created_at
    )
    VALUES 
    (
      :product_name,
      :category,
      :quantity,
      :stock_status,
      :created_at
    )");

    $stmtProduct->bindParam(':product_name', $product_name, PDO::PARAM_STR);
    $stmtProduct->bindParam(':category', $product_category, PDO::PARAM_STR);
    $stmtProduct->bindParam(':quantity', $product_quantity, PDO::PARAM_STR);
    $stmtProduct->bindParam(':stock_status', $stock_status, PDO::PARAM_STR);
    $stmtProduct->bindParam(':created_at', $created_at , PDO::PARAM_STR);
    $result = $stmtProduct->execute();

    $condb = null;
    if($result){
      // echo '<pre>';
      // print_r($_POST);
      // // exit();
      echo '<script>
           setTimeout(function() {
            swal({
                title: "เพิ่มข้อมูลสำเร็จ",
                type: "success"
            }, function() {
                window.location = "product.php"; //หน้าที่ต้องการให้กระโดดไป
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
                window.location = "product.php"; //หน้าที่ต้องการให้กระโดดไป
            });
          }, 1000);
      </script>';
    }
  }
}
?>

