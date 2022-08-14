<?php
global $SConfig;
$siteUrl = $SConfig->_site_url;
?>

<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>List Of Users</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active">
          <a href="#">Users</a>
        </div>
        <div class="breadcrumb-item"><a href="#">User List</a></div>
      </div>
    </div>

    <div class="section-body" id="dashboard-data">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>
                <a href="#add" class="btn btn-primary" id="btnTambah">
                  <i class="fa fa-users"></i>&nbsp;&nbsp; Add a User
                </a> 
              </h4>
              <div class="card-header-form">
                <div class="input-group">
                  <input type="text" id="search" class="form-control" placeholder="Search" autocomplete="off" />
                  <div class="input-group-btn">
                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-lg" id="tbl-user">
                  <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th>Fullname</th>
                    <th >Alias</th>
                    <th>Actor Type</th>
                    <th class="text-center">Reset Password</th>
                    <th class="text-center">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td>1</td>
                    <td>Irwansyah Saputra</td>
                    <td>2017-01-09</td>
                    <td><div class="badge badge-success">Active</div></td>
                    <td><a href="#" class="btn btn-secondary">Detail</a></td>
                    <td><a href="#" class="btn btn-secondary">Detail</a></td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="card-footer text-right">
              <nav class="d-inline-block" id="page">
                <ul class="pagination mb-0" id="pagination-wraper">
                </ul>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="modal fade" role="dialog" id="modal-user">
    <div class="modal-dialog modal-lg" role="document" style="margin-top: 13%">
      <div class="modal-content">
        <form action="aaaa" name="modal-user-form" id="modal-user-form" method="post">
          <div class="modal-header">
            <h5 class="modal-title" id="modal-user-cop"><i class="fa fa-feather-alt"></i> Tambah Actor</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Fullname</label>
                  <input type="text" class="form-control" placeholder="nama user" id="modal-user-fullname" name="fullname" autocomplete="off" />
                  <input type="hidden" name="userid" id="modal-user-userid" />
                </div>
              </div>
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Alias</label>
                  <input type="text" class="form-control" placeholder="npp" id="modal-user-alias" name="alias" autocomplete="off" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Date of Birth</label>
                  <input type="text" class="form-control daterangpicker" placeholder="1193-10-28" id="modal-user-dateofbirth" name="dateofbirth" />
                </div>
              </div>
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Password</label>
                  <input type="password" class="form-control" placeholder="minim 8 karakter" id="modal-user-password" name="password" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Avatar</label>
                  <input type="text" class="form-control" placeholder="http://avatar.jpeg" id="modal-user-avatar" name="avatar" />
                </div>
              </div>
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Actor Type</label>
                  <select class="form-control select2" id="modal-user-actorid" name="actorid">
                  <option value="single">Single Menu</option>
                </select>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
            <button type="submit" class="btn btn-primary" id="modal-user-submit"><i class="fa fa-check"></i> Add !</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" role="dialog" id="modal-ubah">
    <div class="modal-dialog" role="document" style="margin-top: 15%">
      <div class="modal-content">
        <form action="aaaa" name="modal-ubah-form" id="modal-ubah-form" method="post">
          <div class="modal-header">
            <h5 class="modal-title" id="modal-ubah-cop"><i class="fa fa-feather-alt"></i> Tambah Actor</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                <p id="modal-ubah-text">aaa</p>
                <input type="hidden" name="userid" id="modal-ubah-userid" />
                <input type="hidden" name="fullname" id="modal-ubah-fullname" />
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger" id="modal-ubah-submit"><i class="fa fa-check"></i> Add !</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>

<script src="<?=$siteUrl?>assets/js/socket.io.min.js"></script>
<script src="<?=$siteUrl?>assets/js/pagination.min.js"></script>
<script src="<?=$siteUrl?>assets/custom-js/user.js"></script>
<script>
  window.history.pushState(null, null, window.location.pathname)
  getUsers()
</script>