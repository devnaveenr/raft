<!DOCTYPE html>
<html lang="en">
<head>
  <!--  CSS Links Start -->
    <?php $this->load->view($css_links); ?>
  <!-- CSS Links End -->
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
<?php $this->load->view($header); ?>
    <?php $this->load->view($sidebar); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active"></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-body">
          <div class="col-12">
            <p class="lead"><b><?php echo $driver['name']; ?></b></p>

            <div class="table-responsive">
              <table class="table">
                
                <tr>
                  <th width="25%">Mobile: </th>
                  <td><?php echo $driver['mobile'];?></td>

                  <th width="25%">Email: </th>
                  <td><?php echo $driver['email'];?></td>

                </tr>


                
                
              </table>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <a class="btn btn-default" href="javascript:history.back()">Back</a>
        </div>
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php $this->load->view($footer); ?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<?php $this->load->view($js_links); ?>
</body>
</html>
