<?php
defined('BASEPATH') OR exit('No direct script access allowed');
global $SConfig;
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

	<title>Ngaco Bro</title>

	<!-- Google font -->
	<link href="<?=$SConfig->_site_url?>assets/css/googlefont_monstserrat.css" rel="stylesheet">

	<!-- Custom stlylesheet -->
	<link type="text/css" rel="stylesheet" href="<?=$SConfig->_site_url?>assets/css/style404.css" />

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

</head>

<body>
	<div id="notfound">
		<div class="notfound">
			<div class="notfound-404">
				<h1>Oops!</h1>
			</div>
			<h2>404 - Halaman Tidak Ditemukan</h2>
			<p>Haloo, halaman yang anda cari sudah dihapus atau sementara tidak tersdia. Mohon maaf ya...!</p>
			<a href="<?=$SConfig->_site_url?>">Kembali Ke Beranda</a>
		</div>
	</div>

</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>