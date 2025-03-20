<?php
// รับค่า username ที่เลือกจาก dropdown (ถ้ามี)
$selected_username = isset($_GET['username']) ? $_GET['username'] : '';

// Query สำหรับประวัติการยืม (แสดงเฉพาะที่ confirmed หรือ completed)
$sql = "
    SELECT r.*, u.username, p.product_name 
    FROM reservations r 
    JOIN users u ON r.user_id = u.user_id
    JOIN products p ON r.product_id = p.product_id
    WHERE r.status IN ('confirmed', 'completed', 'returned')
";
if (!empty($selected_username)) {
    $sql .= " AND u.username = :username";
}
$sql .= " ORDER BY r.created_at DESC";

$query = $condb->prepare($sql);
if (!empty($selected_username)) {
    $query->bindParam(':username', $selected_username);
}
$query->execute();    
$rs = $query->fetchAll();

// Query สำหรับ dropdown username
$user_query = $condb->prepare("SELECT DISTINCT username FROM users ORDER BY username");
$user_query->execute();
$users = $user_query->fetchAll();
?>

<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>ประวัติการยืม</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Dropdown เลือก username -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <form method="GET">
                        <div class="form-group">
                            <label>เลือกผู้ใช้งาน:</label>
                            <select name="username" class="form-control" onchange="this.form.submit()">
                                <option value="">ทั้งหมด</option>
                                <?php foreach ($users as $user) { ?>
                                    <option value="<?php echo $user['username']; ?>" 
                                        <?php echo $selected_username == $user['username'] ? 'selected' : ''; ?>>
                                        <?php echo $user['username']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="historyTable" class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center">ID</th>
                                        <th width="20%" class="text-center">รหัสการยืม</th>
                                        <th width="20%" class="text-center">ผู้ยืม</th>
                                        <th width="15%" class="text-center">สินค้า</th>
                                        <th width="5%" class="text-center">จำนวน</th>
                                        <th width="15%" class="text-center">สถานะ</th>
                                        <th width="10%" class="text-center">วันที่ยืม</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $i = 1;
                                    foreach ($rs as $row) {
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++; ?></td>
                                        <td class="text-center"><?php echo $row['reserve_id']; ?></td>
                                        <td class="text-center"><?php echo $row['username']; ?></td>
                                        <td class="text-center"><?php echo $row['product_name']; ?></td>
                                        <td class="text-center"><?php echo $row['quantity']; ?></td>
                                        <td class="text-center">
                                            <span style="color: <?php 
                                                echo $row['status'] == 'confirmed' ? 'green' : 
                                                    ($row['status'] == 'returned' ? 'blue' : 'gray'); ?>">
                                                <?php 
                                                echo $row['status'] == 'confirmed' ? 'กำลังยืม' : 
                                                    ($row['status'] == 'returned' ? 'คืนแล้ว' : 'เสร็จสิ้น'); 
                                                ?>
                                            </span>
                                        </td>
                                        <td class="text-center"><?php echo $row['created_at']; ?></td>
                                        
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

<!-- ถ้าใช้ DataTables อาจเพิ่ม script นี้ -->
<script>
$(document).ready(function() {
    $('#historyTable').DataTable({
        "order": [[6, "desc"]], // เรียงตามวันที่ยืมล่าสุดก่อน
        "language": {
            "lengthMenu": "แสดง _MENU_ รายการต่อหน้า",
            "zeroRecords": "ไม่พบข้อมูล",
            "info": "หน้า _PAGE_ จาก _PAGES_",
            "infoEmpty": "ไม่มีข้อมูล",
            "infoFiltered": "(กรองจากทั้งหมด _MAX_ รายการ)",
            "search": "ค้นหา:",
            "paginate": {
                "first": "แรก",
                "last": "สุดท้าย",
                "next": "ถัดไป",
                "previous": "ก่อนหน้า"
            }
        }
    });
});
</script>