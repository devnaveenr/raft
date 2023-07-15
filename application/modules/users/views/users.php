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
            <h1>Users   </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Home</a></li>
              <li class="breadcrumb-item active">Users</li>
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
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                  
                    <?php 
                    $i=1;
                    foreach($users as $user)
                    {
                    ?>
                  <tr id="user<?php echo $user->user_id;?>">
                    <td><?php echo $i;?></td>
                    <td><?php echo $user->name; ?></td>
                   
                    <td><?php echo $user->mobile; ?></td>
                    <td><?php echo $user->email; ?></td>
                    
                    <td>
                       <input type="checkbox" class="switch user_status" id="user_status" data-id="<?php echo $user->user_id;?>" <?php if($user->user_status==1) { ?> checked <?php } ?> />
                    </td>
                    <td>
                        <a href="javascript:void()" class="btn-danger" onclick="delete_user(<?php echo $user->user_id;?>)"><i class="fas fa-trash-alt"></i></a>
                        &nbsp;&nbsp; <a href="<?php echo base_url(); ?>users/view-user/<?php echo $user->user_id;?>" class="btn-info"><i class="fas fa-eye"></i></a>
                    </td>
                  </tr>
                  <?php 
                  $i++;
                  } ?>
                  
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>SNo</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
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
function delete_user(id) {
  if (confirm("you are about to delete a record. This cannot be undone. are you sure?")) {
     var id = id;
        $('#hide'+id).show();
        $.ajax({
            url: "<?php echo base_url(); ?>users/delete_user/"+id,
            type: 'get',
            success: function () {
          $('#user'+id).hide();
          
            window.location.href="<?php echo base_url(); ?>users/users";
            toastr.error('user Deleted Successfully')
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
    $(".user_status").bootstrapSwitch();
     

     $('.user_status').on('switchChange.bootstrapSwitch', function (e, data) {

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
              url: "<?php echo base_url(); ?>users/change_user_status",
              data: {id: id,status: status},
              success: function() {
              	if(status==1){
              		bootbox.alert("Course Status Changed to ON");
              	}else{
              		bootbox.alert("Course Status Changed to OFF");
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
