let path = window.location.pathname

const tbl = $("#tbl-user")
const tblTitle = [
  {
    title: "#",
    className: "text-center"
  },
  {
    title: "Fullname",
    className: ""
  },
  {
    title: "username",
    className: ""
  },
  {
    title: "Actor Type",
    className: ""
  },
  {
    title: "Reset Password",
    className: "text-center"
  },
  {
    title: "Aktivasi",
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

function getUsers(search = null, page = 1, scroll = false) {
  $.ajax({
    url: `${siteUrl}/users/action/getusers`,
    data: {
      search,
      page
    },
    dataType: "json",
    type: "POST",
    success: function (result) {
      buildTable()
      const user = result.data.data
      $.each(user, (i, user) => {
        const isActive = user.is_active == "1" ? `checked` : ``
        tbl.find("tbody").append(
          `
            <tr>
              <td class="text-center">${user.no}</td>
              <td nowrap>
                ${user.fullname}  &nbsp;
                  <span class="table-links float-right">
                    <a href="#update?id=${user.user_id}" class="text-danger">Ubah</a>
                  </span>
              </td>
              <td nowrap>${user.username}</td>
              <td nowrap>${user.role}</td>
              <td class="text-center" nowrap><a href="#reset?id=${user.user_id}" class="btn-sm btn-dark"><i class="fas fa-key"></i> reset</a></td>
              <td class="text-center">
                <label class="custom-switch pl-0">
                  <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input" id="sw-${user.no}" ${isActive}>
                  <span class="custom-switch-indicator"></span>
                </label>
              </td>
            <tr>
          `
        )

        $(`#sw-${user.no}`).on('change', () => {
          if (isActive) {
            window.location.hash = `#delete?id=${user.user_id}&sid=${page}`
          } else {
            window.location.hash = `#activate?id=${user.user_id}&pid=${page}`
          }
        })
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

function buildActors() {
  const komp = $("#modal-user-actor_id")
  komp.find("option").remove()

  const fetchActor = getJSON(`${siteUrl}/users/action/getactors`)

  if (fetchActor.success) {
    $.each(fetchActor.data, (i, actor) => {
      komp.append(
        `
        <option value="${actor.actor_id}">${actor.role}</option>
        `
      )
    })
  }
}


$(window).hashchange(function () {
  const hash = $.param.fragment()

  if (path.search('users') > 0) {
    if (hash.search("get") == 0) {
      const query = getUrlVars()
      const q = query["q"]
      const p = query["p"]
      const s = p ? true : false

      getUsers(q, p, s)
    } else if (hash.search('add') == 0) {
      $("#modal-user-cop").html(`<i class="fa fa-feather-alt"></i> Add a User`)
      $("#modal-user-form").attr("action", "add")
      buildActors()
      $("#modal-user-username").attr("disabled", false)
      $("#modal-user-password").attr("disabled", false)
      $("#modal-user-submit").html(`<i class="fa fa-check"></i> Add !`)
      $("#modal-user").modal("show")

    } else if (hash.search('update') == 0) {
      const query = getUrlVars()
      const id = query["id"]

      const cUser = getJSON(`${siteUrl}/users/action/getuser`, { id })

      if (cUser.success) {
        const user = cUser.data
        buildActors()

        $("#modal-user-cop").html(`<i class="fa fa-edit"></i> Update User <span class="text-danger">${user.fullname}</span>`)
        $("#modal-user-form").attr("action", "update")
        $("#modal-user-user_id").val(user.user_id)
        $("#modal-user-fullname").val(user.fullname)
        $("#modal-user-username").val(user.username)
        $("#modal-user-username").attr("disabled", true)
        $("#modal-user-password").attr("placeholder", "Tidak bisa diubah disini !")
        $("#modal-user-password").attr("disabled", true)
        $("#modal-user-photo").val(user.photo)
        $("#modal-user-actor_id").val(user.actor_id).change()

        $("#modal-user-submit").html(`<i class="fa fa-check"></i> Update !`)
        $("#modal-user").modal("show")
      } else {
        toastr.error("User not Found !")
        window.history.pushState(null, null, path)
      }


    } else if (hash.search('reset') == 0) {
      const query = getUrlVars()
      const id = query["id"]

      const cUser = getJSON(`${siteUrl}/users/action/getuser`, { id })

      if (cUser.success) {

        const user = cUser.data

        $("#modal-reset-cop").html(`<i class="fa fa-user-secret"></i> Reset Password - <span class="text-danger">${user.username} ${user.fullname}</span>`)
        $("#modal-reset-form").attr("action", "reset")
        $("#modal-reset-user_id").val(user.user_id)
        $("#modal-reset-fullname").val(user.username + " - " + user.fullname)

        $("#modal-reset-submit").html(`<i class="fa fa-check"></i> Reset !`)
        $("#modal-reset-submit").removeClass(`btn-primary`)
        $("#modal-reset-submit").addClass(`btn-dark`)

        $("#modal-reset").modal("show")
      } else {
        toastr.error("User not Found !")
        window.history.pushState(null, null, path)
      }

    } else if (hash.search('delete') == 0) {
      const query = getUrlVars()
      const id = query["id"]
      const sid = query["sid"]

      const cUser = getJSON(`${siteUrl}/users/action/getuser`, { id })

      if (cUser.success) {

        const user = cUser.data

        $("#modal-delete-cop").html(`<i class="fa fa-trash"></i> Delete Confirmation`)
        $("#modal-delete-form").attr("action", "delete")
        $("#modal-delete-text").html(`Apakah anda yakin akan me-non-aktifkan user <span class="text-danger"><b>${user.username} - ${user.fullname}</b></span> ?`)
        $("#modal-delete-user_id").val(user.user_id)
        $("#modal-delete-sid").val(sid)
        $("#modal-delete-fullname").val(user.username + " - " + user.fullname)

        $("#modal-delete-submit").html(`<i class="fa fa-check"></i> Hapus !`)
        $("#modal-delete-submit").removeClass(`btn-primary`)
        $("#modal-delete-submit").addClass(`btn-danger`)

        $("#modal-delete").modal("show")
      } else {
        toastr.error("User not Found !")
        window.history.pushState(null, null, path)
      }
    } else if (hash.search('activate') == 0) {
      const query = getUrlVars()
      const id = query["id"]
      const pid = query["pid"]

      const act = getJSON(`${siteUrl}/users/action/activate`, { user_id: id })
      if (act.success) {
        window.history.pushState(null, null, path)
        toastr.success(act.message)
        getUsers("", pid, false)
      }
    }
  }

  $('#modal-user').on('hidden.bs.modal', function () {
    window.history.pushState(null, null, path)
    $("#modal-user-form").get(0).reset()
  })

  $('#modal-delete').on('hidden.bs.modal', function () {
    const query = getUrlVars()
    const sid = query["sid"]
    if (sid) {
      getUsers("", sid, false)
    } else {
      getUsers()
    }
    window.history.pushState(null, null, path)
    $("#modal-delete-form").get(0).reset()
  })

  $('#modal-reset').on('hidden.bs.modal', function () {
    window.history.pushState(null, null, path)
    $("#modal-reset-form").get(0).reset()
  })

})

/** MODAL ACTION */
const btnUserSubmit = $("#modal-user-submit")
const btnHapusSubmit = $("#modal-delete-submit")
const btnResetSubmit = $("#modal-reset-submit")

btnUserSubmit.on("click", (e) => {
  e.preventDefault()
  const mForm = $("#modal-user-form")
  const action = mForm.attr("action")
  if (validateUser()) {
    $.ajax({
      url: `${siteUrl}/users/action/${action}`,
      type: "POST",
      data: mForm.serialize(),
      dataType: "json",
      success: function (result) {

        if (!result.error) {
          toastr.success(result.message)
          $("#modal-user").modal("hide")
          getUsers()
        } else {
          const msg = result.detail ? result.detail.message : result.message
          $("#modal-user .modal-body").append(
            `
            <div class="row" id="modal-user-alert">
              <div class="col-12">
                <div class="alert alert-danger">
                  ${msg}
                </div>
              </div>
            </div>
            `
          )

          setTimeout(() => {
            $("#modal-user-alert").remove()
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
      $("#modal-user-alert").remove()
    }, 4000)
  }
})

btnHapusSubmit.on("click", (e) => {
  e.preventDefault()
  const mForm = $("#modal-delete-form")
  $.ajax({
    url: `${siteUrl}/users/action/delete`,
    type: "POST",
    data: mForm.serialize(),
    dataType: "json",
    success: function (result) {

      if (!result.error) {
        toastr.success(result.message)
        $("#modal-delete").modal("hide")
      } else {
        const msg = result.detail ? result.detail.message : result.message
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

btnResetSubmit.on('click', (e) => {
  const password = $(`#modal-reset-password`).val()
  const user_id = $(`#modal-reset-user_id`).val()
  if (password.length > 3) {
    e.preventDefault()
    const req = getJSON(
      `${siteUrl}/users/action/reset`,
      {
        password,
        user_id
      }
    )

    if (req.success) {
      toastr.success(req.message)
      $("#modal-reset").modal("hide")
      getUsers()
    } else {
      const msg = req.detail ? req.detail.message : req.message
      $("#modal-reset .modal-body").append(
        `
            <div class="row" id="modal-user-alert">
              <div class="col-12">
                <div class="alert alert-danger">
                  ${msg}
                </div>
              </div>
            </div>
          `
      )
      setTimeout(() => {
        $("#modal-reset-alert").remove()
      }, 4000)
    }
  } else {
    $("#modal-reset .modal-body").append(
      `
        <div class="row" id="modal-user-alert">
          <div class="col-12">
            <div class="alert alert-danger">
              Password Kurang Aman !
            </div>
          </div>
        </div>
      `
    )
    setTimeout(() => {
      $("#modal-reset-alert").remove()
    }, 4000)
  }
})


/** VALIDATION */
function validateUser() {
  let msg = ""

  const fullname = $("#modal-user-fullname").val()
  const username = $("#modal-user-username").val()
  const password = $("#modal-user-password").val()
  const formAction = $("#modal-user-form").attr("action")

  if (username.length < 2) {
    msg += "Username setidaknya harus 2 Char, "
  }

  if (fullname.length < 2) {
    msg += "Fullname setidaknya harus 2 Char, "
  }

  if (formAction == 'add' && password.length < 3) {
    msg += "Password setidaknya harus 3 Char, "
  }

  if (msg.length > 0) {
    $("#modal-user .modal-body").append(
      `
        <div class="row" id="modal-user-alert">
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