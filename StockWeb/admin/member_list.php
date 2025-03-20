<?php
// query ข้อมูลสมาชิกพร้อมข้อมูล roles
$queryMember = $condb->prepare("
    SELECT u.*, r.role_name 
    FROM users u 
    LEFT JOIN roles r ON u.role_id = r.role_id
"); 
$queryMember->execute();
$rsMember = $queryMember->fetchAll();
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-3 align-items-center">
        <div class="col-sm-6">
          <h1 class="m-0" style="color: #2c3e50; font-weight: 700; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">
            จัดการข้อมูลสมาชิก
            <a href="member.php?act=add" class="btn btn-primary shadow-sm ml-2">
              <i class="fas fa-plus mr-1"></i> เพิ่มข้อมูล
            </a>
          </h1>
        </div>
      </div>
    </div>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card shadow-lg">
            <div class="card-body p-4">
              <table id="example_1" class="table table-bordered table-striped table-hover table-sm">
                <thead class="thead-dark">
                  <tr>
                    <th width="5%" class="text-center">ID</th>
                    <th width="30%" class="text-center">Username</th>
                    <th width="5%" class="text-center">Email</th>
                    <th width="5%" class="text-center">เบอร์โทร</th>
                    <th width="5%" class="text-center">ระดับ</th>
                    <th width="7%" class="text-center">รหัส</th>
                    <th width="5%" class="text-center">แก้ไข</th>
                    <th width="5%" class="text-center">ลบ</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $i = 1;
                  foreach ($rsMember as $row) { ?>
                  <tr class="table-row">
                    <td align="center"><?php echo $i++; ?></td>
                    <td><?=$row['username'];?></td>
                    <td><?=$row['email'];?></td>
                    <td><?=$row['phone'];?></td>
                    <td align="center">
                      <span class="badge <?php echo $row['role_name'] == 'Admin' ? 'badge-success' : 'badge-info'; ?>">
                        <?=$row['role_name'];?>
                      </span>
                    </td>
                    <td align="center">
                      <a href="member.php?id=<?=$row['user_id'];?>&act=editPwd" class="btn btn-info btn-sm btn-action">
                        <i class="fas fa-key mr-1"></i> 
                      </a>
                    </td>
                    <td align="center">
                      <a href="member.php?id=<?=$row['user_id'];?>&act=edit" class="btn btn-success btn-sm btn-action">
                        <i class="fas fa-edit"></i>
                      </a>
                    </td>
                    <td align="center">
                      <a href="member.php?id=<?=$row['user_id'];?>&act=delete" class="btn btn-danger btn-sm btn-action" 
                         onclick="return confirm('ยืนยันการลบข้อมูล ?');">
                        <i class="fas fa-trash"></i>
                      </a>
                    </td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Custom CSS -->
<style>

  .content-wrapper {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
    padding: 20px;
  }

  .card {
    border-radius: 15px;
    border: none;
    background: #ffffff;
    transition: all 0.3s ease;
  }

  .card:hover {
    box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important;
  }

  .thead-dark {
    background: #2c3e50;
    color: #fff;
    font-weight: 600;
  }

  .thead-dark th {
    border: none;
    padding: 15px;
  }

  .table {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
  }

  .table-row:hover {
    background: #f8f9fa;
    transition: all 0.3s ease;
  }

  .table td {
    vertical-align: middle;
    padding: 12px;
  }

  .btn-action {
    padding: 5px 10px;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-weight: 500;
  }

  .btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 10px rgba(0,0,0,0.15);
  }

  .btn-primary {
    background: #3498db;
    border: none;
    padding: 8px 15px;
    border-radius: 8px;
    transition: all 0.3s ease;
  }

  .btn-primary:hover {
    background: #2980b9;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
  }

  .badge {
    padding: 5px 10px;
    font-size: 0.9em;
    border-radius: 12px;
  }

  .badge-success {
    background: #2ecc71;
  }

  .badge-info {
    background: #17a2b8;
  }
</style>
