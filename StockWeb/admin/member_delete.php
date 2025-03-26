<?php
if (isset($_GET['id']) && isset($_GET['act']) && $_GET['act'] == 'delete') {
    try {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        
        // เปลี่ยนจาก 'id' เป็น 'user_id' หรือชื่อคอลัมน์จริงในตารางของคุณ
        $stmtDelmember = $condb->prepare('DELETE FROM users WHERE user_id = :user_id');
        $stmtDelmember->bindParam(':user_id', $id, PDO::PARAM_INT);
        $stmtDelmember->execute();

        // Close database connection
        $condb = null;

        if ($stmtDelmember->rowCount() >= 1) {
            echo '<script>
                setTimeout(function() {
                    swal({
                        title: "ลบข้อมูลสำเร็จ",
                        type: "success"
                    }, function() {
                        window.location = "member.php";
                    });
                }, 1000);
            </script>';
        } else {
            throw new Exception("ไม่พบข้อมูลที่ต้องการลบ");
        }
    } catch (Exception $e) {
        $condb = null;
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "เกิดข้อผิดพลาด",
                    text: "' . addslashes($e->getMessage()) . '",
                    type: "error"
                }, function() {
                    window.location = "member.php";
                });
            }, 1000);
        </script>';
    }
    exit();
}
?>