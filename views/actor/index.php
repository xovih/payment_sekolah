<?php
global $SConfig;
$siteUrl = $SConfig->_site_url;
?>

<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Manajemen Aktor</h1>
      <div class="section-header-button">
        <a href="#add" class="btn btn-primary">
          <i class="fa fa-user-secret"></i>&nbsp;&nbsp; 
          Add New Actor
        </a>
      </div>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active">
          <a href="#">Konfigurasi App</a>
        </div>
        <div class="breadcrumb-item active">
          <a href="#">Manajemen Aktor</a>
        </div>
        <div class="breadcrumb-item">List Aktor</div>
      </div>
    </div>

    <div class="section-body" id="dashboard-data">
      <h2 class="section-title">Manajemen Aktor</h2>
      <p class="section-lead">
        Role / Akses user dikelola pada halaman ini.
      </p>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>List Aktor</h4>
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
                <table class="table table-bordered table-lg" id="tbl-actor">
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

  <div class="modal fade" tabindex="-1" role="dialog" id="modal-delete">
    <div class="modal-dialog" role="document" style="margin-top: 15%">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-delete-cop">Delete Confirmation</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p id="delete-label">Apakah yakin mau hapus aktor <code>aa</code></p>
          <form action="aaaa" method="post" id="modal-delete-form">
            <input type="hidden" name="menuid" id="delete-id" />
            <input type="hidden" name="name" id="delete-name" />
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" id="modal-delete-submit">Delete</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" role="dialog" id="modal-actor">
    <div class="modal-dialog" role="document" style="margin-top: 13%">
      <div class="modal-content">
        <form action="aaaa" name="modal-actor-form" id="modal-actor-form" method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-actor-cop"><i class="fa fa-feather-alt"></i> Tambah Actor</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label id="modal-actor-namelabel">Nama Aktor</label>
                <input type="text" class="form-control" placeholder="nama aktor" id="modal-actor-name" name="name" />
                <input type="hidden" name="actorid" id="modal-actor-actorid" />
                <input type="hidden" name="oldname" id="modal-actor-oldname" />
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
          <button type="submit" class="btn btn-primary" id="modal-actor-submit"><i class="fa fa-check"></i> Add !</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" role="dialog" id="modal-config">
    <div class="modal-dialog modal-lg" role="document" style="margin-top: 13%">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-config-cop"><i class="fa fa-feather-alt"></i> Tambah Actor</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-9">
              <div class="form-group">
                <label>Add a Menu</label>
                <select class="form-control select2" id="modal-config-menu" name="menu">
                  <option value="single">Single Menu</option>
                </select>
                <input type="hidden" name="actorid" id="modal-config-actorid" />
              </div>
            </div>
            <div class="col-3">
              <div class="form-group">
                <label>&nbsp;</label>
                <button class="btn btn-primary form-control" id="modal-config-add"><i class="fa fa-plus-circle"></i> Add</button>
              </div>
            </div>
          </div>
          <div class="row" style="max-height: 300px; overflow: auto;">
            <div class="col-12">
              <div class="table-responsive">
                <table class="table table-bordered table-lg" id="modal-config-table">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th>Title</th>
                      <th class="text-center">Icon</th>
                      <th class="text-center">Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1</td>
                      <td>Irwansyah Saputra</td>
                      <td>2017-01-09</td>
                      <td><a href="#" class="btn-sm btn-secondary">Detail</a></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" tabindex="-1" role="dialog" id="modal-user">
    <div class="modal-dialog modal-lg" role="document" style="margin-top: 13%">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fa fa-users"></i> User List</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row" style="max-height: 300px; overflow: auto;">
            <div class="col-12">
              <h5 class="card-title text-primary" id="modal-user-cop">Actor : Administrator</h5>
              <input type="hidden" name="actorid" id="modal-user-actorid">
              <input type="hidden" name="name" id="modal-user-name">
            </div>
            <div class="col-12">
              <div class="table-responsive">
                <table class="table table-bordered table-lg" id="modal-user-table">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">NPP</th>
                      <th>Name</th>
                      <th>Departemen</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1</td>
                      <td>05817</td>
                      <td>Sofi Rahmatulloh</td>
                      <td class="text-center"><a href="#" class="btn-sm btn-danger"><i class="fa fa-trash"></i></a></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="<?=$siteUrl?>assets/js/socket.io.min.js"></script>
<script src="<?=$siteUrl?>assets/plugins/twbs-pagination/twbs.min.js"></script>
<script src="<?=$siteUrl?>assets/custom-js/actor.js"></script>
<script>
  window.history.pushState(null, null, window.location.pathname)
  getActors()
</script>