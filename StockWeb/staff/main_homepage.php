<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-4">
        <div class="col-sm-12 text-center">
          <h1 class="m-0" style="color: #2c3e50; font-weight: 700; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">เลือกข้อมูลการทำงาน</h1>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-md-4 col-sm-6 mb-4">
          <a href="disbursement.php" class="gap_fl_btn btn btn-float btn-outline-success w-100 shadow-sm">
            <i class="nav-icon fas fa-box fa-2x"></i>
            <span class="btn-text">เบิกวัสดุสำนักงาน</span>
          </a>
        </div>

        <div class="col-md-4 col-sm-6 mb-4">
          <a href="tracking.php" class="gap_fl_btn btn btn-float btn-outline-success w-100 shadow-sm">
            <i class="nav-icon fas fa-truck fa-2x"></i>
            <span class="btn-text">ติดตามสถานะ</span>
          </a>
        </div>

        <div class="col-md-4 col-sm-6 mb-4">
          <a href="History.php" class="gap_fl_btn btn btn-float btn-outline-info w-100 shadow-sm">
            <i class="nav-icon fas fa-history fa-2x"></i>
            <span class="btn-text">ดูประวัติ</span>
          </a>
        </div>

        <div class="col-md-4 col-sm-6 mb-4">
          <a href="return.php" class="gap_fl_btn btn btn-float btn-outline-info w-100 shadow-sm">
            <i class="nav-icon fas fa-undo fa-2x"></i>
            <span class="btn-text">คืนวัสดุสำนักงาน</span>
          </a>
        </div>

        <div class="col-md-4 col-sm-6 mb-4">
          <a href="../logout.php" class="gap_fl_btn btn btn-float btn-outline-dark w-100 shadow-sm">
            <i class="nav-icon fas fa-sign-out-alt fa-2x"></i>
            <span class="btn-text">ออกจากระบบ</span>
          </a>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Enhanced CSS for styling -->
<style>
  
  .btn-float {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 20px;
    border-width: 2px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    background: #fff;
    border-radius: 15px;
    height: 140px;
  }

  .btn-float:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.2);
    border-color: transparent;
  }

  .gap_fl_btn {
    border-radius: 15px;
  }

  .fa-2x {
    font-size: 2.5em;
    margin-bottom: 10px;
    color: #2c3e50;
    transition: all 0.3s ease;
  }

  .btn-text {
    font-size: 18px;
    font-weight: 600;
    color: #34495e;
    transition: all 0.3s ease;
  }

  .btn-outline-success { border-color: #2ecc71; }
  .btn-outline-info { border-color: #17a2b8; }
  .btn-outline-dark { border-color: #2c3e50; }

  .btn-outline-success:hover { background: #2ecc71; }
  .btn-outline-info:hover { background: #17a2b8; }
  .btn-outline-dark:hover { background: #2c3e50; }

  .btn-float:hover .fa-2x,
  .btn-float:hover .btn-text {
    color: #fff;
  }

  .shadow-sm {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  }

  .col-md-4 {
    padding: 0 15px;
  }

  @media (max-width: 768px) {
    .btn-float {
      height: 120px;
    }
    .btn-text {
      font-size: 16px;
    }
    .fa-2x {
      font-size: 2em;
    }
  }
</style>