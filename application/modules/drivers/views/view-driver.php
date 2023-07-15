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
            <img src="<?php echo base_url();?><?php echo $driver['driver_profile_pic'];?>" style="width: 200px;height: 100px;">

            <div class="table-responsive">
              <table class="table">
                
                <tr>
                  <th width="25%">Mobile: </th>
                  <td><?php echo $driver['mobile'];?></td>

                  <th width="25%">Email: </th>
                  <td><?php echo $driver['email'];?></td>

                </tr>
                <tr>
                  <th width="25%">Owner Number: </th>
                  <td><?php echo $driver['owner_number'];?></td>
                  <th width="25%">city: </th>
                  <td><?php echo $driver['city_name'];?></td>
                </tr>
                <tr>
                  <th width="25%">Vehicle Type: </th>
                  <td><?php echo $driver['vehicle_type'];?></td>
                  <th width="25%">Driving Licence Number: </th>
                  <td><?php echo $driver['driving_licence_number'];?></td>
                </tr>
                <tr>
                  <th width="25%">Device ID: </th>
                  <td><?php echo $driver['device_id'];?></td>

                  

                </tr>
                <tr>
                <th width="25%">FCM Token: </th>
                  <td><?php echo $driver['fcm_token'];?></td>
                </tr>
                <tr>
                  <th width="25%">Aadhar Front: </th>
                  <td>
                    <?php
                      if($driver['aadhar_front']==NULL)
                      {
                        echo 'Not Uploaded';
                      }else{
                        ?>
                          <a href="<?php echo base_url();?><?php echo $driver['aadhar_front'];?>" download class="btn btn-info">Download</a>
                        <?php
                      }
                    ?>
                    
                  
                  </td>
                  <th width="25%">Aadhar Back: </th>
                  <td>
                  <?php
                      if($driver['aadhar_back']==NULL)
                      {
                        echo 'Not Uploaded';
                      }else{
                        ?>
                    <a href="<?php echo base_url();?><?php echo $driver['aadhar_back'];?>" download class="btn btn-info">Download</a>
                    <?php
                      }
                    ?>
                  </td>
                </tr>
                <tr>
                  <th width="25%">RC Front: </th>
                  <td>
                    <?php
                      if($driver['rc_front']==NULL)
                      {
                        echo 'Not Uploaded';
                      }else{
                    ?>
                    <a href="<?php echo base_url();?><?php echo $driver['rc_front'];?>" download class="btn btn-info">Download</a>
                    <?php
                      }
                    ?>
                  </td>
                  <th width="25%">RC Back: </th>
                  <td>
                    <?php
                      if($driver['rc_back']==NULL)
                      {
                        echo 'Not Uploaded';
                      }else{
                    ?>
                    <a href="<?php echo base_url();?><?php echo $driver['rc_back'];?>" download class="btn btn-info">Download</a>
                    <?php
                      }
                    ?>
                  </td>
                </tr>
                <tr>
                  <th width="25%">Driving Licence Front: </th>
                  <td>
                    <?php
                    if($driver['driving_licence_front']==NULL)
                      {
                        echo 'Not Uploaded';
                      }else{
                    ?>
                    <a href="<?php echo base_url();?><?php echo $driver['driving_licence_front'];?>" download class="btn btn-info">Download</a>
                    <?php
                      }
                    ?>
                  </td>
                  <th width="25%">Driving Licence Back: </th>
                  <td>
                  <?php
                    if($driver['driving_licence_back']==NULL)
                      {
                        echo 'Not Uploaded';
                      }else{
                    ?>
                    <a href="<?php echo base_url();?><?php echo $driver['driving_licence_back'];?>" download class="btn btn-info">Download</a>
                    <?php
                      }
                    ?>
                  </td>
                </tr>
                <tr>
                  <th>Document Status</th>
                  <td>
                  <!--<input type="checkbox" class="switch documents_status" id="documents_status" data-id="<?php echo $driver['user_id'];?>" <?php //if($driver['documents_status']==1) { ?> checked <?php //} ?> />-->
                  <!-- <select name="document_status" id="document_status" class="form-control">
                      <option value="">Select Status</option>
                      <option value="0">Intial Status</option>
                      <option value="1">Re Upload</option>
                      <option value="2">Approved</option>
                  </select> -->
                  <button class="btn <?php if($driver['documents_status']==0) { ?> btn-success <?php } else { ?>  btn-outline-success <?php } ?> docstatus" data-id="0">Intial Status</button>
                  <button class="btn <?php if($driver['documents_status']==1) { ?> btn-success <?php } else { ?>  btn-outline-success <?php } ?> docstatus" data-id="1">Re Upload</button>
                  <button class="btn <?php if($driver['documents_status']==2) { ?> btn-success <?php } else { ?>  btn-outline-success <?php } ?> docstatus" data-id="2">Approved</button>
                  <input type="hidden" name="user_id" id="user_id" value="<?php echo $driver['user_id']; ?>">
                  </td>
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
<script>

  $( document ).ready(function() {
    $('.docstatus').click(function() {
     
     var val = $(this).attr("data-id");
    
     var user_id = $("#user_id").val();

     $.ajax({
              type: 'POST',
              url: "<?php echo base_url(); ?>drivers/change_documents_status",
              data: {val: val,user_id: user_id},
              success: function() {
              	if(status==1){
              		bootbox.alert("Document Status Changed to Reupload");
                  window.location.href="<?php echo base_url();?>drivers/view-driver/"+user_id;
              	}else if(status==2){
              		bootbox.alert("Document Status Changed to Approved");
                  window.location.href="<?php echo base_url();?>drivers/view-driver/"+user_id;
              	}else{
                  bootbox.alert("Document Status Changed to Intial Screen");
                  window.location.href="<?php echo base_url();?>drivers/view-driver/"+user_id;
                }
                
              }
          });
     });
});

</script>
</body>
</html>
