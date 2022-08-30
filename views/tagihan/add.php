<?php
global $SConfig;
$siteUrl = $SConfig->_site_url;
?>

<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Input Tagihan</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active">
          <a href="#">Transaksi</a>
        </div>
        <div class="breadcrumb-item active">
          <a href="#">Data Tagihan</a>
        </div>
        <div class="breadcrumb-item">Input Tagihan</div>
      </div>
    </div>

    <div class="section-body" id="dashboard-data">
      <h2 class="section-title">Input Tagihan</h2>
      <p class="section-lead">
        Input Tagihan ini digunakan untuk Menginput tagihan / kewajiban siswa.
      </p>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Formulir Tagihan</h4>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-12 col-lg-6">
                  <div class="form-group">
                    <label for="">Akun Pembayaran</label>
                    <select name="id_akun" id="formulir-id_akun" class="select2 form-control"></select>
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="form-group">
                    <label for="">Nominal</label>
                    <input type="text" name="nominal" id="formulir-nominal" class="form-control" autocomplete="off" onchange="bilBul($('#formulir-nominal'))" onkeyup="bilBul($('#formulir-nominal'))"/>
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="form-group">
                    <label for="">Tenggat Waktu</label>
                    <input type="text" name="tenggat_waktu" id="formulir-tenggat_waktu" class="form-control datepicker" autocomplete="off" />
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="form-group">
                    <label for="">Catatan</label>
                    <input type="text" name="catatan" id="formulir-catatan" class="form-control" autocomplete="off" />
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <div class="row">
                <div class="col-12">
                  <h6 class="text-primary">Tambah Siswa</h6>
                </div>
                <div class="col-5">
                  <input type="text" name="text_siswa" id="input-text_siswa" class="form-control" autocomplete="off" />
                </div>
                <div class="col-1">
                  <button class="form-control btn btn-dark" id="pilih_siswa"><i class="fa fa-users"></i></button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>List Siswa</h4>
            </div>
            <div class="card-body">
              <div style="max-height: 300px; overflow: auto;" class="table-responsive">
                <table class="table table-bordered table-lg" id="tabel-input-tagihan">
                  <thead>
                    <th class="text-center">#</th>
                    <th class="text-center">NISN</th>
                    <th class="text-center">Nama Siswa</th>
                    <th class="text-center">Kelas</th>
                    <th class="text-center">Hapus</th>
                  </thead>
                  <tbody>
                    
                  </tbody>
                </table>
              </div>
            </div>
            <div class="card-footer text-right">
              <nav class="d-inline-block">
                <ul class="pagination mb-0" id="pagination-wraper">
                  <li class="active">
                    <a href="#" class="btn btn-primary" id="formulir-simpan">
                      <i class="fa fa-save"></i> Simpan Tagihan
                    </a>
                  </li>
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

</div>

<script src="<?=$siteUrl?>assets/js/socket.io.min.js"></script>
<script src="<?=$siteUrl?>assets/plugins/twbs-pagination/twbs.min.js"></script>
<script src="<?=$siteUrl?>assets/custom-js/tagihan.js"></script>
<script>
  window.history.pushState(null, null, window.location.pathname)
  newTransaction()
</script>