<?php
global $SConfig;
$siteUrl = $SConfig->_site_url;
?>

<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Manajemen Kelas</h1>
      <div class="section-header-button">
        <a href="#add" class="btn btn-primary">
          <i class="fa fa-sitemap"></i>&nbsp;&nbsp; 
          Tambah Kelas
        </a>
      </div>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active">
          <a href="#">Master Data</a>
        </div>
        <div class="breadcrumb-item active">
          <a href="#">Manajemen Kelas</a>
        </div>
        <div class="breadcrumb-item">List Kelas</div>
      </div>
    </div>

    <div class="section-body" id="dashboard-data">
      <h2 class="section-title">Manajemen Kelas</h2>
      <p class="section-lead">
        Manajamen Kelas ini digunakan untuk mengatur kelas-kelas yang ada, sebagai acuan siswa.
      </p>

      <div class="row">
        <div class="col-12 col-lg-9">
          <div class="card">
            <div class="card-header">
              <h4>List Kelas</h4>
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
                <table class="table table-bordered table-lg" id="tbl-kelas">
                  <thead>
                  <tr>
                    <th>#</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td>1</td>
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
          <p id="modal-delete-text">Apakah yakin mau hapus aktor <code>aa</code></p>
          <form action="aaaa" method="post" id="modal-delete-form">
            <input type="hidden" name="id_kelas" id="modal-delete-delete_id" />
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" id="modal-delete-submit">Delete</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" role="dialog" id="modal-kelas">
    <div class="modal-dialog" role="document" style="margin-top: 13%">
      <div class="modal-content">
        <form action="aaaa" name="modal-kelas-form" id="modal-kelas-form" method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-kelas-cop"><i class="fa fa-feather-alt"></i> Tambah Actor</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label id="modal-kelas-namelabel">Tingkat</label>
                <select name="tingkat" id="modal-kelas-tingkat" class="select2 form-control">
                  <option value="7">Kelas 7</option>
                  <option value="8">Kelas 8</option>
                  <option value="9">Kelas 9</option>
                </select>
                <input type="hidden" name="id_kelas" id="modal-kelas-id_kelas" />
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label for="">Label Kelas</label>
                <input type="text" name="label" id="modal-kelas-label" class="form-control" autocomplete="off">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
          <button type="submit" class="btn btn-primary" id="modal-kelas-submit"><i class="fa fa-check"></i> Add !</button>
        </div>
        </form>
      </div>
    </div>
  </div>

</div>

<script src="<?=$siteUrl?>assets/js/socket.io.min.js"></script>
<script src="<?=$siteUrl?>assets/plugins/twbs-pagination/twbs.min.js"></script>
<script src="<?=$siteUrl?>assets/custom-js/kelas.js"></script>
<script>
  window.history.pushState(null, null, window.location.pathname)
  getKelas()
</script>