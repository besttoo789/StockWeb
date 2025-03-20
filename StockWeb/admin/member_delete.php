<?php
if(isset($_GET['id']) && $_GET['act'] == 'delete') {
    $id = $_GET['id'];
    // echo $id;
$stmtDelmember = $condb->prepare('DELETE FROM tbl_member WHERE id=:id');
$stmtDelmember->bindParam(':id', $id , PDO::PARAM_INT);
$stmtDelmember->execute();
 $condb = null;

if($stmtDelmember->rowCount() ==1){
    echo '<script>
         setTimeout(function() {
          swal({
              title: "ลบข้อมูลสำเร็จ",
              type: "success"
          }, function() {
              window.location = "member.php"; //หน้าที่ต้องการให้กระโดดไป
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
              window.location = "member.php"; //หน้าที่ต้องการให้กระโดดไป
          });
        }, 1000);
    </script>';
    }
}   //isset

?>