<?php
// query ข้อมูลสมาชิก
$queryPro = $condb->prepare("SELECT * FROM products");
$queryPro->execute();          
$rsPro = $queryPro->fetchAll();
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) --><section class="content-header">
    <div class="container-fluid">
      <div class="row mb-3 align-items-center">
        <div class="col-sm-6">
          <h1 class="m-0" style="color: #2c3e50; font-weight: 700; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">
          จัดการข้อมูลสินค้า
            <a href="productAdd.php" class="btn btn-primary shadow-sm ml-2">
              <i class="fas fa-plus mr-1"></i> จัดการข้อมูลสินค้า
            </a>
          </h1>
        </div>
      </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped table-sm">
                                <thead class="thead-dark">
                                <tr>
                                    <th width="5%" class="scope">ID.</th>
                                    <th width="30%" class="text-center">ชื่อสินค้า</th>
                                    <th width="20%" class="text-center">ประเภท</th>
                                    <th width="12%" class="text-center">จำนวน</th>
                                    <th width="8%" class="text-center">Barcode</th>
                                    <th width="10%" class="text-center">สถานะ</th>
                                    <th width="10%" class="text-center">เวลาเพิ่ม</th>
                                    <th width="10%" class="text-center">อัพเดทล่าสุด</th>
                                    <th width="10%" class="text-center">ลบ</th>
                                </tr>
                                </thead>
                                <tbody>   
                                    <?php 
                                    $i = 1;
                                    foreach ($rsPro as $row) { ?>
                                    <tr>
                                        <td align="center"><?php echo $i++; ?></td>
                                        <td align="center"><?=$row['product_name'];?></td>
                                        <td align="center"><?=$row['category'];?></td>
                                        <td align="center"><?=$row['quantity'];?></td>
                                        <td align="center"><?=$row['barcode'];?></td>
                                        <td align="center">
                                            <span style="color: <?= $row['stock_status'] == 'ใช้งานได้' ? 'green' : 'red'; ?>">
                                                <?=$row['stock_status'];?>
                                            </span>
                                        </td>
                                        <td align="center"><?=$row['created_at'];?></td>
                                        <td align="center"><?=$row['updated_at'];?></td>
                                        <td align="center">
                                            <a href="product.php?id=<?=$row['product_id'];?>&act=delete" 
                                               class="btn btn-danger btn-sm" 
                                               onclick="return confirm('ยืนยันการลบข้อมูล ?');">ลบ</a>
                                        </td>
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
<style>
  .content-wrapper {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
    padding: 10px;
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
    font-weight: 400;
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





  .btn-primary {
    background: #3498db;
    border: none;
    padding: 8px 15px;
    border-radius: 8px;
    transition: all 0.3s ease;
  }


</style>