<?php 
global $SConfig; 
$siteUrl = $SConfig->_site_url;
$userData = $this->session->userdata;
?>

<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
  <form class="form-inline mr-auto">
						<ul class="navbar-nav mr-3">
							<li>
								<a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"
									><i class="fas fa-bars"></i
								></a>
							</li>
						</ul>
					</form>
					<ul class="navbar-nav navbar-right">
						<li class="dropdown">
							<a
								href="#"
								data-toggle="dropdown"
								class="nav-link dropdown-toggle nav-link-lg nav-link-user"
							>
								<img
									alt="image"
									src="<?=$this->session->userdata("avatar")?>"
									class="rounded-circle mr-1"
								/>
								<div class="d-sm-none d-lg-inline-block">
									<?=$this->session->userdata("fullname")?>
								</div></a
							>
							<div class="dropdown-menu dropdown-menu-right">
								<div class="dropdown-title">User Choice</div>
								
								<a href="features-settings.html" class="dropdown-item has-icon" id="page-reset-password">
									<i class="fas fa-cog"></i> Ubah Password
								</a>
								<div class="dropdown-divider"></div>
								<a href="#" onClick="logout()" class="dropdown-item has-icon text-danger">
									<i class="fas fa-sign-out-alt"></i> Logout
								</a>
							</div>
						</li>
					</ul>
</nav>
<div class="main-sidebar">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="index.html">MTSN 7 Kediri</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="index.html">MK</a>
    </div>
    <ul class="sidebar-menu">
      
			<?php
				$top = $menu['menu_top'];
				$kon = $menu['menu_stg'];
				$mtr = $menu['menu_mtr'];
				$trs = $menu['menu_trs'];
				$lap = $menu['menu_lap'];

				if ($top != null) {
					foreach ($top as $menuku) {
						$tipe = $menuku->type;
						$klas = $tipe == 'single' ? '' : 'nav-item dropdown';
						$hasChild = count($menuku->child) > 0 ? 'has-dropdown' : '';

						?>
						<li class="<?=$klas?> <?php echo is_active_menu($menuku->active_class, "active"); ?>">
							<a class="nav-link <?=$hasChild?>" href="<?=base_url().$menuku->link?>">
								<i class="<?=$menuku->icon?>"></i> <span><?=$menuku->label?></span>
							</a>

							<?php
							if (count($menuku->child) > 0) {
								echo '<ul class="dropdown-menu">';

								foreach($menuku->child as $childMenu) {
									?>
									<li>
										<a class="nav-link" href="<?=base_url().$childMenu->link?>">
											<?=$childMenu->label?>
										</a>
									</li>
									<?php
								}

								echo '</ul>';
							}
							?>

						</li>
						<?php 
					}
				}

				if ($kon != null) {
					echo "<li class='menu-header'>Konfigurasi App</li>";
					foreach ($kon as $menuku) {
						$tipe = $menuku->type;
						$klas = $tipe == 'single' ? '' : 'nav-item dropdown';
						$hasChild = count($menuku->child) > 0 ? 'has-dropdown' : '';

						?>
						<li class="<?=$klas?> <?php echo is_active_menu($menuku->active_class, "active") ?>">
							<a class="nav-link <?=$hasChild?>" href="<?=base_url().$menuku->link?>">
								<i class="<?=$menuku->icon?>"></i> <span><?=$menuku->label?></span>
							</a>

							<?php
							if (count($menuku->child) > 0) {
								echo '<ul class="dropdown-menu">';

								foreach($menuku->child as $childMenu) {
									?>
									<li>
										<a class="nav-link" href="<?=base_url().$childMenu->link?>">
											<?=$childMenu->label?>
										</a>
									</li>
									<?php
								}

								echo '</ul>';
							}
							?>

						</li>
						<?php 
					}
				}

				if ($mtr != null) {
					echo "<li class='menu-header'>Master Data</li>";
					foreach ($mtr as $menuku) {
						$tipe = $menuku->type;
						$klas = $tipe == 'single' ? '' : 'nav-item dropdown';
						$hasChild = count($menuku->child) > 0 ? 'has-dropdown' : '';

						?>
						<li class="<?=$klas?> <?php echo is_active_menu($menuku->active_class, "active") ?>">
							<a class="nav-link <?=$hasChild?>" href="<?=base_url().$menuku->link?>">
								<i class="<?=$menuku->icon?>"></i> <span><?=$menuku->label?></span>
							</a>

							<?php
							if (count($menuku->child) > 0) {
								echo '<ul class="dropdown-menu">';

								foreach($menuku->child as $childMenu) {
									?>
									<li>
										<a class="nav-link" href="<?=base_url().$childMenu->link?>">
											<?=$childMenu->label?>
										</a>
									</li>
									<?php
								}

								echo '</ul>';
							}
							?>

						</li>
						<?php 
					}
				}

				if ($trs != null) {
					echo "<li class='menu-header'>Transaksi</li>";
					foreach ($trs as $menuku) {
						$tipe = $menuku->type;
						$klas = $tipe == 'single' ? '' : 'nav-item dropdown';
						$hasChild = count($menuku->child) > 0 ? 'has-dropdown' : '';

						?>
						<li class="<?=$klas?> <?php echo is_active_menu($menuku->active_class, "active") ?>">
							<a class="nav-link <?=$hasChild?>" href="<?=base_url().$menuku->link?>">
								<i class="<?=$menuku->icon?>"></i> <span><?=$menuku->label?></span>
							</a>

							<?php
							if (count($menuku->child) > 0) {
								echo '<ul class="dropdown-menu">';

								foreach($menuku->child as $childMenu) {
									?>
									<li>
										<a class="nav-link" href="<?=base_url().$childMenu->link?>">
											<?=$childMenu->label?>
										</a>
									</li>
									<?php
								}

								echo '</ul>';
							}
							?>

						</li>
						<?php 
					}
				}

				if ($lap != null) {
					echo "<li class='menu-header'>Laporan</li>";
					foreach ($lap as $menuku) {
						$tipe = $menuku->type;
						$klas = $tipe == 'single' ? '' : 'nav-item dropdown';
						$hasChild = count($menuku->child) > 0 ? 'has-dropdown' : '';

						?>
						<li class="<?=$klas?> <?php echo is_active_menu($menuku->active_class, "active") ?>">
							<a class="nav-link <?=$hasChild?>" href="<?=base_url().$menuku->link?>">
								<i class="<?=$menuku->icon?>"></i> <span><?=$menuku->label?></span>
							</a>

							<?php
							if (count($menuku->child) > 0) {
								echo '<ul class="dropdown-menu">';

								foreach($menuku->child as $childMenu) {
									?>
									<li>
										<a class="nav-link" href="<?=base_url().$childMenu->link?>">
											<?=$childMenu->label?>
										</a>
									</li>
									<?php
								}

								echo '</ul>';
							}
							?>

						</li>
						<?php 
					}
				}
			?>
      
    </ul>

    <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
      <button
        onClick="logout()"
        class="btn btn-primary btn-lg btn-block btn-icon-split"
      >
        <i class="fas fa-sign-out-alt"></i> Logout
      </button>
    </div>
  </aside>
</div>