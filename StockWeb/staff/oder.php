<?php
  
include 'header.php';
include 'navbar.php';
include 'sidebar_menu.php';
$act = (isset($_GET['act']) ? $_GET['act'] : '');
if($act == 'edit'){
include 'oder_edit.php';
}else {
include 'oder_list.php';
}
include 'footer.php';
?>
