<?php
global $SConfig;
$siteUrl = $SConfig->_site_url;
?>

<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Data Tagihan</h1>
      <div class="section-header-button">
        <a href="<?=$siteUrl?>tagihan/add" target="_blank" class="btn btn-primary">
          <i class="fa fa-tag"></i>&nbsp;&nbsp; 
          Tambah Tagihan
        </a>
        <a href="#import" class="btn btn-dark">
          <i class="fa fa-file-excel"></i>&nbsp;&nbsp; 
          Import Tagihan
        </a>
      </div>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active">
          <a href="#">Transaksi</a>
        </div>
        <div class="breadcrumb-item active">
          <a href="#">Data Tagihan</a>
        </div>
        <div class="breadcrumb-item">List Tagihan</div>
      </div>
    </div>

    <div class="section-body" id="dashboard-data">
      <h2 class="section-title">Data Tagihan</h2>
      <p class="section-lead">
        Data Tagihan ini digunakan untuk melihat dan menghapus data tagihan / kewajiban siswa.
      </p>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Data Tagihan</h4>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-12 col-lg-4">
                  <div class="row">
                    <div class="col-8">
                      <div class="form-group">
                        <label for="">Filter Siswa</label>
                        <input type="text" name="id_siswa" id="filter-siswa" class="form-control" placeholder="Scan Kartu / Pilih" autocomplte="off" />
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="form-group">
                        <label for="">&nbsp;</label>
                        <button class="form-control btn btn-dark" id="filter-pilih_siswa"><i class="fa fa-user"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-lg-4">
                  <div class="form-group">
                    <label for="">Filter Tangal</label>
                    <input type="text" name="filter_tanggal" id="filter_tanggal" class="form-control daterange-cus">
                  </div>
                </div>
                <div class="col-12 col-lg-3">
                  <div class="form-group">
                    <label for="">Filter Akun Pembayaran</label>
                    <select name="id_akun" id="filter-akun" class="form-control select2"></select>
                  </div>
                </div>
                <div class="col-12 col-lg-1">
                  <div class="form-group">
                    <label for="">&nbsp;</label>
                    <button class="btn btn-danger form-control" id="filter-reset"><i class="fa fa-times"></i></button>
                  </div>
                </div>
              </div>

              <div class="clearfix mb-3"></div>
              <div>
                <h6 class="text-primary">List Tagihan Siswa : </h6>
              </div>
              <div class="table-responsive">
                <table class="table table-bordered table-lg" id="tbl-tagihan">
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

  <div class="modal fade" role="dialog" id="modal-pilihan">
    <div class="modal-dialog" role="document" style="margin-top: 10%">
      <div class="modal-content">
        <form action="aaaa" name="modal-pilihan-form" id="modal-pilihan-form" method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-pilihan-cop"><i class="fa fa-search"></i> Cari Data Siswa</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row" id="badan-pemilihan">
            <div class="col-12">
              <div class="form-group">
                <label id="">Pilih Kelas</label>
                <select name="id_kelas" id="modal-pilihan-id_kelas" class="form-control select2">
                  <option value="init">Initial</option>
                </select>
                <input type="hidden" name="tipe" id="modal-pilihan-tipe"/>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group">
                <label id="">Pilih  Siswa</label>
                <select name="id_siswa" id="modal-pilihan-id_siswa" class="form-control select2">
                  <option value="init">Initial</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
          <button type="submit" class="btn btn-primary" id="modal-pilihan-submit"><i class="fa fa-check"></i> Add !</button>
        </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" role="dialog" id="modal-import"  style="margin-top: 5%">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-import-cop"><i class="fa fa-upload"></i> Import Tagihan dari Excel</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="" method="post" enctype="multipart/form-data" id="modal-import-form">
            <div class="row">
              <div class="col-12">
                <img src="<?=$siteUrl?>/assets/images/tagihan.png" class="rounded" style="width:100%" alt="Panduan">
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
<script src="<?=$siteUrl?>assets/custom-js/tagihan.js"></script>
<script>
  window.history.pushState(null, null, window.location.pathname)
  resetFilter()
  getTagihan()
  filterAkun()
</script>