<?php 
global $SConfig; 
$judul = $pageTitle." - ".$SConfig->_site_name;
$siteUrl = $SConfig->_site_url;
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta
			content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no"
			name="viewport"
		/>
		<title><?= $judul ?></title>
		<link rel="icon" type="image/png" href="<?=$siteUrl?>assets/images/logo.png" />

		<!-- General CSS Files -->
		<link rel="stylesheet" href="<?=$siteUrl?>assets/css/bootstrap.min.css" />
		<!-- CSS Libraries -->
		<link rel="stylesheet" href="<?=$siteUrl?>assets/css/toastr.min.css" />
		<link rel="stylesheet" href="<?=$siteUrl?>assets/css/select2.min.css" />
		<link rel="stylesheet" href="<?=$siteUrl?>assets/css/daterangepicker.css" />
		<link rel="stylesheet" href="<?=$siteUrl?>assets/css/selectric.css">
		<link rel="stylesheet" href="<?=$siteUrl?>assets/css/jquery-ui.min.css">
		<link rel="stylesheet" href="<?=$siteUrl?>assets/css/jquery-ui.theme.min.css">

		  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?= $SConfig->_site_url ?>assets/plugins/fontawesome-free/css/all.min.css">

		<!-- Template CSS -->
		<link rel="stylesheet" href="<?=$siteUrl?>assets/css/style.css" />
		<link rel="stylesheet" href="<?=$siteUrl?>assets/css/components.css" />

		<!-- Custom CSS -->
		<link rel="stylesheet" href="<?=$siteUrl?>assets/css/mystyle.css" />
		

    <!-- General JS Scripts -->
		<script src="<?=$siteUrl?>assets/js/jquery-3.6.0.min.js"></script>
		<script src="<?=$siteUrl?>assets/js/popper.min.js"></script>
		<script src="<?=$siteUrl?>assets/js/bootstrap.min.js"></script>
		<script src="<?=$siteUrl?>assets/js/jquery.nicescroll.min.js"></script>
		<script src="<?=$siteUrl?>assets/js/jquery.ba-bbq.min.js"></script>
		<script src="<?=$siteUrl?>assets/js/jquery-ui.min.js"></script>
		
		<!-- JS LIB -->
		<script src="<?=$siteUrl?>assets/js/moment-with-locales.min.js"></script>
		<script src="<?=$siteUrl?>assets/js/toastr.min.js"></script>
		<script src="<?=$siteUrl?>assets/js/chart.min.js"></script>
		<!-- <script src="<$siteUrl?>assets/js/Chart.min.js"></script> -->
		<script src="<?=$siteUrl?>assets/js/select2.full.min.js"></script>
		<script src="<?=$siteUrl?>assets/js/daterangepicker.js"></script>
		<script src="<?=$siteUrl?>assets/js/jquery.selectric.min.js"></script>
		
		<!-- Template JS File -->
		<script src="<?=$siteUrl?>assets/js/stisla.js"></script>
		<script src="<?=$siteUrl?>assets/js/scripts.js"></script>

		<!-- Custom JS -->
		<script src="<?=$siteUrl?>assets/custom-js/global_function.js"></script>
	</head>

	<body>
    <div id="app">
			<div class="main-wrapper">