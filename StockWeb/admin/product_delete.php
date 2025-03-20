<?php
if(isset($_GET['id']) && $_GET['act'] == 'delete') {
    $id = $_GET['id'];
    // echo $id;
$stmtDelpro = $condb->prepare('DELETE FROM products WHERE product_id=:id');
$stmtDelpro->bindParam(':id', $id , PDO::PARAM_INT);
$stmtDelpro->execute();
 $condb = null;

if($stmtDelpro->rowCount() ==1){
    echo '<script>
         setTimeout(function() {
          swal({
              title: "ลบข้อมูลสำเร็จ",
              type: "success"
          }, function() {
              window.location = "product.php"; //หน้าที่ต้องการให้กระโดดไป
          });
        }, 1000);
    </script>';
    exit();
}else{
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
}   //isset

?>