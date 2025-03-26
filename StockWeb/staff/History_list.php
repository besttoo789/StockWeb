<?php
// รับค่า username ที่เลือกจาก dropdown (ถ้ามี)
$selected_username = isset($_GET['username']) ? $_GET['username'] : '';

// Query สำหรับประวัติการยืม
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

<style>
    .status-confirmed { color: #28a745; font-weight: bold; }
    .status-returned { color: #007bff; font-weight: bold; }
    .status-completed { color: #6c757d; font-weight: bold; }
    .card-header { background-color: #f8f9fa; }
    .table th { background-color: #e9ecef; }
    .select-wrapper { margin-bottom: 20px; }
    .table-responsive { overflow-x: auto; }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-history"></i> ประวัติการยืม</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row select-wrapper">
                <div class="col-md-4">
                    <form method="GET">
                        <div class="form-group">
                            <label class="font-weight-bold">เลือกผู้ใช้งาน:</label>
                            <div class="input-group">
                                <select name="username" class="form-control" onchange="this.form.submit()">
                                    <option value="">ทั้งหมด</option>
                                    <?php foreach ($users as $user) { ?>
                                        <option value="<?php echo htmlspecialchars($user['username']); ?>" 
                                            <?php echo $selected_username == $user['username'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($user['username']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title">รายการประวัติการยืม</h3>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="historyTable" class="table table-bordered table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th class="text-center">รหัสการยืม</th>
                                        <th class="text-center">ผู้ยืม</th>
                                        <th class="text-center">สินค้า</th>
                                        <th class="text-center">จำนวน</th>
                                        <th class="text-center">สถานะ</th>
                                        <th class="text-center">วันที่ยืม</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $i = 1;
                                    foreach ($rs as $row) {
                                        $status_class = 'status-' . $row['status'];
                                        $status_text = $row['status'] == 'confirmed' ? 'กำลังยืม' : 
                                                      ($row['status'] == 'returned' ? 'คืนแล้ว' : 'เสร็จสิ้น');
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++; ?></td>
                                        <td class="text-center"><?php echo htmlspecialchars($row['reserve_id']); ?></td>
                                        <td class="text-center"><?php echo htmlspecialchars($row['username']); ?></td>
                                        <td class="text-center"><?php echo htmlspecialchars($row['product_name']); ?></td>
                                        <td class="text-center"><?php echo $row['quantity']; ?></td>
                                        <td class="text-center">
                                            <span class="<?php echo $status_class; ?>">
                                                <?php echo $status_text; ?>
                                            </span>
                                        </td>
                                        <td class="text-center"><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
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

<script>
$(document).ready(function() {
    $('#historyTable').DataTable({
        "order": [[6, "desc"]],
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
        },
        "responsive": true,
        "pageLength": 10,
        "lengthMenu": [5, 10, 25, 50, 100]
    });
});
</script>