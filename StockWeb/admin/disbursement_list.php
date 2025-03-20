<?php
// เตรียม query ด้วย PDO และป้องกัน SQL Injection
try {
    $query = $condb->prepare("
        SELECT r.*, u.username, p.product_name 
        FROM reservations r 
        INNER JOIN users u ON r.user_id = u.user_id
        INNER JOIN products p ON r.product_id = p.product_id
    ");
    $query->execute();
    $rs = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: " . $e->getMessage());
}

// ฟังก์ชันช่วยแปลงสถานะเป็นข้อความภาษาไทยและกำหนดสี
function getStatusDisplay($status) {
    $statusMap = [
        'pending' => ['text' => 'รอดำเนินการ', 'class' => 'badge-warning'],
        'confirmed' => ['text' => 'อนุมัติ', 'class' => 'badge-success'],
        'cancelled' => ['text' => 'ไม่อนุมัติ', 'class' => 'badge-danger'],
        'returned' => ['text' => 'คืนสำเร็จ', 'class' => 'badge-info']
    ];
    
    return $statusMap[$status] ?? ['text' => 'ไม่ทราบสถานะ', 'class' => 'badge-secondary'];
}
?>

<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">การอนุมัติการใช้งาน</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table id="example2" class="table table-bordered table-striped table-hover table-sm">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-center" style="width: 5%">ID</th>
                                            <th class="text-center" style="width: 25%">รหัสการเบิก-จ่าย</th>
                                            <th class="text-center" style="width: 20%">ผู้ทำรายการ</th>
                                            <th class="text-center" style="width: 17%">ชื่อสินค้า</th>
                                            <th class="text-center" style="width: 8%">จำนวน</th>
                                            <th class="text-center" style="width: 15%">สถานะ</th>
                                            <th class="text-center" style="width: 10%">วันที่ยืม</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if (count($rs) > 0) {
                                            $i = 1;
                                            foreach ($rs as $row) {
                                                $reserve_id = htmlspecialchars($row['reserve_id'], ENT_QUOTES, 'UTF-8');
                                                $username = htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8');
                                                $product_name = htmlspecialchars($row['product_name'], ENT_QUOTES, 'UTF-8');
                                                $quantity = htmlspecialchars($row['quantity'], ENT_QUOTES, 'UTF-8');
                                                $created_at = htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8');
                                                $statusDisplay = getStatusDisplay($row['status']);
                                        ?>
                                        <tr>
                                            <td class="text-center align-middle"><?php echo $i++; ?></td>
                                            <td class="text-center align-middle"><?php echo $reserve_id; ?></td>
                                            <td class="text-center align-middle"><?php echo $username; ?></td>
                                            <td class="text-center align-middle"><?php echo $product_name; ?></td>
                                            <td class="text-center align-middle"><?php echo $quantity; ?></td>
                                            <td class="text-center align-middle">
                                                <span class="badge <?php echo $statusDisplay['class']; ?> p-2">
                                                    <?php echo $statusDisplay['text']; ?>
                                                </span>
                                                <a href="disbursement.php?id=<?php echo urlencode($reserve_id); ?>&act=edit" 
                                                   class="btn btn-outline-primary mt-1">
                                                    <i class="fas fa-edit"></i> แก้ไข
                                                </a>
                                            </td>
                                            <td class="text-center align-middle"><?php echo $created_at; ?></td>
                                        </tr>
                                        <?php 
                                            }
                                        } else {
                                        ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-3">
                                                ไม่พบข้อมูลการจอง
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
        </div>
    </section>
</div>

<!-- CSS เพื่อความสวยงาม -->
<style>
    .card {
        border-radius: 10px;
        overflow: hidden;
    }
    .table {
        margin-bottom: 0;
    }
    .table th {
        background-color: #343a40;
        color: white;
        font-weight: 600;
    }
    .table td {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.9em;
        padding: 0.5em 1em;
        display: inline-block;
        width: 100px;
        margin-bottom: 5px;
    }
    .btn-primary {
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
</style>

<!-- เพิ่มการเชื่อมต่อ Font Awesome และ Bootstrap (ถ้ายังไม่มี) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>