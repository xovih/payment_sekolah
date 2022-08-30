<?php
global $SConfig;
$siteUrl = $SConfig->_site_url;
?>

<div class="overlao">
  <img class="airplane" src="<?= $siteUrl ?>/assets/images/airplane.png" alt="airplane">
  <h3>Sedang Mengunggah . .</h3>
  <img class="cloud1" src="<?= $siteUrl ?>/assets/images/cloud.png" alt="cloud1">
  <img class="cloud2" src="<?= $siteUrl ?>/assets/images/cloud.png" alt="cloud2">
  <img class="cloud3" src="<?= $siteUrl ?>/assets/images/cloud.png" alt="cloud3">
</div>

<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Data Siswa</h1>
      <div class="section-header-button">
        <a href="#add" class="btn btn-primary">
          <i class="fa fa-id-badge"></i>&nbsp;&nbsp; 
          Tambah Data Siswa
        </a>
        <a href="#import" class="btn btn-dark">
          <i class="fa fa-file-excel"></i>&nbsp;&nbsp; 
          Import Data
        </a>
      </div>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active">
          <a href="#">Master Data</a>
        </div>
        <div class="breadcrumb-item active">
          <a href="#">Data Siswa</a>
        </div>
        <div class="breadcrumb-item">List Siswa</div>
      </div>
    </div>

    <div class="section-body" id="dashboard-data">
      <h2 class="section-title">Data Siswa</h2>
      <p class="section-lead">
        Master Siswa ini digunakan untuk mengelola Data Siswa.
      </p>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>List Siwa</h4>
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
                <table class="table table-bordered table-lg" id="tbl-siswa">
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
            <input type="hidden" name="id_siswa" id="modal-delete-delete_id" />
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" id="modal-delete-submit">Delete</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" role="dialog" id="modal-siswa">
    <div class="modal-dialog modal-lg" role="document" style="margin-top: 10%">
      <div class="modal-content">
        <form action="aaaa" name="modal-siswa-form" id="modal-siswa-form" method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-siswa-cop"><i class="fa fa-feather-alt"></i> Tambah Actor</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12 col-lg-6">
              <div class="form-group">
                <label id="modal-siswa-idlabel">No. Induk Siswa</label>
                <input type="text" class="form-control" placeholder="no induk siswa" id="modal-siswa-no_induk" name="no_induk" autocomplete="off"/>
                <input type="hidden" class="form-control" id="modal-siswa-id_siswa" name="id_siswa" />
              </div>
            </div>
            <div class="col-12 col-lg-6">
              <div class="form-group">
                <label id="modal-siswa-namalabel">Nama Siswa</label>
                <input type="text" class="form-control harusalfa" placeholder="Nama Lengkap Siswa" id="modal-siswa-nama" name="nama" autocomplete="off"/>
              </div>
            </div>
            <div class="col-12 col-lg-6">
              <div class="form-group">
                <label id="modal-siswa-genlabel">Jenis Kelamin</label>
                <select name="gender" id="modal-siswa-gender" class="form-control select2">
                  <option value="L">Laki-Laki</option>
                  <option value="P">Perempuan</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-lg-6">
              <div class="form-group">
                <label id="modal-siswa-kelas">Kelas</label>
                <select name="id_kelas" id="modal-siswa-id_kelas" class="form-control select2">
                  <option value="L">Laki-Laki</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
          <button type="submit" class="btn btn-primary" id="modal-siswa-submit"><i class="fa fa-check"></i> Add !</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" role="dialog" id="modal-import"  style="margin-top: 5%">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-import-cop"><i class="fa fa-upload"></i> Import Siswa dari Excel</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="" method="post" enctype="multipart/form-data" id="modal-import-form">
            <div class="row">
              <div class="col-12">
                <img src="<?=$siteUrl?>/assets/images/siswa.png" class="rounded" style="width:100%" alt="Panduan">
              </div>
              <div class="col-12">
                <div class="form-group">
                  <input type="file" id="modal-import-file" name="file" class="form-control" aria-describedby="fileHelpText">
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-dark" id="modal-import-submit">Import !</button>
          
        </div>
      </div>
    </div>
  </div>

</div>

<script src="<?=$siteUrl?>assets/js/jszip.min.js"></script>
<script src="<?=$siteUrl?>assets/js/xlsx.mini.min.js"></script>
<script src="<?=$siteUrl?>assets/plugins/twbs-pagination/twbs.min.js"></script>
<script src="<?=$siteUrl?>assets/custom-js/siswa.js"></script>
<script>
  window.history.pushState(null, null, window.location.pathname)
  getSiswa()
</script>