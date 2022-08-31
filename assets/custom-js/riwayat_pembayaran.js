let path = window.location.pathname

const tbl = $("#tbl-bayar")
const tblTitle = [
  {
    title: "#",
    className: "text-center",
  },
  {
    title: "Tgl. Transaksi",
    className: "",
  },
  {
    title: "NISN",
    className: "",
  },
  {
    title: "Nama Siswa",
    className: "",
  },
  {
    title: "Kelas",
    className: "text-center",
  },
  {
    title: "Jenis Tagihan",
    className: "",
  },
  {
    title: "Nominal",
    className: "text-center",
  },
  {
    title: "Status",
    className: "text-center",
  },
  {
    title: "Action",
    className: "text-center",
  },
]

function buildTable() {
  tbl.find("thead").find("tr").remove()
  tbl.find("tbody").find("tr").remove()

  let title = `<tr>`

  tblTitle.forEach((t) => {
    title += `<th class="${t.className}" nowrap>${t.title}</th>`
  })
  title += `</tr>`

  tbl.find("thead").append(`${title}`)
}

function getRiwayat(search = null, page = 1, scroll = false) {
  const id_siswa = localStorage.getItem("id_siswa") ?? null
  const id_akun = localStorage.getItem("id_akun") ?? null
  const filter_tanggal = localStorage.getItem("filter_tanggal") ?? null
  const awal_filter = filter_tanggal
    ? localStorage.getItem("awal_filter")
    : null
  const akir_filter = filter_tanggal
    ? localStorage.getItem("akir_filter")
    : null
  $.ajax({
    url: `${siteUrl}/pembayaran/action/list`,
    data: {
      search,
      page,
      id_siswa,
      id_akun,
      filter_tanggal,
      awal_filter,
      akir_filter,
    },
    dataType: "json",
    type: "POST",
    success: function (result) {
      buildTable()
      const dt = result.data.data
      $.each(dt, (i, d) => {
        let status = `<span class="badge badge-warning">Belum Lunas<span>`
        if (d.terbayar >= parseInt(d.nominal)) {
          status = `<span class="badge badge-success">Lunas<span>`
        } else if (d.terbayar == 0) {
          status = `<span class="badge badge-secondary">Belum Dibayar<span>`
        }

        tbl.find("tbody").append(
          `
            <tr>
              <td class="text-center">
                ${d.no} &nbsp;
                <span class="table-links">
                  <a href="#cetak?id=${d.id_pembayaran}" class="text-success">Cetak</a>
                </span>
              </td>
              <td nowrap>
                ${moment(d.waktu_transaksi).format("DD-MM-YYYY")}
              </td>
							<td nowrap>${d.no_induk}</td>
							<td nowrap>${d.nama}</td>
							<td class="text-center">${d.kelas_tingkat}${d.kelas_label}</td>
							<td>${d.kode_akun} - ${d.nama_akun} (Rp. ${formatRupiah(d.nominal)})</td>
							<td class="text-center">${formatRupiah(d.jumlah_bayar)}</td>
							<td class="text-center">${status}</td>
              <td class="text-center" nowrap>
                <a href="#cetak?id=${d.id_pembayaran}" class="btn-sm btn-dark"><i class="fa fa-print"></i> Cetak</a>
                <a href="#delete?id=${d.id_pembayaran}" class="btn-sm btn-danger"><i class="fa fa-trash"></i> Hapus</a>
              </td>
            <tr>
          `
        )
      })

      let hr = "#get?p="

      if (search) {
        hr = "#get?q=" + search + "&p="
      }

      let totalPage = result.data.totalpage
      if (result.data.totalrows < 1) totalPage = 1

      $("#page").empty()
      $("#page").removeData("twbs-pagination")
      $("#page").unbind("page")

      $("#page").twbsPagination({
        totalPages: totalPage,
        visiblePages: 5,
        startPage: parseInt(page),
        initiateStartPageClick: false,
        onPageClick: function (event, page) {
          window.location.hash = hr + page
        },
      })

      if (scroll == true) {
        $("html, body").animate(
          {
            scrollTop: 0,
          },
          "slow"
        )
        return false
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      if (textStatus == "parsererror")
        window.location.href = `${siteUrl}${window.location.pathname}`
      toastr.error(textStatus)
    },
  })
}

$(window).hashchange(function () {
  const hash = $.param.fragment()

  if (path.search("pembayaran") > 0) {
    if (hash.search('import') == 0) {
      $("#modal-import").modal("show")
    }
    else if (hash.search('delete') == 0) {
      const query = getUrlVars()
      const id = query["id"]

      const trans = getJSON(`${siteUrl}/pembayaran/action/get`, { id })

      if (trans.success) {

        const tr = trans.data

        $("#modal-delete-cop").html(`<i class="fa fa-trash"></i> Konfirmasi Hapus Transaksi`)
        $("#modal-delete-form").attr("action", "delete")
        $("#modal-delete-text").html(`Apakah anda yakin akan menghapus Transaksi <span class="text-danger"><b>${tr.nama} - ${tr.kode_akun} (${tr.nama_akun} - Rp. ${formatRupiah(tr.jumlah_bayar)})</b></span> ?`)
        $("#modal-delete-delete_id").val(tr.id_pembayaran)

        $("#modal-delete-submit").html(`<i class="fa fa-check"></i> Hapus !`)
        $("#modal-delete-submit").removeClass(`btn-primary`)
        $("#modal-delete-submit").addClass(`btn-danger`)

        $("#modal-delete").modal("show")
      } else {
        window.history.pushState(null, null, window.location.pathname)
      }
    }
    else if (hash.search('cetak') == 0) {
      const id = getUrlVars()["id"]
      cetakresi(id)
      window.history.pushState(null, null, window.location.pathname)
    }

    $('#modal-import').on('hidden.bs.modal', function () {
      window.history.pushState(null, null, path)
      $("#modal-import-form").get(0).reset()
    })

    $('#modal-delete').on('hidden.bs.modal', function () {
      window.history.pushState(null, null, path)
      $("#modal-delete-form").get(0).reset()
    })
  }

})


// INPUT Tagihan Start Dari sini
const bilBul = (komponen) => {
  const bilangan = formatAngka(komponen.val())
  komponen.val(formatRupiah(bilangan))
}

const newTransaction = function () {
  opsiAkun()
  $("#formulir-nominal").val("")
  $("#formulir-catatan").val("")
  $("#formulir-tenggat_waktu")
    .val(moment(new Date()).format("YYYY-MM-DD"))
    .change()
  $("#tabel-input-tagihan tbody").find("tr").remove()
}

const opsiAkun = function () {
  const reqAkun = getJSON(`${siteUrl}/paymentlist/action/list`)
  const opsi = $("#formulir-id_akun")
  opsi.find("option").remove()

  if (reqAkun.success) {
    const akun = reqAkun.data.data

    if (akun.length > 0) {
      $.each(akun, (i, v) => {
        opsi.append(
          `<option value="${v.id_akun}">${v.kode_akun} - ${v.nama_akun}</option>`
        )
      })
    }
  }
}

const opsiKelas = function () {
  const reqKelas = getJSON(`${siteUrl}/kelas/action/list`)
  const opsi = $("#modal-pilihan-id_kelas")

  opsi.find("option").remove()

  if (reqKelas.success) {
    const kelas = reqKelas.data.data
    if (kelas.length > 0) {
      $.each(kelas, (i, v) => {
        opsi.append(
          `
            <option value="${v.id_kelas}">Kelas ${v.tingkat}${v.label}</option>
          `
        )
      })
    }
  }
}

$("#modal-pilihan-id_kelas").on("change", (e) => { opsiSiswa() })

const opsiSiswa = function () {
  const kelas = $("#modal-pilihan-id_kelas :selected").val()
  const reqSiswa = getJSON(`${siteUrl}/siswa/action/list`, { id_kelas: kelas })
  const opsi = $("#modal-pilihan-id_siswa")

  opsi.find("option").remove()

  if (reqSiswa.success) {
    const siswa = reqSiswa.data.data

    if (siswa.length > 0) {
      opsi.append(`<option value="all">Pilih Semua</option>`)

      $.each(siswa, (i, v) => {
        opsi.append(
          `<option value="${v.id_siswa}">${v.no_induk} - ${v.nama}</option>`
        )
      })
    }
  }
}

const validasiPilihan = function () {
  const pilihan = $("#modal-pilihan-id_siswa :selected").val()
  if (pilihan == undefined) {
    const modal = $("#badan-pemilihan")
    modal.append(
      `
				<div class="col-12" id="pilihan-error">
					<div class="alert alert-danger">
						Silahkan Pilih Siswa Dahulu !
					</div>
				</div>
			`
    )

    return false
  }

  return true
}

$("#pilih_siswa").on("click", (e) => {
  e.preventDefault()

  opsiKelas()
  opsiSiswa()
  $("#modal-pilihan-tipe").val("add")
  $("#modal-pilihan").modal("show")
})

$("#modal-pilihan-submit").on("click", function (e) {
  e.preventDefault()

  if (validasiPilihan()) {
    const pilihan = $("#modal-pilihan-id_siswa :selected").val()
    const kelas = $("#modal-pilihan-id_kelas :selected").val()
    const tipe = $("#modal-pilihan-tipe").val()
    if (tipe == "filter") {
      localStorage.setItem("id_siswa", pilihan)
      $("#filter-siswa").val($("#modal-pilihan-id_siswa :selected").text())
      getRiwayat(null, 1, false)
    }

    $("#modal-pilihan").modal("hide")
  } else {
    setTimeout(() => {
      $("#pilihan-error").remove()
    }, 3000)
  }
})

// FILTER Tagihan
const opsiSiswaFilter = function () {
  const kelas = $("#modal-pilihan-id_kelas :selected").val()
  const reqSiswa = getJSON(`${siteUrl}/siswa/action/list`, { id_kelas: kelas })
  const opsi = $("#modal-pilihan-id_siswa")

  opsi.find("option").remove()

  if (reqSiswa.success) {
    const siswa = reqSiswa.data.data

    if (siswa.length > 0) {
      $.each(siswa, (i, v) => {
        opsi.append(
          `<option value="${v.id_siswa}">${v.no_induk} - ${v.nama}</option>`
        )
      })
    }
  }
}

$("#filter-pilih_siswa").on("click", (e) => {
  e.preventDefault()

  opsiKelas()
  opsiSiswaFilter()
  $("#modal-pilihan-tipe").val("filter")
  $("#modal-pilihan").modal("show")
})

const filterAkun = function () {
  const reqAkun = getJSON(`${siteUrl}/paymentlist/action/list`)
  const opsi = $("#filter-akun")
  opsi.find("option").remove()

  if (reqAkun.success) {
    const akun = reqAkun.data.data

    if (akun.length > 0) {
      opsi.append(`<option value="0">Filter Kode Akun</option>`)
      $.each(akun, (i, v) => {
        opsi.append(
          `<option value="${v.id_akun}">${v.kode_akun} - ${v.nama_akun}</option>`
        )
      })
    }
  }
}

$("#filter-akun").on("change", (e) => {
  e.preventDefault()
  const akun = $("#filter-akun :selected").val()
  if (akun != "none") localStorage.setItem("id_akun", akun)
  getRiwayat()
})

$("#filter-reset").on("click", (e) => {
  e.preventDefault()
  resetFilter()
  getRiwayat()
})

const resetFilter = function () {
  localStorage.removeItem("id_akun")
  localStorage.removeItem("id_siswa")
  localStorage.removeItem("filter_tanggal")

  $("#filter-siswa").val("")
  $("#filter-akun").val($("#filter-akun option:first").val())
}

const fNow = moment(new Date()).format("YYYY-01-01")
$("#filter_tanggal").daterangepicker({
  startDate: fNow,
  locale: {
    format: "YYYY-MM-DD",
  },
  drops: "down",
  opens: "right",
})

$("#filter_tanggal").on("change", () => {
  const dayOne = moment(
    $("#filter_tanggal").data("daterangepicker").startDate._d
  ).format("YYYY-MM-DD")
  const dayTwo = moment(
    $("#filter_tanggal").data("daterangepicker").endDate._d
  ).format("YYYY-MM-DD")

  localStorage.setItem("filter_tanggal", "true")
  localStorage.setItem("awal_filter", dayOne)
  localStorage.setItem("akir_filter", dayTwo)
  getRiwayat()
})

$("#filter-siswa").on("change", function (e) {
  const no_induk = $("#filter-siswa").val()

  const reqSiswa = getJSON(`${siteUrl}/siswa/action/list`, { no_induk })

  if (reqSiswa.success) {
    if (reqSiswa.data.data.length > 0) {
      const siswa = reqSiswa.data.data[0]
      const fsiswa = siswa.no_induk + " - " + siswa.nama
      $("#filter-siswa").val(fsiswa)
      localStorage.setItem("id_siswa", siswa.id_siswa)
      getRiwayat()
    } else {
      toastr.error("Data Siswa Tidak Ditemukan !")
      setTimeout(() => {
        $("#filter-siswa").val("")
      }, 2000)
    }
  } else {
    toastr.error("Data Siswa Tidak Ditemukan !")
    setTimeout(() => {
      $("#filter-siswa").val("")
    }, 2000)
  }
})

$("#modal-import-submit").on("click", (e) => {
  e.preventDefault()

  const fieldID = "modal-import-file"

  if (document.getElementById(fieldID).files.length == 0) {
    $("#modal-import .modal-body").append(
      `
      <div class="row" id="modal-import-alert">
        <div class="col-12">
          <div class="alert alert-danger">
            Belum ada file terpilih !
          </div>
        </div>
      </div>
      `
    )

    setTimeout(() => {
      $("#modal-import-alert").remove()
    }, 3500)

  }

  if (validasiFileUpload(fieldID)) {
    return handleExcelImport(fieldID)
  } else {
    $("#modal-import .modal-body").append(
      `
      <div class="row" id="modal-import-alert">
        <div class="col-12">
          <div class="alert alert-danger">
            File Tidak Valid !
          </div>
        </div>
      </div>
      `
    )

    setTimeout(() => {
      $("#modal-import-alert").remove()
    }, 4000)
  }
})

function validasiExcel(fieldID) {
  const fileUpload = document.getElementById(fieldID)

  //Validate whether File is valid Excel file.
  let regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/
  if (regex.test(fileUpload.value.toLowerCase())) return true

  return false
}

function validasiFileUpload(fieldID) {
  if (validasiExcel(fieldID)) return true

  return false
}

const handleExcelImport = (fieldID) => {
  //Reference the FileUpload element.
  const fileUpload = document.getElementById(fieldID)

  //Validate whether File is valid Excel file.
  let regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/
  if (regex.test(fileUpload.value.toLowerCase())) {
    if (typeof (FileReader) != "undefined") {
      let reader = new FileReader()

      //For Browsers other than IE.
      if (reader.readAsBinaryString) {
        reader.onload = function (e) {
          const datah = e.target.result

          let workbook = XLSX.read(datah, {
            type: 'binary'
          })

          //Fetch the name of First Sheet.
          let firstSheet = workbook.SheetNames[0]

          //Read all rows from First Sheet into an JSON array.
          let excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet])

          postExcelData(excelRows)
        }
        reader.readAsBinaryString(fileUpload.files[0])
      } else {
        //For IE Browser.
        reader.onload = function (e) {
          let data = ""
          let bytes = new Uint8Array(e.target.result)
          for (let i = 0; i < bytes.byteLength; i++) {
            data += String.fromCharCode(bytes[i])
          }

          let workbook = XLSX.read(datah, {
            type: 'binary'
          })

          //Fetch the name of First Sheet.
          let firstSheet = workbook.SheetNames[0]

          //Read all rows from First Sheet into an JSON array.
          let excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet])

          postExcelData(excelRows)
        }
        reader.readAsArrayBuffer(fileUpload.files[0])
      }
    } else {
      toastr.error("Browser yang anda pakai tidak mendukung HTML5.")
    }
  } else {
    toastr.error("Silahkan pilih file excel yang valid !")
  }
}

const postExcelData = (data) => {
  console.log(data.length)
  $.ajax({
    url: `${siteUrl}/pembayaran/action/importExcel`,
    data: { data },
    type: "POST",
    dataType: "json",
    beforeSend: () => {
      $("div.overlao").css("display", "flex")
      $("header").css("display", "none")
      $("body").css("overflow", "hidden")
    },
    complete: () => {
      $("div.overlao").css("display", "none")
      $("header").css("display", "block")
      $("body").css("overflow", "auto")
    },
    success: (result) => {
      const { success, message, warning } = result
      if (success) {
        if (warning) {
          toastr.warning(message)
        } else {
          toastr.success(message)
        }
        $("#modal-import").modal("hide")

        resetFilter()
        getRiwayat()
        filterAkun()
      } else {
        $("#modal-import .modal-body").append(
          `
              <div class="row" id="modal-import-alert">
                <div class="col-12">
                  <div class="alert alert-danger">
                    ${message}
                  </div>
                </div>
              </div>
              `
        )

        setTimeout(() => {
          $("#modal-import-alert").remove()
        }, 4000)
      }

    },
    error: (jqXHR, textStatus, errorThrown) => {
      if (textStatus == "parsererror") window.location.href = `${siteUrl}/${path}`
      toastr.error(textStatus)
    },
  })
}

const cetakresi = function (id) {
  const getData = getJSON(`${siteUrl}/pembayaran/action/get`, { id })
  const data = getData.data

  if (getData.success) {
    let kop = `
		<table class="header">
			<tr>
				<td width="18%"><img src="${siteUrl}/assets/images/logo_mts.png" alt="Logo MTS" width="100%" class="center"></td>
				<td width="82%">
					<table class="instansi">
						<tr>
							<th>MTsN 7 KEDITI</th>
						</tr>
						<tr>
							<td>Jl. Kebonsari No. 1, Kencong, Kec. Kepung, Gresik.</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	
	
		<table class="deskripsi">
			<tr>
				<th class="kiri">No. Resi</th>
				<td class="tengah" width="3%">:</td>
				<td class="kiri" width="65%">${id}</td>
			</tr>
			<tr>
				<th class="kiri">Tg. Resi</th>
				<td class="tengah">:</td>
				<td class="kiri">${moment(new Date(data.waktu_transaksi)).format("DD-MM-YYYY HH:mm")}</td>
			</tr>
			<tr>
				<th class="kiri">Petugas</th>
				<td class="tengah">:</td>
				<td class="kiri">${data.nama_petugas}</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<th class="kiri">NISN</th>
				<td class="tengah">:</td>
				<td class="kiri">${data.no_induk}</td>
			</tr>
			<tr>
				<th class="kiri">Nama Siswa</th>
				<td class="tengah">:</td>
				<td class="kiri">${data.nama}</td>
			</tr>
			<tr>
				<th class="kiri">Kelas</th>
				<td class="tengah">:</td>
				<td class="kiri">${data.kelas_tingkat}${data.kelas_label}</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<th class="kiri">Catatan</th>
				<td class="tengah">:</td>
				<td class="kiri">${data.catatan_transaksi}</td>
			</tr>
		</table>
		`

    let isi = `
			<table class="price">
				<tr class="top">
					<th colspan="2">${data.kode_akun} - ${data.nama_akun}</th>
					</tr>
					<tr>
					<td class="kiri">Rp. ${formatRupiah(data.jumlah_bayar)}</td>
      	</tr>
				<tr class="top">
					<td>&nbsp;</td>
				</tr>
			</table>
		`

    let bot = `
		<table class="deskripsi">
			<tr>
				<th class="kiri">Grand Total</th>
				<td class="tengah" width="3%">:</td>
				<td class="kiri" width="65%">Rp. ${formatRupiah(data.jumlah_bayar)}</td>
			</tr>
			<tr>
				<th class="kiri">Tunai</th>
				<td class="tengah">:</td>
				<td class="kiri">Rp. ${formatRupiah(data.tunai)}</td>
			</tr>
			<tr>
				<th class="kiri">Kembalian</th>
				<td class="tengah">:</td>
				<td class="kiri">Rp. ${formatRupiah(data.sisa)}</td>
			</tr>
		</table>

		<p class="tengah" style="margin-right: 4% !important">Resi ini tercetak oleh sitem dan merupakan bukti pembayaran yang sah.</p>
		`

    let mywindow = window.open(
      "",
      "PRINT RESI PEMBAYARAN - MTsN 7 GRESIK",
      "resizable=yes,width=720,height=" + screen.height
    )
    mywindow.document.write(
      `<!DOCTYPE html><html lang="en"><head><title>RESI PEMBAYARAN - MTsN 7 GRESIK"</title></head><link rel="stylesheet" href="${siteUrl}/assets/css/struckjual.css" media="all">`
    )
    mywindow.document.write(
      `<script src="${siteUrl}/assets/js/jquery.min.js"></`
    )
    mywindow.document.write(
      `script><script src="${siteUrl}/assets/custom-js/global_function.js"></`
    )
    mywindow.document.write("script><body>")
    mywindow.document.write(kop)
    mywindow.document.write(isi)
    mywindow.document.write(bot)
    mywindow.document.write("</body></html>")
    mywindow.document.close() // necessary for IE >= 10
    mywindow.focus() // necessary for IE >= 10

    let is_chrome = Boolean(window.chrome)
    if (is_chrome) {
      mywindow.onload = function () {
        setTimeout(function () {
          // wait until all resources loaded
          mywindow.print() // change window to winPrint
          mywindow.close() // change window to winPrint
        }, 200)
      }
    } else {
      mywindow.print()
      mywindow.close()
    }

    return true
  } else {
    toastr.error("Gagal Mencetak resi !")
  }
}

$("#modal-delete-submit").on("click", (e) => {
  e.preventDefault()
  const mForm = $("#modal-delete-form")
  $.ajax({
    url: `${siteUrl}/pembayaran/action/delete`,
    type: "POST",
    data: mForm.serialize(),
    dataType: "json",
    success: function (result) {

      if (result.success) {
        toastr.success(result.message)
        $("#modal-delete").modal("hide")
        getRiwayat()
      } else {
        const msg = result.message
        $("#modal-delete .modal-body").append(
          `
          <div class="row" id="modal-delete-alert">
            <div class="col-12">
              <div class="alert alert-danger">
                ${msg}
              </div>
            </div>
          </div>
          `
        )

        setTimeout(() => {
          $("#modal-delete-alert").remove()
        }, 4000)
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      if (textStatus == "parsererror") window.location.href = `${siteUrl}${window.location.pathname}`
      toastr.error(textStatus)
    }
  })
})