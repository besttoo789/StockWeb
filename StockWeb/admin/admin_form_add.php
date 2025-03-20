  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>เพิ่มข้อมูล Admin</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline card-info">
            <!-- /.card-header -->
            <div class="card-body">
              <div class="card card-primary">
                <!-- form start -->
                <form>
                  <div class="card-body">
                    <div class="form-group row">
                      <label class="col-sm-2">Username</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" require placeholder="Username" >
                      </div>
                    </div>
                  
                    <div class="form-group row">
                      <label class="col-sm-2">Password</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" require placeholder="Password" >
                      </div>
                    </div>
                  
                    <div class="form-group row">
                    <label class="col-sm-2 col-form-label"> ระดับผู้ใช้งาน </label>
                    <div class="col-sm-4" align="left">
                    <select name="personel_level" id="personel_level" class="form-control" required="">
                        <option value="">-เลือก-</option> 
                        <option value="M">บุคลากร</option>
                        <option value="A">ผู้ดูแลระบบ</option>
                        <option value="S">เจ้าหน้าที่พัสดุ</option>
                    </select>
                    </div>
                </div>
                <div class="form-group row">
                <label class="col-sm-2  col-form-label">หน่วยงาน</label>
                <div class="col-sm-4">
                <select name="institution_number" class="form-control" required="">
                    <option value="">-เลือก-</option> 
                            <option value="1">สำนักงาน</option>
                            <option value="2">แผนกการตลาด</option>
                            <option value="3">หน่วยไอที</option>
                            <option value="4">หน่วยยานพาหนะ</option>
                        </select>
                    </div>
                 </div>
                    <div class="form-group row">
                      <label class="col-sm-2">ชื่อพนักงาน</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" require placeholder="ชื่อ - นามสกุล" >
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2">เบอร์โทร</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" require placeholder="เบอร์โทร" >
                      </div>
                    </div>
                  </div><div class="form-group row">
                      <label class="col-sm-2"></label>
                      <div class="col-sm-4">
                        <!-- <button type="submit" class="btn btn-primary">submit</button> -->
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                        <a href="" class="btn btn-danger">ยกเลิก</a>
                      </div>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- /.col-->
      </div>
      <!-- ./row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->