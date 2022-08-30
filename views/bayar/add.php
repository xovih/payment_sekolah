<?php
global $SConfig;
$siteUrl = $SConfig->_site_url;
?>

<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Pemabayaran Tagihan</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active">
          <a href="#">Transaksi</a>
        </div>
        <div class="breadcrumb-item active">
          <a href="#">Data Tagihan</a>
        </div>
        <div class="breadcrumb-item">Pemabayaran Tagihan</div>
      </div>
    </div>

    <div class="section-body" id="dashboard-data">
      <h2 class="section-title">Pemabayaran Tagihan</h2>
      <p class="section-lead">
        Pemabayaran Tagihan ini digunakan untuk Melakukan Transaksi Pembayaran tagihan / kewajiban siswa.
      </p>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Formulir Pembayaran</h4>
            </div>
            <div class="card-body">
              <form action="add" class="eventInsForm" method="post" id="form-pembayaran">
                <div class="row">
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label for="">Data Siswa</label>
                      <input type="text" name="" id="pilihan_siswa" class="form-control" />
                      <input type="hidden" name="id_siswa" id="id_siswa">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label for="">Kode Tagihan</label>
                      <select name="" id="id_tagihan" class="select2 form-control"></select>
                    </div>
                  </div>
                  <div class="col-12 col-lg-3">
                    <div class="form-group">
                      <label for="">Nominal Tagihan</label>
                      <input type="text" name="nominal_tagihan" readonly="tru" id="nominal_tagihan" class="form-control">
                    </div>
                  </div>
                  <div class="col-12 col-lg-3">
                    <div class="form-group">
                      <label for="">Telah Terbayar</label>
                      <input type="text" name="nominal_terbayar" readonly="tru" id="nominal_terbayar" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-6">
                    <div class="form-group">
                      <label for="">Nominal Pembayaran</label>
                      <input type="text" name="nominal_pembayaran" id="nominal_pembayaran" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="">Catatan Pembayaran</label>
                      <textarea name="catatan" id="catatan" class="form-control"></textarea>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="card-footer">
              <nav class="d-inline-block">
                <ul class="pagination mb-0" id="pagination-wraper">
                  <li class="active">
                    <a href="#" class="btn btn-primary" id="formulir-bayar">
                      <i class="fa fa-check"></i> Bayar Tagihan !
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
<script src="<?=$siteUrl?>assets/custom-js/pembayaran.js"></script>
<script>
  window.history.pushState(null, null, window.location.pathname)
  opsiSiswa()
</script>