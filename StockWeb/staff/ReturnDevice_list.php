<?php
// query ข้อมูลสมาชิก
$query = $condb->prepare("
    SELECT r.*, u.username , p.product_name 
    FROM reservations r 
    JOIN users u ON r.user_id = u.user_id
    JOIN products p ON r.product_id = p.product_id
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
                    <th width="23%" class="text-center" >รหัสการเบิก-จ่าย</th>
                    <th width="15%" class="text-center" >ผู้ทำรายการ</th>
                    <th width="7%" class="text-center" >เลขสินค้า</th>
                    <th width="5%" class="text-center" >จำนวน</th>
                    <th width="10%" class="text-center" >สถานะ</th>
                    <th width="10%" class="text-center" >การอนุมัติ</th>
                    <th width="10%" class="text-center" >วันที่ยืม</th>
                </tr>
                  </thead>
                  <tbody>   
                    <?php 
                    $i=1;
                    foreach ($rs as $row) {
                    ?>
                 <tr>
                 <!-- <tr>
                    <td align="center"><?php echo $i++;?> </td>
                    <td><?=$row['reserve_id'];?></td>
                    <td><?=$row['username'];?></td>
                    <td><?=$row['m_level'];?></td>
                    <td align="center">
                    <a href="member.php?id=<?=$row['id'];?>&act=editPwd" class="btn btn-info btn-sm">แก้รหัส</a></td>
                    <td align="center">
                    <a href="member.php?id=<?=$row['id'];?>&act=edit" class="btn btn-success btn-sm">แก้ไข</a></td>
                    <td align="center">
                    <a href="member.php?id=<?=$row['id'];?>&act=delete" class="btn btn-danger btn-sm" onclick="return confirm('ยืนยันการลบข้อมูล ?');">ลบ</a>
                  </td> -->



                 
                  <td class="text-center"><?php echo $i++;?> </td>
                    <td class="text-center"><?php echo $row['reserve_id'];?></td>
                    <td class="text-center"><?php echo $row['username'];?></td>
                    <td class="text-center"><?php echo $row['product_name'];?></td>
                    <td class="text-center"><?php echo $row['quantity'];?></td>
                    <td class="text-center">
                      <span style="color: <?php echo $row['status'] == 'pending' ? 'orange' : ($row['status'] == 'confirmed' ? 'green' : 'red'); ?>"> 
                     <?php echo $row['status'] == 'pending' ? 'รอการอนุมัติ' : ($row['status'] == 'confirmed' ? 'อนุมัติเเล้ว' : 'ไม่อนุมัติ'); ?>
                    </span></td>
                    <td align="center">
                      <a href="disbursement.php?id=<?=$row['reserve_id'];?>&act=edit" class="btn btn-success btn-sm">อนุมัติ</a></td>
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