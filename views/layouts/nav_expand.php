<?php 
global $SConfig; 
$siteUrl = $SConfig->_site_url;
$userData = $this->session->userdata;
?>

<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
  <a href="index.html" class="navbar-brand sidebar-gone-hide">
    <img src="<?=$siteUrl?>assets/images/logoapp.png" alt="" height="60px" />
    <!-- SuhuDB -->
  </a>
  <div class="navbar-nav">
    <a href="#" class="nav-link sidebar-gone-show" data-toggle="sidebar"
      ><i class="fas fa-bars"></i
    ></a>
  </div>
  <div class="nav-collapse">
    <a class="sidebar-gone-show nav-collapse-toggle nav-link" href="#">
      <i class="fas fa-ellipsis-v"></i>
    </a>
    <ul class="navbar-nav">
      <li class="nav-item">
        <a href="#" class="nav-link">Report Something</a>
      </li>
      <li class="nav-item active">
        <a href="<?=$siteUrl?>auth/logout" class="nav-link">Logout</a>
      </li>
    </ul>
  </div>
  <ul class="navbar-nav navbar-right ml-auto">
    <li class="dropdown dropdown-list-toggle">
      <a
        href="#"
        data-toggle="dropdown"
        class="nav-link notification-toggle nav-link-lg beep"
        ><i class="far fa-bell"></i
      ></a>
      <div class="dropdown-menu dropdown-list dropdown-menu-right">
        <div class="dropdown-header">
          Notifications
          <div class="float-right">
            <a href="#">Mark All As Read</a>
          </div>
        </div>
        <div class="dropdown-list-content dropdown-list-icons">
          <a href="#" class="dropdown-item dropdown-item-unread">
            <div class="dropdown-item-icon bg-primary text-white">
              <i class="fas fa-code"></i>
            </div>
            <div class="dropdown-item-desc">
              Template update is available now!
              <div class="time text-primary">2 Min Ago</div>
            </div>
          </a>
          <a href="#" class="dropdown-item">
            <div class="dropdown-item-icon bg-info text-white">
              <i class="far fa-user"></i>
            </div>
            <div class="dropdown-item-desc">
              <b>You</b> and <b>Dedik Sugiharto</b> are now friends
              <div class="time">10 Hours Ago</div>
            </div>
          </a>
          <a href="#" class="dropdown-item">
            <div class="dropdown-item-icon bg-success text-white">
              <i class="fas fa-check"></i>
            </div>
            <div class="dropdown-item-desc">
              <b>Kusnaedi</b> has moved task <b>Fix bug header</b> to
              <b>Done</b>
              <div class="time">12 Hours Ago</div>
            </div>
          </a>
          <a href="#" class="dropdown-item">
            <div class="dropdown-item-icon bg-danger text-white">
              <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="dropdown-item-desc">
              Low disk space. Let's clean it!
              <div class="time">17 Hours Ago</div>
            </div>
          </a>
          <a href="#" class="dropdown-item">
            <div class="dropdown-item-icon bg-info text-white">
              <i class="fas fa-bell"></i>
            </div>
            <div class="dropdown-item-desc">
              Welcome to Stisla template!
              <div class="time">Yesterday</div>
            </div>
          </a>
        </div>
        <div class="dropdown-footer text-center">
          <a href="#">View All <i class="fas fa-chevron-right"></i></a>
        </div>
      </div>
    </li>
    <li class="dropdown">
      <a
        href="#"
        data-toggle="dropdown"
        class="nav-link dropdown-toggle nav-link-lg nav-link-user"
      >
        <img
          alt="image"
          src="<?= $userData["avatar"] ?>"
          class="rounded-circle mr-1"
        />
        <div class="d-sm-none d-lg-inline-block">
          <?= $userData["fullname"] ?>
        </div></a
      >
      <div class="dropdown-menu dropdown-menu-right">
        <div class="dropdown-title"><?= $userData["actorAS"] ?></div>
        <a 
          href="features-profile.html" 
          class="dropdown-item has-icon"
          id="profile-button"
        >
          <i class="far fa-user"></i> Profile
        </a>
        <a 
          href="features-settings.html" 
          class="dropdown-item has-icon"
          id="password-button"
        >
          <i class="fas fa-cog"></i> Change Password
        </a>
        <div class="dropdown-divider"></div>
        <a href="<?=base_url()?>auth/logout" class="dropdown-item has-icon text-danger">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </div>
    </li>
  </ul>
</nav>

<div class="modal fade" tabindex="-1" role="dialog" id="modal-password">
  <div class="modal-dialog" role="document" style="margin-top: 13%">
    <div class="modal-content">
      <form action="aaaa" name="modal-password-form" id="modal-password-form" method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-password-cop"><i class="fa fa-key"></i> Ubah Password</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label>Password Lama</label>
                <input 
                  type="password" 
                  class="form-control" 
                  id="modal-password-oldpassword" 
                  name="oldpassword" 
                />
                <input type="hidden" name="userid" id="modal-password-userid" />
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>Password Baru</label>
                <input 
                  type="password" 
                  class="form-control" 
                  id="modal-password-newpassword" 
                  name="newpassword" 
                />
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label>Konfirmasi Password</label>
                <input 
                  type="password" 
                  class="form-control" 
                  id="modal-password-confpassword" 
                  name="confpassword" 
                />
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
          <button type="submit" class="btn btn-danger" id="modal-password-submit"><i class="fa fa-check"></i> Ubah !</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal-profile">
    <div class="modal-dialog modal-lg" role="document" style="margin-top: 13%">
      <div class="modal-content">
        <form action="aaaa" name="modal-profile-form" id="modal-profile-form" method="post">
          <div class="modal-header">
            <h5 class="modal-title" id="modal-profile-cop"><i class="fa fa-user"></i> User Profile</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Fullname</label>
                  <input type="text" class="form-control" placeholder="nama user" id="modal-profile-fullname" name="fullname" autocomplete="off" />
                  <input type="hidden" name="userid" id="modal-profile-userid" />
                </div>
              </div>
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Alias</label>
                  <input type="text" class="form-control" placeholder="npp" id="modal-profile-alias" name="alias" autocomplete="off" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Date of Birth</label>
                  <input type="text" class="form-control daterangpicker" placeholder="1193-10-28" id="modal-profile-dateofbirth" name="dateofbirth" />
                </div>
              </div>
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Avatar</label>
                  <input type="text" class="form-control" placeholder="http://avatar.jpeg" id="modal-profile-avatar" name="avatar" />
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
            <button type="submit" class="btn btn-danger" id="modal-profile-submit"><i class="fa fa-check"></i> Change !</button>
          </div>
        </form>
      </div>
    </div>
  </div>

<script src="<?=$siteUrl?>assets/custom-js/password.js"></script>