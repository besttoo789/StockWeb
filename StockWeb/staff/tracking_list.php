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
            <h1 class="page-title">ระบบติดตามสถานะการเบิก-จ่าย</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
              <li class="breadcrumb-item active">ติดตามสถานะ</li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <!-- แถวแสดงสถิติ -->
        <div class="row">
          <div class="col-xl-4 col-md-6 mb-4">
            <div class="stats-card stats-card-pending">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="stats-card-title">รอการอนุมัติ</div>
                  <div class="stats-card-count">
                    <?php 
                      $pending_count = 0;
                      foreach ($rs as $row) {
                        if ($row['status'] == 'pending') $pending_count++;
                      }
                      echo $pending_count;
                    ?>
                  </div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-clock fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-xl-4 col-md-6 mb-4">
            <div class="stats-card stats-card-confirmed">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="stats-card-title">อนุมัติแล้ว</div>
                  <div class="stats-card-count">
                    <?php 
                      $confirmed_count = 0;
                      foreach ($rs as $row) {
                        if ($row['status'] == 'confirmed') $confirmed_count++;
                      }
                      echo $confirmed_count;
                    ?>
                  </div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-xl-4 col-md-6 mb-4">
            <div class="stats-card stats-card-rejected">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="stats-card-title">ไม่อนุมัติ</div>
                  <div class="stats-card-count">
                    <?php 
                      $rejected_count = 0;
                      foreach ($rs as $row) {
                        if ($row['status'] == 'rejected') $rejected_count++;
                      }
                      echo $rejected_count;
                    ?>
                  </div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- เพิ่มส่วนเลือก username -->
        <div class="form-select-container">
          <form method="GET" class="filter-form">
            <label for="username-select"><i class="fas fa-filter"></i> กรองข้อมูลตามผู้ใช้:</label>
            <select name="username" id="username-select" class="form-control" onchange="this.form.submit()">
              <option value="">- แสดงทั้งหมด -</option>
              <?php foreach ($users as $user) { ?>
                <option value="<?php echo $user['username']; ?>" 
                  <?php echo $selected_username == $user['username'] ? 'selected' : ''; ?>>
                  <?php echo $user['username']; ?>
                </option>
              <?php } ?>
            </select>
          </form>
        </div>

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-list mr-1"></i>
              รายการเบิก-จ่ายทั้งหมด
              <?php if (!empty($selected_username)) echo " - " . $selected_username; ?>
            </h3>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="reservation-table" class="table table-bordered table-striped table-hover">
                <thead>
                  <tr>
                    <th class="text-center">ลำดับ</th>
                    <th class="text-center">รหัสการเบิก-จ่าย</th>
                    <th class="text-center">ผู้ทำรายการ</th>
                    <th class="text-center">สินค้า</th>
                    <th class="text-center">จำนวน</th>
                    <th class="text-center">สถานะ</th>
                    <th class="text-center">วันที่ยืม</th>
                  </tr>
                </thead>
                <tbody>   
                  <?php 
                  $i = 1;
                  foreach ($rs as $row) {
                    // กำหนดคลาสสำหรับสถานะ
                    $status_class = '';
                    $status_text = '';
                    
                    if ($row['status'] == 'pending') {
                      $status_class = 'status-pending';
                      $status_text = 'รอการอนุมัติ';
                    } elseif ($row['status'] == 'confirmed') {
                      $status_class = 'status-confirmed';
                      $status_text = 'อนุมัติแล้ว';
                    } else {
                      $status_class = 'status-rejected';
                      $status_text = 'ไม่อนุมัติ';
                    }
                  ?>
                  <tr>
                    <td class="text-center"><?php echo $i++;?></td>
                    <td class="text-center"><?php echo $row['reserve_id'];?></td>
                    <td><?php echo $row['username'];?></td>
                    <td><?php echo $row['product_name'];?></td>
                    <td class="text-center"><?php echo $row['quantity'];?></td>
                    <td class="text-center">
                      <span class="status-badge <?php echo $status_class; ?>">
                        <?php echo $status_text; ?>
                      </span>
                    </td>
                    <td class="text-center"><?php echo date('d/m/Y', strtotime($row['created_at']));?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
</div>
<style>
  .content-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 15px 0;
    margin-bottom: 20px;
  }
  
  .page-title {
    font-weight: 600;
    color: #3c4b64;
    margin-bottom: 0;
    padding-left: 10px;
    border-left: 4px solid #4e73df;
  }
  
  .card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    border: none;
    border-radius: 0.35rem;
    margin-bottom: 30px;
  }
  
  .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e3e6f0;
    padding: 1rem 1.25rem;
  }
  
  .status-badge {
    padding: 6px 12px;
    border-radius: 30px;
    font-weight: 500;
    font-size: 0.85rem;
    display: inline-block;
    text-align: center;
    white-space: nowrap;
  }
  
  .status-pending {
    background-color: #fff3cd;
    color: #856404;
  }
  
  .status-confirmed {
    background-color: #d4edda;
    color: #155724;
  }
  
  .status-rejected {
    background-color: #f8d7da;
    color: #721c24;
  }
  
  .table-responsive {
    overflow-x: auto;
  }
  
  .form-select-container {
    background-color: white;
    padding: 20px;
    border-radius: 0.35rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
    margin-bottom: 20px;
  }
  
  .filter-form {
    display: flex;
    align-items: center;
  }
  
  .filter-form label {
    margin-right: 15px;
    margin-bottom: 0;
    font-weight: 500;
  }
  
  .filter-form select {
    border-radius: 0.25rem;
    height: calc(1.5em + 0.75rem + 2px);
    padding: 0.375rem 1.75rem 0.375rem 0.75rem;
  }
  
  /* เพิ่มสีสันและข้อมูลสถิติด้านบน */
  .stats-card {
    background-color: white;
    border-left: 4px solid;
    border-radius: 0.35rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
    margin-bottom: 20px;
    padding: 15px;
  }
  
  .stats-card-pending {
    border-left-color: #f6c23e;
  }
  
  .stats-card-confirmed {
    border-left-color: #1cc88a;
  }
  
  .stats-card-rejected {
    border-left-color: #e74a3b;
  }
  
  .stats-card-count {
    font-size: 1.5rem;
    font-weight: 700;
    color: #5a5c69;
  }
  
  .stats-card-title {
    text-transform: uppercase;
    font-size: 0.8rem;
    font-weight: 600;
    color: #b7b9cc;
  }
  
  /* ทำให้ตารางสวยงามมากขึ้น */
  .table th {
    background-color: #f8f9fa;
    vertical-align: middle;
    border-bottom: 2px solid #e3e6f0;
  }
  
  .table td {
    vertical-align: middle;
  }
</style>


