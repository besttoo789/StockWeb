<?php
// query ข้อมูลสมาชิก
$query = $condb->prepare("
    SELECT r.*, u.username 
    FROM reservations r 
    JOIN users u ON r.user_id = u.user_id
");

$query->execute();    
$rs = $query->fetchAll();
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>ประวัติการยืมวัสดุสำนักงาน
          </h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- /.card -->
            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-striped table-sm">
                  <thead>
                <tr>
                    <th width="5%" class="text-center">ID.</th>
                    <th width="28%" class="text-center" >รหัสการเบิก-จ่าย</th>
                    <th width="20%" class="text-center" >ผู้ทำรายการ</th>
                    <th width="7%" class="text-center" >เลขสินค้า</th>
                    <th width="5%" class="text-center" >จำนวน</th>
                    <th width="10%" class="text-center" >สถานะ</th>
                    <th width="10%" class="text-center" >วันที่ยืม</th>
                </tr>
                  </thead>
                  <tbody>   
                    <?php 
                    $i=1;
                    foreach ($rs as $row) {
                    ?>
                 <tr>
                  <td class="text-center"><?php echo $i++;?> </td>
                    <td class="text-center"><?php echo $row['reserve_id'];?></td>
                    <td class="text-center"><?php echo $row['username'];?></td>
                    <td class="text-center"><?php echo $row['product_id'];?></td>
                    <td class="text-center"><?php echo $row['quantity'];?></td>
                    <td align="center">
                    <a href="disbursement.php?id=<?=$row['status'];?>&act=edit" class="btn btn-success btn-sm">ตั้งค่า</a></td>
                    </td>
                    <td class="text-center"><?php echo $row['created_at'];?></td>
                  
                </tr>
                    <?php 
                    } 
                    ?>
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