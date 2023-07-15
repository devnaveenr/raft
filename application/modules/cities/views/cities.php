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
            <h1>Cities  &nbsp;&nbsp; <a href="<?php echo base_url();?>cities/add-city" class="btn btn-primary">Add city</a> </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Home</a></li>
              <li class="breadcrumb-item active">Cities</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            
           

             
            <div class="card">
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>SNo</th>
                    <th>City Name</th>
                    <th>City Priority</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                  
                    <?php 
                    $i=1;
                    foreach($cities as $city)
                    {
                    ?>
                  <tr id="city<?php echo $city->city_id;?>">
                    <td><?php echo $i;?></td>
                    <td><?php echo $city->city_name; ?></td>
                    <td><?php echo $city->city_priority; ?></td>
                    <td>
                       <input type="checkbox" class="switch city_status" id="city_status" data-id="<?php echo $city->city_id;?>" <?php if($city->city_status==1) { ?> checked <?php } ?> />
                    </td>
                    <td><a href="<?php echo base_url(); ?>cities/edit-city/<?php echo $city->city_id;?>" class="btn-primary"><i class="fas fa-edit"></i></a> &nbsp;&nbsp; <a href="javascript:void()" class="btn-danger" onclick="delete_city(<?php echo $city->city_id;?>)"><i class="fas fa-trash-alt"></i></a>
                     
                    </td>
                  </tr>
                  <?php 
                  $i++;
                  } ?>
                  
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>SNo</th>
                    <th>City Name</th>
                    <th>City Priority</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- Modal -->
<div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        Are you sure you want to change this?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary">Confirm</button>
      </div>
    </div>
  </div>
</div>
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
function delete_city(id) {
  if (confirm("you are about to delete a record. This cannot be undone. are you sure?")) {
     var id = id;
        $('#hide'+id).show();
        $.ajax({
            url: "<?php echo base_url(); ?>cities/delete_city/"+id,
            type: 'get',
            success: function () {
          $('#city'+id).hide();
          
            window.location.href="<?php echo base_url(); ?>cities";
            toastr.error('City Deleted Successfully')
            return false;
            },
            error: function () {
                alert('ajax failure');
                return false;
            }
        });
    } else {
        //alert(id + " not deleted");
    }
}
 $(function () {
    $(".city_status").bootstrapSwitch();
     

     $('.city_status').on('switchChange.bootstrapSwitch', function (e, data) {

       if($(this).prop('checked')) {
          status = 1;
          id = $(this).data("id");
      } else {
          status = 0;
          id = $(this).data("id"); 
      }
      if((status != '' || status != null) && (id !='')) {
          $.ajax({
              type: 'POST',
              url: "<?php echo base_url(); ?>cities/change_city_status",
              data: {id: id,status: status},
              success: function() {
              	if(status==1){
              		bootbox.alert("City Status Changed to ON");
              	}else{
              		bootbox.alert("City Status Changed to OFF");
              	}
                
              }
          });
      }  
    });
  })
</script>
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
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
