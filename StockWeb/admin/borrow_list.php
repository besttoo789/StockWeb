<?php
$queryBorrow = $condb->prepare("SELECT * FROM product_changes
");
$queryBorrow->execute();
$rsBorrow = $queryBorrow->fetchAll();
?>

<div class="content-wrapper">


  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <!-- /.card -->
          <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
            <table id="example1" class="table table-bordered table-striped table-sm">
            <thead class="thead-dark">
                <tr>
                    <th width="10%" class="text-center">ID.</th>
                    <th width="25%" class="text-center">ประเภทกรายการ</th>
                    <th width="20%" class="text-center">จำนวนเก่า</th>
                    <th width="10%" class="text-center">คงเหลือ</th>
                    <th width="10%" class="text-center">วันที่เเก้ไข</th>
                    <th width="10%" class="text-center">เเก้โดย</th>
                    <th width="10%" class="text-center">วันที่เพิ่ม</th>
                </tr>
                </thead>
                <tbody>   
                  <?php 
                  $i=1;
                  foreach ($rsBorrow as $row) { ?>
                <tr>
                  <td class="text-center"><?php echo $i++;?> </td>
                  <td class="text-center">
                  <span style="color: <?php echo $row['change_type'] =='ADD' ? 'green' : 'orange'; ?>">
                      <?php echo $row['change_type'] == 'ADD' ? 'เพิ่มเข้า' : 'อัพเดท'; ?>
                      
                    </span></td>
                  <td class="text-center"><?php echo $row['old_quantity'];?></td>
                  <td class="text-center"><?php echo $row['new_quantity'];?></td>
                  <td class="text-center"><?php echo $row['changed_at'];?></td>
                  <td class="text-center">
                    <span style="color: <?php echo $row['changed_by'] == 'admin' ? 'green' : 'red'; ?>">
                      <?php echo $row['changed_by'];?>
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
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
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