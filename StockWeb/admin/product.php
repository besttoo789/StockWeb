<?php
include 'header.php';
include 'navbar.php';
include 'sidebar_menu.php';
$act = (isset($_GET['act']) ? $_GET['act'] : '');
 if($act == 'delete'){
    include 'product_delete.php';
}else 
 {
    include 'product_list.php';
}
include 'footer.php';
?>