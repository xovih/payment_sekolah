<?php
global $SConfig;
$siteUrl = $SConfig->_site_url;
$fullname = $this->session->userdata("fullname");
$username = $this->session->userdata("username");
?>

<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Welcome <span class="text-danger"><?=$fullname?> @<?=$username?></span></h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active">
          <a href="#">Home</a>
        </div>
      </div>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <img style="width: 100%" src="<?=$siteUrl?>assets/images/welcome.jpg" alt="welcome"/>
        </div>
      </div>
    </div>
  </section>
</div>