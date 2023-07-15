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
            <h1>Edit Settings</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Home</a></li>
              <li class="breadcrumb-item active">Edit Settings</li>
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
                <h3 class="card-title">Settings</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="post" action="" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="form-group">
                    <label for="site_name">Site Name</label>
                    <input type="text" class="form-control" id="site_name" name="site_name" placeholder="Enter Site Name" value="<?php echo $editdata['site_name']; ?>">
                    <small class="text-danger"><?php echo form_error('site_name'); ?></small>
                  </div>
                  <div class="form-group">
                    <label for="contact_no">Contact No</label>
                    <input type="text" class="form-control" id="contact_no" name="contact_no" placeholder="Enter Contact Number" value="<?php echo $editdata['contact_no']; ?>">
                    <small class="text-danger"><?php echo form_error('contact_no'); ?></small>
                  </div>
                  <div class="form-group">
                    <label for="company_email">Company Email</label>
                    <input type="text" class="form-control" id="company_email" name="company_email" placeholder="Enter Company Email" value="<?php echo $editdata['company_email']; ?>">
                    <small class="text-danger"><?php echo form_error('company_email'); ?></small>
                  </div>
                  <div class="form-group">
                    <label for="address">Address</label>
                    <textarea  class="form-control" id="address" name="address" placeholder="Enter Course Description" rows="6"><?php echo $editdata['address']; ?></textarea>
                  </div>
                  
                  <div class="form-group">
                    <label for="exampleInputFile">Logo</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" id="logo" name="logo">
                        <input type="hidden" name="logo_image_edit" value="<?php echo $editdata['logo']; ?>">
                      </div>
                      <img src="<?php echo base_url();?><?php echo $editdata['logo']  ?>" style="width:300px">
                  </div>
                  <small class="text-danger"><?php echo form_error('logo'); ?></small>
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
    $('#address').summernote()
  })
</script>
<?php if($this->session->flashdata('message_name')){ 
$message_name = $this->session->flashdata('message_name');
?>
  <script type="text/javascript">
  toastr.info('<?php echo $message_name ?>')
  </script>
<?php 
unset($_SESSION['message_name']);
} ?>
</body>
</html>
