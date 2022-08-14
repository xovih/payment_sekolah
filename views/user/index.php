<?php
global $SConfig;
$siteUrl = $SConfig->_site_url;
?>

<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Manajemen Pengguna</h1>
      <div class="section-header-button">
        <a href="#add" class="btn btn-primary">
          <i class="fa fa-user"></i>&nbsp;&nbsp; 
          Add New User
        </a>
      </div>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active">
          <a href="#">Konfigurasi App</a>
        </div>
        <div class="breadcrumb-item active">
          <a href="#">Manajemen Pengguna</a>
        </div>
        <div class="breadcrumb-item">List Pengguna</div>
      </div>
    </div>

    <div class="section-body" id="dashboard-data">
      <h2 class="section-title">Manajemen Pengguna</h2>
      <p class="section-lead">
        Menambah, mengubah, menghapus User. Serta Reset Password.
      </p>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>List Pengguna</h4>
            </div>
            <div class="card-body">
              <div class="float-right">
                <form>
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search" id="search">
                    <div class="input-group-append">
                      <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                  </div>
                </form>
              </div>

              <div class="clearfix mb-3"></div>
              <div class="table-responsive">
                <table class="table table-bordered table-lg" id="tbl-user">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td>1</td>
                    <td>Irwansyah Saputra</td>
                    <td>2017-01-09</td>
                    <td><div class="badge badge-success">Active</div></td>
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
            <h5 class="modal-title" id="modal-user-cop"><i class="fa fa-feather-alt"></i> Tambah Pengguna</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 col-lg-7">
                <div class="form-group">
                  <label>Fullname</label>
                  <input type="text" class="form-control" placeholder="nama user" id="modal-user-fullname" name="fullname" autocomplete="off" />
                  <input type="hidden" name="user_id" id="modal-user-user_id" />
                </div>
              </div>
              <div class="col-12 col-lg-5">
                <div class="form-group">
                  <label>Username</label>
                  <input type="text" class="form-control" placeholder="username" id="modal-user-username" name="username" autocomplete="off" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Password</label>
                  <input type="password" class="form-control" placeholder="minim 5 karakter" id="modal-user-password" name="password" />
                </div>
              </div>
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Actor Type</label>
                  <select class="form-control select2" id="modal-user-actor_id" name="actor_id">
                    <option value="single">Single Menu</option>
                  </select>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label>Photo Link</label>
                  <input type="text" name="photo" id="modal-user-photo" class="form-control" />
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

  <div class="modal fade" role="dialog" id="modal-delete">
    <div class="modal-dialog" role="document" style="margin-top: 15%">
      <div class="modal-content">
        <form action="aaaa" name="modal-delete-form" id="modal-delete-form" method="post">
          <div class="modal-header">
            <h5 class="modal-title" id="modal-delete-cop"><i class="fa fa-feather-alt"></i> Tambah Aksi</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                <p id="modal-delete-text">aaa</p>
                <input type="hidden" name="user_id" id="modal-delete-user_id" />
                <input type="hidden" name="fullname" id="modal-delete-fullname" />
                <input type="hidden" name="sid" id="modal-delete-sid" />
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger" id="modal-delete-submit"><i class="fa fa-check"></i> Add !</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" role="dialog" id="modal-reset">
    <div class="modal-dialog" role="document" style="margin-top: 15%">
      <div class="modal-content">
        <form action="aaaa" name="modal-reset-form" id="modal-reset-form" method="post">
          <div class="modal-header">
            <h5 class="modal-title" id="modal-reset-cop"><i class="fa fa-feather-alt"></i> Tambah Aksi</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label>Password Baru</label>
                  <input type="password" class="form-control" placeholder="password" id="modal-reset-password" name="password" autocomplete="off" />
                </div>
                <input type="hidden" name="user_id" id="modal-reset-user_id" />
                <input type="hidden" name="fullname" id="modal-reset-fullname" />
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger" id="modal-reset-submit"><i class="fa fa-check"></i> Add !</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="<?=$siteUrl?>assets/js/socket.io.min.js"></script>
<script src="<?=$siteUrl?>assets/plugins/twbs-pagination/twbs.min.js"></script>
<script src="<?=$siteUrl?>assets/custom-js/user.js"></script>
<script>
  window.history.pushState(null, null, window.location.pathname)
  getUsers()
</script>