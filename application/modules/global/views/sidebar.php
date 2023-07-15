<aside class="main-sidebar sidebar-dark-primary elevation-4" >
    <!-- Brand Logo -->
    <?php
      $CI =& get_instance();  
      $data['title'] = 'Edit Settings';		
			$params = array();
			$params['select'] = "t1.*"; 
			$params['table'] = 'settings t1';
			$where = "";
			$where = "t1.settings_id=1";
			$params['where'] = $where;
			$params['output'] = 'row_array';              
      $settings = $CI->crud_model->getReports($params);
      
    ?>
    <a href="<?php echo base_url();?>admin/dashboard" class="brand-link" style="background:#1c91d0;">
      <img src="<?php echo base_url();?><?php echo $settings['logo'];?>" style="width:240px;">
      
    </a>

    <!-- Sidebar -->
    <div class="sidebar" >
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo base_url();?>assets/backend/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Admin</a>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="<?php echo base_url();?>admin/dashboard" class="nav-link <?php if($this->uri->segment(2) == 'dashboard') { ?> active <?php } ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url();?>users/users" class="nav-link <?php if($this->uri->segment(2) == 'users' ) { ?> active <?php } ?>">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Users
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url();?>drivers" class="nav-link <?php if($this->uri->segment(1) == 'drivers' ) { ?> active <?php } ?>">
              <i class="nav-icon fas fa-id-card"></i>
              <p>
                Drivers
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url();?>settings/index/1" class="nav-link <?php if($this->uri->segment(1) == 'settings') { ?> active <?php } ?>">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Settings
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url();?>cities" class="nav-link <?php if($this->uri->segment(1) == 'cities') { ?> active <?php } ?>">
              <i class="nav-icon fas fa-city"></i>
              <p>
                Cities
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url();?>vehicletypes" class="nav-link <?php if($this->uri->segment(1) == 'vehicletypes') { ?> active <?php } ?>">
              <i class="nav-icon fas fa-car"></i>
              <p>
                Vehicle Types
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url();?>admin/login/logout" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Log Out
              </p>
            </a>
          </li>
        </ul>
      </nav>
      
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>