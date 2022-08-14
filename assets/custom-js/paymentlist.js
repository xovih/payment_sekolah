let path = window.location.pathname

const tbl = $("#tbl-payment")
const tblTitle = [
  {
    title: "#",
    className: "text-center"
  },
  {
    title: "Kode Akun",
    className: ""
  },
  {
    title: "Jenis Pembayaran",
    className: ""
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

function getList(search = null, page = 1, scroll = false) {
  $.ajax({
    url: `${siteUrl}/paymentlist/action/list`,
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
        tbl.find("tbody").append(
          `
            <tr>
              <td class="text-center">${d.no}</td>
              <td nowrap>
                ${d.kode_akun}&nbsp;
                  <span class="table-links">
                    <a href="#update?id=${d.id_akun}" class="text-warning">Update</a>
                    <div class="bullet"></div>
                    <a href="#delete?id=${d.id_akun}" class="text-danger">Delete</a>
                  </span>
              </td>
              <td nowrap> ${d.nama_akun}</td>
              <td class="text-center" nowrap>
                <a href="#update?id=${d.id_akun}" class="btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                <a href="#delete?id=${d.id_akun}" class="btn-sm btn-danger"><i class="fa fa-trash"></i></a>
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

$(window).hashchange(function () {
  const hash = $.param.fragment()

  if (path.search('paymentlist') > 0) {
    if (hash.search("get") == 0) {
      const query = getUrlVars()
      const q = query["q"]
      const p = query["p"]
      const s = p ? true : false

      getList(q, p, s)
    } else if (hash.search('add') == 0) {
      $("#modal-payment-cop").html(`<i class="fa fa-book"></i> Tambah Akun`)
      $("#modal-payment-form").attr("action", "add")
      $("#modal-payment-submit").html(`<i class="fa fa-check"></i> Add !`)
      $("#modal-payment").modal("show")

    } else if (hash.search('update') == 0) {
      const query = getUrlVars()
      const id = query["id"]

      const akuns = getJSON(`${siteUrl}/paymentlist/action/get`, { id })

      if (akuns.success) {
        const akun = akuns.data

        $("#modal-payment-cop").html(`<i class="fa fa-edit"></i> Update Jenis Pembayaran <span class="text-danger">${akun.kode_akun}</span>`)
        $("#modal-payment-form").attr("action", "update")
        $("#modal-payment-id_akun").val(akun.id_akun)
        $("#modal-payment-kode_akun").val(akun.kode_akun)
        $("#modal-payment-nama_akun").val(akun.nama_akun)

        $("#modal-payment-submit").html(`<i class="fa fa-check"></i> Update !`)
        $("#modal-payment").modal("show")

      } else {
        toastr.error("Kelas tidak ditemukan !")
        window.history.pushState(null, null, path)
      }


    } else if (hash.search('delete') == 0) {
      const query = getUrlVars()
      const id = query["id"]

      const akuns = getJSON(`${siteUrl}/paymentlist/action/get`, { id })

      if (akuns.success) {

        const kl = akuns.data

        $("#modal-delete-cop").html(`<i class="fa fa-trash"></i> Konfirmasi Delete`)
        $("#modal-delete-form").attr("action", "delete")
        $("#modal-delete-text").html(`Apakah anda yakin akan menghapus Data Akun Pembayaran <span class="text-danger"><b>${kl.kode_akun} - ${kl.nama_akun}</b></span> ?`)
        $("#modal-delete-delete_id").val(kl.id_akun)

        $("#modal-delete-submit").html(`<i class="fa fa-check"></i> Hapus !`)
        $("#modal-delete-submit").removeClass(`btn-primary`)
        $("#modal-delete-submit").addClass(`btn-danger`)

        $("#modal-delete").modal("show")
      } else {
        toastr.error("Dept not Found !")
        window.history.pushState(null, null, path)
      }
    }
  }

  $('#modal-payment').on('hidden.bs.modal', function () {
    window.history.pushState(null, null, path)
    $("#modal-payment-form").get(0).reset()
  })

  $('#modal-delete').on('hidden.bs.modal', function () {
    window.history.pushState(null, null, path)
    $("#modal-delete-form").get(0).reset()
  })

})

const btnSubmit = $("#modal-payment-submit")
const btnHapusSubmit = $("#modal-delete-submit")

btnSubmit.on("click", (e) => {
  e.preventDefault()
  const mForm = $("#modal-payment-form")
  const action = mForm.attr("action")
  if (validate()) {
    $.ajax({
      url: `${siteUrl}/paymentlist/action/${action}`,
      type: "POST",
      data: mForm.serialize(),
      dataType: "json",
      success: function (result) {

        if (result.success) {
          toastr.success(result.message)
          $("#modal-payment").modal("hide")
          getList()
        } else {
          const msg = result.message
          $("#modal-payment .modal-body").append(
            `
            <div class="row" id="modal-payment-alert">
              <div class="col-12">
                <div class="alert alert-danger">
                  ${msg}
                </div>
              </div>
            </div>
            `
          )

          setTimeout(() => {
            $("#modal-payment-alert").remove()
          }, 4000)
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        if (textStatus == "parsererror") window.location.href = `${siteUrl}${window.location.pathname}`
        toastr.error(textStatus)
      }
    })
  } else {
    setTimeout(() => {
      $("#modal-payment-alert").remove()
    }, 4000)
  }
})

btnHapusSubmit.on("click", (e) => {
  e.preventDefault()
  const mForm = $("#modal-delete-form")
  $.ajax({
    url: `${siteUrl}/paymentlist/action/delete`,
    type: "POST",
    data: mForm.serialize(),
    dataType: "json",
    success: function (result) {

      if (result.success) {
        toastr.success(result.message)
        $("#modal-delete").modal("hide")
        getList()
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

function validate() {
  let msg = ""

  const kode = $("#modal-payment-kode_akun").val()
  const nama = $("#modal-payment-nama_akun").val()

  if (kode.length < 1) {
    msg += "Kode Akun Tidak Valid, "
  }
  if (nama.length < 1) {
    msg += "Nama Akun Tidak Valid, "
  }

  if (msg.length > 0) {
    $("#modal-payment .modal-body").append(
      `
        <div class="row" id="modal-payment-alert">
          <div class="col-12">
            <div class="alert alert-danger">
              ${msg}
            </div>
          </div>
        </div>
      `
    )

    return false
  } else {
    return true
  }
}