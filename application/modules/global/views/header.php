  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
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
    <img class="animation__shake" src="<?php echo base_url();?><?php echo $settings['logo'];?>" alt="Yagna Solutions">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>

    </ul>
  </nav>