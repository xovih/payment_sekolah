let path = window.location.pathname

const tbl = $("#tbl-kelas")
const tblTitle = [
  {
    title: "#",
    className: "text-center"
  },
  {
    title: "Nama Kelas",
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

function getKelas(search = null, page = 1, scroll = false) {
  $.ajax({
    url: `${siteUrl}/kelas/action/list`,
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
                ${d.tingkat}${d.label} &nbsp;
                  <span class="table-links">
                    <a href="#update?id=${d.id_kelas}" class="text-warning">Update</a>
                    <div class="bullet"></div>
                    <a href="#delete?id=${d.id_kelas}" class="text-danger">Delete</a>
                  </span>
              </td>
              <td class="text-center" nowrap>
                <a href="#update?id=${d.id_kelas}" class="btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                <a href="#delete?id=${d.id_kelas}" class="btn-sm btn-danger"><i class="fa fa-trash"></i></a>
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

  if (path.search('kelas') > 0) {
    if (hash.search("get") == 0) {
      const query = getUrlVars()
      const q = query["q"]
      const p = query["p"]
      const s = p ? true : false

      getKelas(q, p, s)
    } else if (hash.search('add') == 0) {
      $("#modal-kelas-cop").html(`<i class="fa fa-feather-alt"></i> Tambah Kelas`)
      $("#modal-kelas-form").attr("action", "add")
      $("#modal-kelas-submit").html(`<i class="fa fa-check"></i> Add !`)
      $("#modal-kelas").modal("show")

    } else if (hash.search('update') == 0) {
      const query = getUrlVars()
      const id = query["id"]

      const kelass = getJSON(`${siteUrl}/kelas/action/get`, { id })

      if (kelass.success) {
        const kelas = kelass.data

        $("#modal-kelas-cop").html(`<i class="fa fa-edit"></i> Update Kelas <span class="text-danger">${kelas.tingkat}${kelas.label}</span>`)
        $("#modal-kelas-form").attr("action", "update")
        $("#modal-kelas-id_kelas").val(kelas.id_kelas)
        $("#modal-kelas-tingkat").val(kelas.tingkat).change()
        $("#modal-kelas-label").val(kelas.label)

        $("#modal-kelas-submit").html(`<i class="fa fa-check"></i> Update !`)
        $("#modal-kelas").modal("show")

      } else {
        toastr.error("Kelas tidak ditemukan !")
        window.history.pushState(null, null, path)
      }


    } else if (hash.search('delete') == 0) {
      const query = getUrlVars()
      const id = query["id"]

      const kelass = getJSON(`${siteUrl}/kelas/action/get`, { id })

      if (kelass.success) {

        const kl = kelass.data

        $("#modal-delete-cop").html(`<i class="fa fa-trash"></i> Konfirmasi Delete`)
        $("#modal-delete-form").attr("action", "delete")
        $("#modal-delete-text").html(`Apakah anda yakin akan menghapus Kelas <span class="text-danger"><b>${kl.tingkat}${kl.label}</b></span> ?`)
        $("#modal-delete-delete_id").val(kl.id_kelas)

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

  $('#modal-kelas').on('hidden.bs.modal', function () {
    window.history.pushState(null, null, path)
    $("#modal-kelas-tingkat").val("7").change()
    $("#modal-kelas-form").get(0).reset()
  })

  $('#modal-delete').on('hidden.bs.modal', function () {
    window.history.pushState(null, null, path)
    $("#modal-delete-form").get(0).reset()
  })

})

const btnKelasSubmit = $("#modal-kelas-submit")
const btnHapusSubmit = $("#modal-delete-submit")

btnKelasSubmit.on("click", (e) => {
  e.preventDefault()
  const mForm = $("#modal-kelas-form")
  const action = mForm.attr("action")
  if (validate()) {
    $.ajax({
      url: `${siteUrl}/kelas/action/${action}`,
      type: "POST",
      data: mForm.serialize(),
      dataType: "json",
      success: function (result) {

        if (result.success) {
          toastr.success(result.message)
          $("#modal-kelas").modal("hide")
          getKelas()
        } else {
          const msg = result.message
          $("#modal-kelas .modal-body").append(
            `
            <div class="row" id="modal-kelas-alert">
              <div class="col-12">
                <div class="alert alert-danger">
                  ${msg}
                </div>
              </div>
            </div>
            `
          )

          setTimeout(() => {
            $("#modal-kelas-alert").remove()
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
      $("#modal-kelas-alert").remove()
    }, 4000)
  }
})

btnHapusSubmit.on("click", (e) => {
  e.preventDefault()
  const mForm = $("#modal-delete-form")
  $.ajax({
    url: `${siteUrl}/kelas/action/delete`,
    type: "POST",
    data: mForm.serialize(),
    dataType: "json",
    success: function (result) {

      if (result.success) {
        toastr.success(result.message)
        $("#modal-delete").modal("hide")
        getKelas()
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

  const name = $("#modal-kelas-label").val()

  if (name.length < 1) {
    msg += "Label Kelas Tidak Valid, "
  }

  if (msg.length > 0) {
    $("#modal-kelas .modal-body").append(
      `
        <div class="row" id="modal-kelas-alert">
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