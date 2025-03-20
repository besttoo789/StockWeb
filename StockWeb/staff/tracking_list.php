<?php
// รับค่า username ที่เลือกจาก dropdown (ถ้ามี)
$selected_username = isset($_GET['username']) ? $_GET['username'] : '';

// สร้าง query โดยเพิ่มเงื่อนไข username ถ้ามีการเลือก
$sql = "
    SELECT r.*, u.username, p.product_name 
    FROM reservations r 
    JOIN users u ON r.user_id = u.user_id
    JOIN products p ON r.product_id = p.product_id
";
if (!empty($selected_username)) {
    $sql .= " WHERE u.username = :username";
}

$query = $condb->prepare($sql);

// ถ้ามีการเลือก username ให้ bind parameter
if (!empty($selected_username)) {
    $query->bindParam(':username', $selected_username);
}

$query->execute();    
$rs = $query->fetchAll();

// Query เพื่อดึงรายชื่อ username ทั้งหมดสำหรับ dropdown
$user_query = $condb->prepare("SELECT DISTINCT username FROM users ORDER BY username");
$user_query->execute();
$users = $user_query->fetchAll();
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>ติดตามสถานะ</h1>
          </div>
        </div>
      </div>
    </section>
    
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- เพิ่มส่วนเลือก username -->
        <div class="row mb-3">
          <div class="col-md-4">
            <form method="GET">
              <div class="form-group">
                <label>เลือกผู้ใช้งาน:</label>
                <select name="username" class="form-control" onchange="this.form.submit()">
                  <option value="">ทั้งหมด</option>
                  <?php foreach ($users as $user) { ?>
                    <option value="<?php echo $user['username']; ?>" 
                      <?php echo $selected_username == $user['username'] ? 'selected' : ''; ?>>
                      <?php echo $user['username']; ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
            </form>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <table id="example2" class="table table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th width="5%" class="text-center">ID.</th>
                      <th width="23%" class="text-center">รหัสการเบิก-จ่าย</th>
                      <th width="20%" class="text-center">ผู้ทำรายการ</th>
                      <th width="7%" class="text-center">เลขสินค้า</th>
                      <th width="5%" class="text-center">จำนวน</th>
                      <th width="15%" class="text-center">สถานะ</th>
                      <th width="10%" class="text-center">วันที่ยืม</th>
                    </tr>
                  </thead>
                  <tbody>   
                    <?php 
                    $i = 1;
                    foreach ($rs as $row) {
                    ?>
                    <tr>
                      <td class="text-center"><?php echo $i++;?></td>
                      <td class="text-center"><?php echo $row['reserve_id'];?></td>
                      <td class="text-center"><?php echo $row['username'];?></td>
                      <td class="text-center"><?php echo $row['product_name'];?></td>
                      <td class="text-center"><?php echo $row['quantity'];?></td>
                      <td class="text-center">
                        <span style="color: <?php echo $row['status'] == 'pending' ? 'orange' : ($row['status'] == 'confirmed' ? 'green' : 'red'); ?>"> 
                          <?php echo $row['status'] == 'pending' ? 'รอการอนุมัติ' : ($row['status'] == 'confirmed' ? 'อนุมัติแล้ว' : 'ไม่อนุมัติ'); ?>
                        </span>
                      </td>
                      <td class="text-center"><?php echo $row['created_at'];?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
</div>