<?php
  
include 'header.php';
include 'navbar.php';
include 'sidebar_menu.php';
$act = (isset($_GET['act']) ? $_GET['act'] : '');
if($act == 'edit'){
include 'disbursement_edit.php';
}else {
include 'disbursement_list.php';
}
include 'footer.php';
?>
