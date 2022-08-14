<?php 
global $SConfig; 
?>

<?php $this->load->view('layouts/header', ["pageTitle" => $title]); ?>

<?php 
$this->load->view('layouts/sidebar', ["menu" => $menus]);
$this->load->view($content);
$this->load->view('layouts/footer'); 
?>  
</div>
