<?php
global $SConfig;
$judul = "Login - ".$SConfig->_site_name;
$siteUrl = $SConfig->_site_url;
?>

<!DOCTYPE html>
<html lang="en" >

  <head>
    <meta charset="UTF-8">
    <title><?=$judul?></title>
    <meta name="viewport" content="initial-scale=1.0, width=device-width" />
    <link rel="stylesheet" href="<?=$siteUrl?>assets/login/css/reset.min.css">

    <link rel="stylesheet" href="<?=$siteUrl?>assets/login/css/style.css">
    <link rel="shortcut icon" type="image/png" href="<?=$siteUrl;?>assets/images/logo.png"/>

    <link rel="stylesheet" href="<?=$siteUrl?>assets/css/toastr.min.css" />
    
  </head>

  <body>

    <div class="login_form">
      <section class="login-wrapper">
        
        <div class="logo">
        <a target="_blank" rel="noopener" href="#">
        <img src="<?=$siteUrl?>assets/login/logo_pin.png" alt=""></a>
        </div>
        
        <form id="login" method="post" action=".....">
        <br />
          <label for="username">User Name</label>
          <input  required name="username" type="text" autocapitalize="off" autocorrect="off" id="username"/>
          
          <label for="password">Password</label>
          <input class="password" required name="password" type="password" id="password"/>
          <div class="hide-show">
            <span>Show</span>
          </div>
          
          <button type="submit" id="btn-login">Sign In</button>
          
        </form>
      </section>
    </div>

    <!-- General JS Scripts -->
		<script src="<?=$siteUrl?>assets/js/jquery-3.6.0.min.js"></script>

    <script src="<?=$siteUrl?>assets/js/toastr.min.js"></script>
    <script src="<?=$siteUrl?>assets/js/moment.min.js"></script>
    <script src="<?=$siteUrl?>assets/js/scripts.js"></script>

    <script  src="<?=$siteUrl?>assets/login/js/index.js"></script>

    <script src="<?=$siteUrl?>assets/custom-js/global_function.js"></script>
    <script src="<?=$siteUrl?>assets/custom-js/login.js"></script>

    <script>
      localStorage.clear()
    </script>

  </body>

</html>
