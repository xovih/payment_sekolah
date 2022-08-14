<?php
global $SConfig;
$siteUrl = $SConfig->_site_url;
?>

<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Menu Sistem</h1>
      <div class="section-header-button">
        <a href="#addparent" class="btn btn-primary">
          <i class="fa fa-cube"></i>&nbsp;&nbsp; 
          Add Parent Menu
        </a>
        <a href="#addchild" class="btn btn-dark">
          <i class="fas fa-angle-right"></i>&nbsp;&nbsp; 
          Add Sub Menu
        </a>
      </div>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active">
          <a href="#">Konfigurasi App</a>
        </div>
        <div class="breadcrumb-item active">
          <a href="#">Menu Sistem</a>
        </div>
        <div class="breadcrumb-item">Semua Menu</div>
      </div>
    </div>

    <div class="section-body" id="dashboard-data">
      <h2 class="section-title">Menu Sistem</h2>
      <p class="section-lead">
        Semua Menu yang Ada di Web App Aplikasi Praktis diatur disini.
      </p>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>List Menu</h4>
            </div>
            <div class="card-body">
              <div class="float-left">
                <select class="form-control selectric" id="sel-menu-type">
                  <option value="parent">Parent Menu</option>
                  <option value="child">Sub Menu</option>
                </select>
              </div>
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
                <table class="table table-bordered table-lg" id="tbl-menu">
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
          <p id="delete-label">Apakah yakin mau hapus menu <code>aa</code></p>
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

  <div class="modal fade" role="dialog" id="modal-parent">
    <div class="modal-dialog modal-lg" role="document" style="margin-top: 13%">
      <div class="modal-content">
        <form action="aaaa" name="modal-parent-form" id="modal-parent-form" method="post">
          <div class="modal-header">
            <h5 class="modal-title" id="modal-parent-cop"><i class="fa fa-feather-alt"></i> Tambah Menu</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Title</label>
                  <input type="text" class="form-control" placeholder="Menus" id="modal-parent-title" name="title" />
                  <input type="hidden" name="menuid" id="modal-parent-menuid" />
                </div>
              </div>
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Link</label>
                  <input type="text" class="form-control" placeholder="/menus/index" id="modal-parent-link" name="link" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-lg-4">
                <div class="form-group">
                  <label>Menu Category</label>
                  <select class="form-control select2" id="modal-parent-category" name="category">
                    <option value="top">Top Menu</option>
                    <option value="pengaturan">Konfigurasi App</option>
                    <option value="master">Master Data</option>
                    <option value="transaksi">Transaksi</option>
                    <option value="laporan">Laporan</option>
                  </select>
                </div>
              </div>
              <div class="col-12 col-lg-4">
                <div class="form-group">
                  <label>Order No.</label>
                  <input type="number" class="form-control" placeholder="1" id="modal-parent-orderno" name="orderno" />
                </div>
              </div>
              <div class="col-12 col-lg-4">
                <div class="form-group">
                  <label>Menu Type</label>
                  <select class="form-control select2" id="modal-parent-menutype" name="menutype">
                    <option value="single">Single Menu</option>
                    <option value="parent">Parent Menu</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Active Class</label>
                  <input type="text" class="form-control" placeholder="controller" id="modal-parent-activeclass" name="activeclass" />
                </div>
              </div>
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Icon</label>
                  <input type="text" class="form-control" placeholder="fontawesome5 class" id="modal-parent-icon" name="icon" />
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
            <button type="submit" class="btn btn-primary" id="modal-parent-submit"><i class="fa fa-check"></i> Add !</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" role="dialog" id="modal-child">
    <div class="modal-dialog modal-lg" role="document"  style="margin-top: 13%">
      <div class="modal-content">
        <form action="aaaa" name="modal-child-form" id="modal-child-form" method="post">
          <div class="modal-header">
            <h5 class="modal-title" id="modal-child-cop"><i class="fa fa-feather-alt"></i> Tambah Menu</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Title</label>
                  <input type="text" class="form-control" placeholder="Menus" id="modal-child-title" name="title" />
                  <input type="hidden" name="menuid" id="modal-child-menuid" />
                </div>
              </div>
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Link</label>
                  <input type="text" class="form-control" placeholder="/menus/index" id="modal-child-link" name="link" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Parent ID</label>
                  <select class="form-control select2" id="modal-child-parentid" name="parentid">
                    <option value="child">Child Menu</option>
                  </select>
                </div>
              </div>
              <div class="col-12 col-lg-3">
                <div class="form-group">
                  <label>Category</label>
                  <input type="text" class="form-control" placeholder="1" id="modal-child-category" name="category" readonly/>
                </div>
              </div>
              <div class="col-12 col-lg-3">
                <div class="form-group">
                  <label>Order No.</label>
                  <input type="number" class="form-control" placeholder="1" id="modal-child-orderno" name="orderno" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Active Class</label>
                  <input type="text" class="form-control" placeholder="controller" id="modal-child-activeclass" name="activeclass" />
                </div>
              </div>
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label>Icon</label>
                  <input type="text" class="form-control" placeholder="fontawesome5 class" id="modal-child-icon" name="icon" />
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
            <button type="submit" class="btn btn-primary" id="modal-child-submit"><i class="fa fa-check"></i> Add !</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="<?=$siteUrl?>assets/js/socket.io.min.js"></script>
<script src="<?=$siteUrl?>assets/plugins/twbs-pagination/twbs.min.js"></script>
<script src="<?=$siteUrl?>assets/custom-js/menu_list.js"></script>
<script>
  newPage()
</script>