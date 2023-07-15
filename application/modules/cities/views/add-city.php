<!DOCTYPE html>
<html lang="en">
<head>
  <!--  CSS Links Start -->
    <?php $this->load->view($css_links); ?>
    <!-- CSS Links End -->
</head>
<body class="hold-transition sidebar-mini">
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
            <h1>Add City</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Home</a></li>
              <li class="breadcrumb-item active">Add City</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-2">
          </div>
          <div class="col-md-8">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Cities</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="post" action="" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="form-group">
                    <label for="city_name">City Name</label>
                    <input type="text" class="form-control" id="city_name" name="city_name" placeholder="Enter Course Name" value="<?php echo set_value('city_name'); ?>">
                    <small class="text-danger"><?php echo form_error('city_name'); ?></small>
                  </div>
                   <div class="form-group">
                    <label for="city_priority">Priority</label>
                    <input type="text" class="form-control" id="city_priority" name="city_priority" placeholder="Enter Priority" value="<?php echo set_value('city_priority'); ?>">
                  </div>
                </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <a class="btn btn-default" href="javascript:history.back()">Cancel</a>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
          <div class="col-md-2">
          </div>
          
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
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
<!-- Page specific script -->
<script>
$(function () {
  $('.select2').select2();
});
</script>
<script>
  $(function () {
    // Summernote
    $('#category_desc').summernote()
  })
</script>
</body>
</html>
