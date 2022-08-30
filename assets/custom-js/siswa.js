let path = window.location.pathname

const tbl = $("#tbl-siswa")
const tblTitle = [
  {
    title: "#",
    className: "text-center"
  },
  {
    title: "No Induk",
    className: ""
  },
  {
    title: "Nama Siswa",
    className: "",
  },
  {
    title: "Jenis Kelamin",
    className: "",
  },
  {
    title: "Kelas",
    className: "text-center",
  },
  {
    title: "Aksi",
    className: "text-center",
  },
]

function buildTable() {
  tbl.find("thead").find("tr").remove()
  tbl.find("tbody").find("tr").remove()

  let title = `<tr>`

  tblTitle.forEach((t) => {
    title += `<th class="${t.className}">${t.title}</th>`
  })
  title += `</tr>`

  tbl.find("thead").append(`${title}`)
}

const txtSearch = $("#search")
txtSearch.on("keyup", function (e) {
  e.preventDefault()
  const sr = $(this).val()
  window.location.hash = `#get?q=${sr}`
})

function getSiswa(search = null, page = 1, scroll = false) {
  $.ajax({
    url: `${siteUrl}/siswa/action/list`,
    data: {
      search,
      page
    },
    dataType: "json",
    type: "POST",
    success: function (result) {
      buildTable()
      const dept = result.data.data
      $.each(dept, (i, d) => {
        const gender = d.jenis_kelamin == "P" ? "Perempuan" : "Laki-Laki"
        tbl.find("tbody").append(
          `
            <tr>
              <td class="text-center">${d.no}</td>
              <td>
                ${d.no_induk}
                <span class="table-links">
                  <a href="#update?id=${d.id_siswa}" class="text-warning">Update</a>
                  <div class="bullet"></div>
                  <a href="#delete?id=${d.id_siswa}" class="text-danger">Delete</a>
                </span>
              </td>
              <td>${d.nama}</td>
              <td>${gender}</td>
              <td class="text-center">${d.kelas_tingkat}${d.kelas_label}</td>
              <td class="text-center" nowrap>
                <a href="#update?id=${d.id_siswa}" class="btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                <a href="#delete?id=${d.id_siswa}" class="btn-sm btn-danger"><i class="fa fa-trash"></i></a>
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

      $('#page').empty()
      $('#page').removeData("twbs-pagination")
      $('#page').unbind("page")

      $('#page').twbsPagination({
        totalPages: totalPage,
        visiblePages: 5,
        startPage: parseInt(page),
        initiateStartPageClick: false,
        onPageClick: function (event, page) {
          window.location.hash = hr + page
        }

      })

      if (scroll == true) {
        $("html, body").animate({
          scrollTop: 0
        }, "slow")
        return false
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      if (textStatus == "parsererror") window.location.href = `${siteUrl}${window.location.pathname}`
      toastr.error(textStatus)
    }
  })
}

const buildKelas = function () {
  const opsi = $("#modal-siswa-id_kelas")
  opsi.find("option").remove()

  const dept = getJSON(`${siteUrl}/kelas/action/list`)

  if (dept.data.data.length > 0) {
    $.each(dept.data.data, (i, v) => {
      opsi.append(
        `
          <option value="${v.id_kelas}">${v.tingkat}${v.label}</option>
        `
      )
    })
  }
}

$(window).hashchange(function () {
  const hash = $.param.fragment()

  if (path.search('siswa') > 0) {
    if (hash.search("get") == 0) {
      const query = getUrlVars()
      const q = query["q"]
      const p = query["p"]
      const s = p ? true : false

      getSiswa(q, p, s)
    } else if (hash.search('add') == 0) {
      $("#modal-siswa-cop").html(`<i class="fa fa-feather-alt"></i> Tambah Data Siswa`)
      $("#modal-siswa-form").attr("action", "add")

      // $(".harusnumer").inputFilter(function (value) {
      //   return /^\d*$/g.test(value)
      // }, "Harus Angka !")

      $(".harusalfa").inputFilter(function (value) {
        return /^[a-zA-Z\s]*$/g.test(value)
      }, "Harus Alfabet !")

      buildKelas()

      $("#modal-siswa-submit").html(`<i class="fa fa-check"></i> Add !`)
      $("#modal-siswa").modal("show")

    } else if (hash.search('update') == 0) {
      const query = getUrlVars()
      const id = query["id"]

      const depts = getJSON(`${siteUrl}/siswa/action/get`, { id })

      if (depts.success) {
        const emp = depts.data

        buildKelas()

        $("#modal-siswa-cop").html(`<i class="fa fa-edit"></i> Ubah Data Siswa <span class="text-danger">${emp.no_induk}</span>`)
        $("#modal-siswa-form").attr("action", "update")
        $("#modal-siswa-id_siswa").val(emp.id_siswa)
        $("#modal-siswa-no_induk").val(emp.no_induk)
        $("#modal-siswa-nama").val(emp.nama)
        $("#modal-siswa-jenis_kelamin").val(emp.jenis_kelamin).change()
        $("#modal-siswa-id_kelas").val(emp.id_kelas).change()

        $(".harusalfa").inputFilter(function (value) {
          return /^[a-zA-Z\s]*$/g.test(value)
        }, "Harus Alfabet !")

        $("#modal-siswa-submit").html(`<i class="fa fa-check"></i> Update !`)
        $("#modal-siswa").modal("show")

      } else {
        toastr.error("Data Siswa Tidak Ditemukan !")
        window.history.pushState(null, null, path)
      }


    } else if (hash.search('delete') == 0) {
      const query = getUrlVars()
      const id = query["id"]

      const depts = getJSON(`${siteUrl}/siswa/action/get`, { id })

      if (depts.success) {

        const emp = depts.data

        $("#modal-delete-cop").html(`<i class="fa fa-trash"></i> Konffirmasi Hapus Siswa`)
        $("#modal-delete-form").attr("action", "delete")
        $("#modal-delete-text").html(`Apakah anda yakin akan menghapus Siswa <span class="text-danger"><b>${emp.no_induk} - ${emp.nama}</b></span> ?`)
        $("#modal-delete-delete_id").val(emp.id_siswa)

        $("#modal-delete-submit").html(`<i class="fa fa-check"></i> Hapus !`)
        $("#modal-delete-submit").removeClass(`btn-primary`)
        $("#modal-delete-submit").addClass(`btn-danger`)

        $("#modal-delete").modal("show")
      } else {
        ttoastr.error("Data Siswa Tidak Ditemukan !")
        window.history.pushState(null, null, path)
      }
    } else if (hash.search('import') == 0) {
      $("#modal-import").modal("show")
    }
  }

  $('#modal-siswa').on('hidden.bs.modal', function () {
    window.history.pushState(null, null, path)
    $("#modal-siswa-form").get(0).reset()
  })

  $('#modal-import').on('hidden.bs.modal', function () {
    window.history.pushState(null, null, path)
    $("#modal-import-form").get(0).reset()
  })

  $('#modal-delete').on('hidden.bs.modal', function () {
    window.history.pushState(null, null, path)
    $("#modal-delete-form").get(0).reset()
  })

})

const btnSiswaSubmit = $("#modal-siswa-submit")
const btnHapusSubmit = $("#modal-delete-submit")

btnSiswaSubmit.on("click", (e) => {
  e.preventDefault()
  const mForm = $("#modal-siswa-form")
  const action = mForm.attr("action")
  if (validate()) {
    $.ajax({
      url: `${siteUrl}/siswa/action/${action}`,
      type: "POST",
      data: mForm.serialize(),
      dataType: "json",
      success: function (result) {
        if (result.success) {
          toastr.success(result.message)
          $("#modal-siswa").modal("hide")
          getSiswa()
        } else {
          const msg = result.message
          $("#modal-siswa .modal-body").append(
            `
            <div class="row" id="modal-siswa-alert">
              <div class="col-12">
                <div class="alert alert-danger">
                  ${msg}
                </div>
              </div>
            </div>
            `
          )

          setTimeout(() => {
            $("#modal-siswa-alert").remove()
          }, 4000)
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        if (textStatus == "parsererror") window.location.href = `${siteUrl}${window.location.pathname}`
        toastr.error(textStatus)
      }
    })
  }
})

btnHapusSubmit.on("click", (e) => {
  e.preventDefault()
  const mForm = $("#modal-delete-form")
  $.ajax({
    url: `${siteUrl}/siswa/action/delete`,
    type: "POST",
    data: mForm.serialize(),
    dataType: "json",
    success: function (result) {

      if (result.success) {
        toastr.success(result.message)
        $("#modal-delete").modal("hide")
        getSiswa()
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

function validate() {
  let msg = ""

  const no_induk = $("#modal-siswa-no_induk").val()
  const nama = $("#modal-siswa-nama").val()

  if (no_induk.length < 2) {
    msg += "No Induk minimal 2 Char, "
  }

  if (nama.length < 4) {
    msg += "Nama minimal 4 Char, "
  }

  if (msg.length > 0) {
    $("#modal-siswa .modal-body").append(
      `
        <div class="row" id="modal-siswa-alert">
          <div class="col-12">
            <div class="alert alert-danger">
              ${msg}
            </div>
          </div>
        </div>
      `
    )

    setTimeout(() => {
      $("#modal-siswa-alert").remove()
    }, 4500)

    return false
  } else {
    return true
  }
}

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
  $.ajax({
    url: `${siteUrl}/siswa/action/importExcel`,
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
      const { success, message } = result
      if (success) {
        $("#modal-import").modal("hide")
        toastr.success(message)

        getSiswa()
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