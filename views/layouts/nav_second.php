<?php 
global $SConfig; 
$siteUrl = $SConfig->_site_url;
?>

<nav class="navbar navbar-secondary navbar-expand-lg">
  <div class="container">
    <ul class="navbar-nav">
    <?php
      foreach($menus as $mn) {
        $kelasInduk = $mn->menutype == "parent" ? "dropdown" : "";
        $kelasAktif = is_active_menu($mn->activeclass, 'active');

        $menuTitle  = $mn->title;
        $menuIcon   = $mn->icon;
        $menuLink   = $siteUrl.$mn->link;

        $menuKonten = "<a href='$menuLink' class='nav-link'><i class='$menuIcon'></i><span>$menuTitle</span></a>";

        if ($mn->menutype == "parent") {
          $menuKonten = "<a href='#' data-toggle='dropdown' class='nav-link has-dropdown'><i class='$menuIcon'></i><span>$menuTitle</span></a>";
          $menuKonten .= "<ul class='dropdown-menu'>";

          foreach($mn->child as $anak) {
            $linkAnak = $siteUrl.$anak->link;
            $namaAnak = $anak->title;
            $iconAnak = $anak->icon;

            $menuKonten .= "<li class='nav-item'><a href='$linkAnak' class='nav-link'>$namaAnak</a></li>";
          }

          $menuKonten .= "</ul>";
        }

        echo "<li class='nav-item $kelasAktif $kelasInduk'>";
        echo $menuKonten;
        echo "</li>";
      }
    ?>
    </ul>
  </div>
</nav>