let path = window.location.pathname

const tbl = $("#tbl-actor")
const tblTitle = [
  {
    title: "#",
    className: "text-center"
  },
  {
    title: "Actor Name",
    className: ""
  },
  {
    title: "Users Count",
    className: "text-center"
  },
  {
    title: "Menus Count",
    className: "text-center"
  },
  {
    title: "Action",
    className: "text-center"
  },
]

const txtSearch = $("#search")

txtSearch.on("keyup", function (e) {
  e.preventDefault()
  const sr = $(this).val()
  window.location.hash = `#get?q=${sr}`
})

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

function getActors(search = null, page = 1, scroll = false) {

  $.ajax({
    url: `${siteUrl}/actors/action/getactors`,
    data: {
      search,
      page
    },
    dataType: "json",
    type: "POST",
    success: function (result) {
      buildTable()
      const actors = result.data.data

      $.each(actors, (i, actor) => {
        tbl.find("tbody").append(
          `
            <tr>
              <td class="text-center">${actor.no}</td>
              <td nowrap>
                ${actor.role} &nbsp;
                  <span class="table-links">
                    <a href="#copy?id=${actor.actor_id}" class="text-dark">Copy</a>
                    <div class="bullet"></div>
                    <a href="#config?id=${actor.actor_id}" class="text-success">Config</a>
                    <div class="bullet"></div>
                    <a href="#update?id=${actor.actor_id}" class="text-warning">Update</a>
                    <div class="bullet"></div>
                    <a href="#delete?id=${actor.actor_id}" class="text-danger">Delete</a>
                  </span>
              </td>
              <td class="text-center" nowrap><a href="#users?id=${actor.actor_id}">${actor.user_count} Pengguna</a></td>
              <td class="text-center" nowrap><a href="#config?id=${actor.actor_id}">${actor.menu_count} Menu</a></td>
              <td class="text-center" nowrap>
                <a href="#copy?id=${actor.actor_id}" class="btn-sm btn-dark"><i class="fa fa-copy"></i></a>
                <a href="#config?id=${actor.actor_id}" class="btn-sm btn-success"><i class="fa fa-cogs"></i></a>
                <a href="#update?id=${actor.actor_id}" class="btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                <a href="#delete?id=${actor.actor_id}" class="btn-sm btn-danger"><i class="fa fa-trash"></i></a>
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

function buildMenu() {
  const comp = $("#modal-config-menu")
  comp.find("option").remove()

  const menus = getJSON(`${siteUrl}/actors/action/allmenus`)

  $.each(menus.data, function (i, val) {
    const status = val.type == 'child' ? "(Sub Menu)" : ""
    comp.append(
      `
        <option value="${val.menu_id}">${val.label} ${status} (${val.link})</option>
      `
    )
  })
}

function getActorMenus(id) {
  const tbl = $("#modal-config-table")
  tbl.find("tbody").find("tr").remove()

  const menus = getJSON(`${siteUrl}/actors/action/actormenus`, { id })
  if (!menus.error) {
    const dataMenu = menus.data
    let no = 1
    $.each(dataMenu, function (i, val) {
      const status = val.type == 'child' ? "(Sub Menu)" : ""
      tbl.find("tbody").append(
        `
          <tr>
            <td class="text-center">${no++}</td>
            <td>
              ${val.label} ${status}
              <input type="hidden" value="${val.detail_id}">
            </td>
            <td class="text-center">
              <i class="${val.icon}"></i>
              <input type="hidden" value="${val.actor_id}">
            </td>
            <td class="text-center"><a href="#" id="hapusin" class="btn-sm btn-danger"><i class="fa fa-trash"></i></a></td>
          </tr>
        `
      )
    })
  }
}

function getActorUsers(id) {
  const tbl = $("#modal-user-table")
  tbl.find("tbody").find("tr").remove()

  const menus = getJSON(`${siteUrl}/actors/action/actorusers`, { id })
  if (!menus.error) {
    const dataMenu = menus.data
    let no = 1
    if (dataMenu.length) {
      $.each(dataMenu, function (i, val) {
        tbl.find("tbody").append(
          `
          <tr>
            <td class="text-center">
              ${no++}
              <input type="hidden" value="${val.user_id}">
            </td>
            <td class="text-center">
              ${val.npp}
              <input type="hidden" value="${id}">
            </td>
            <td>${val.fullname}</td>
            <td>${val.dept}</td>
          </tr>
        `
        )
      })
      $("#modal-user").modal("show")
    } else {

      toastr.warning("Data User Kosong !")
      window.history.pushState(null, null, path)
    }

  }
}

$(window).hashchange(function () {
  const hash = $.param.fragment()

  if (path.search('actors') > 0) {
    if (hash.search('add') == 0) {
      $("#modal-actor-cop").html(`<i class="fa fa-feather-alt"></i> Add an Actor`)
      $("#modal-actor-namelabel").html("Actor Name")
      $("#modal-actor-form").attr("action", "add")
      $("#modal-actor-submit").html(`<i class="fa fa-check"></i> Add !`)
      $("#modal-actor").modal("show")

    } else if (hash.search('copy') == 0) {
      const query = getUrlVars()
      const id = query["id"]

      const currActor = getJSON(`${siteUrl}/actors/action/getactor`, { id })

      if (currActor.error) {
        toastr.error("Actor not Found !")
        window.history.pushState(null, null, path)
      } else {
        const dataActor = currActor.data

        $("#modal-actor-cop").html(`<i class="fa fa-copy"></i> Copy Actor from <span class="text-danger">${dataActor.role}</span>`)
        $("#modal-actor-form").attr("action", "copy")
        $("#modal-actor-namelabel").html("New Name")
        $("#modal-actor-actorid").val(dataActor.actor_id)
        $("#modal-actor-name").val(``)
        $("#modal-actor-oldname").val(dataActor.role)
        $("#modal-actor-submit").html(`<i class="fa fa-check"></i> Copy !`)

        $("#modal-actor").modal("show")
      }

    } else if (hash.search('config') == 0) {
      const query = getUrlVars()
      const id = query["id"]

      const currActor = getJSON(`${siteUrl}/actors/action/getactor`, { id })

      if (currActor.error) {
        toastr.error("Actor not Found !")
        window.history.pushState(null, null, path)
      } else {
        const dataActor = currActor.data

        buildMenu()
        getActorMenus(dataActor.actor_id)

        $("#modal-config-cop").html(`<i class="fa fa-copy"></i> Config Actor <span class="text-danger">${dataActor.role}</span>`)
        $("#modal-config-actorid").val(dataActor.actor_id)

        $("#modal-config").modal("show")
      }

    } else if (hash.search('update') == 0) {
      const query = getUrlVars()
      const id = query["id"]

      const currActor = getJSON(`${siteUrl}/actors/action/getactor`, { id })

      if (currActor.error) {
        toastr.error("Actor not Found !")
        window.history.pushState(null, null, path)
      } else {
        const dataActor = currActor.data

        $("#modal-actor-cop").html(`<i class="fa fa-edit"></i> Update Actor <span class="text-danger">${dataActor.role}</span>`)
        $("#modal-actor-form").attr("action", "update")
        $("#modal-actor-namelabel").html("New Name")
        $("#modal-actor-actorid").val(dataActor.actor_id)
        $("#modal-actor-name").val(dataActor.role)
        $("#modal-actor-oldname").val(dataActor.role)
        $("#modal-actor-submit").html(`<i class="fa fa-check"></i> Update !`)

        $("#modal-actor").modal("show")
      }

    } else if (hash.search('delete') == 0) {
      const query = getUrlVars()
      const id = query["id"]

      const currActor = getJSON(`${siteUrl}/actors/action/getactor`, { id })

      if (currActor.error) {
        toastr.error("Actor not Found !")
        window.history.pushState(null, null, path)
      } else {
        const dataActor = currActor.data

        $("#modal-delete-cop").html(`<i class="fa fa-trash"></i> Delete Confirmation`)
        $("#delete-label").html(`Apakah yakin akan menghapus Aktor <span class="text-danger"><b>${dataActor.role}</b></span> ?`)
        $("#modal-delete-form").attr("action", "delete")
        $("#delete-id").val(dataActor.actor_id)
        $("#delete-name").val(dataActor.role)

        $("#modal-delete").modal("show")
      }

    } else if (hash.search("get") == 0) {
      const query = getUrlVars()
      const q = query["q"]
      const p = query["p"]
      const s = p ? true : false

      getActors(q, p, s)
    } else if (hash.search("users") == 0) {
      const query = getUrlVars()
      const id = query["id"]

      const currActor = getJSON(`${siteUrl}/actors/action/getactor`, { id })

      if (currActor.error) {
        toastr.error("Actor not Found !")
        window.history.pushState(null, null, path)
      } else {
        const dataActor = currActor.data

        $("#modal-user-cop").html(`Actor : ${dataActor.role}`)
        $("#modal-user-actorid").val(dataActor.actor_id)
        $("#modal-user-name").val(dataActor.role)

        getActorUsers(dataActor.actor_id)

      }
    }
  }

  $('#modal-delete').on('hidden.bs.modal', function () {
    window.history.pushState(null, null, path)
  })

  $('#modal-user').on('hidden.bs.modal', function () {
    window.history.pushState(null, null, path)
  })

  $('#modal-copy').on('hidden.bs.modal', function () {
    window.history.pushState(null, null, path)
    $("#modal-copy-form").get(0).reset()
  })

  $('#modal-config').on('hidden.bs.modal', function () {
    window.history.pushState(null, null, path)
    window.location.reload()
  })

  $('#modal-actor').on('hidden.bs.modal', function () {
    window.history.pushState(null, null, path)
    $("#modal-actor-form").get(0).reset()
  })
})

/** MODAL ACTION */
const btnActorAdd = $("#modal-actor-submit")
const btnDelete = $("#modal-delete-submit")
const btnConfigAdd = $("#modal-config-add")

btnActorAdd.on("click", (e) => {
  e.preventDefault()

  const name = $("#modal-actor-name").val()
  const mForm = $("#modal-actor-form")

  const action = mForm.attr("action")

  if (name.length >= 2) {
    $.ajax({
      url: `${siteUrl}/actors/action/${action}`,
      type: "POST",
      data: mForm.serialize(),
      dataType: "json",
      success: function (result) {
        if (!result.error) {
          toastr.success(result.message)
          $("#modal-actor").modal("hide")
          getActors()
        } else {
          $("#modal-actor .modal-body").append(
            `
            <div class="row" id="modal-actor-alert">
              <div class="col-12">
                <div class="alert alert-danger">
                  ${result.message}
                </div>
              </div>
            </div>
            `
          )

          setTimeout(() => {
            $("#modal-actor-alert").remove()
          }, 4000)
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        if (textStatus == "parsererror") window.location.href = `${siteUrl}${window.location.pathname}`
        toastr.error(textStatus)
      }
    })
  } else {
    $("#modal-actor .modal-body").append(
      `
        <div class="row" id="modal-actor-alert">
          <div class="col-12">
            <div class="alert alert-danger">
              Name setidaknya harus 2 Char !
            </div>
          </div>
        </div>
        `
    )
    setTimeout(() => {
      $("#modal-actor-alert").remove()
    }, 4000)
  }
})

btnDelete.on("click", (e) => {
  e.preventDefault

  const id = $("#delete-id").val()
  const name = $("#delete-name").val()
  $.ajax({
    url: `${siteUrl}/actors/action/delete`,
    type: "POST",
    data: { id, name },
    dataType: "json",
    success: function (result) {
      if (!result.error) {
        toastr.success(result.message)
        $("#modal-delete").modal("hide")
        getActors()
      } else {
        $("#modal-delete .modal-body").append(
          `
            <div class="row" id="modal-delete-alert">
              <div class="col-12">
                <div class="alert alert-danger">
                  ${result.message}
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

btnConfigAdd.on("click", (e) => {
  e.preventDefault()

  const menuid = $("#modal-config-menu :selected").val()
  const name = $("#modal-config-menu :selected").text()
  const actorid = $("#modal-config-actorid").val()

  $.ajax({
    url: `${siteUrl}/actors/action/addmenutoactor`,
    type: "POST",
    data: { menuid, name, actorid },
    dataType: "json",
    success: function (result) {
      if (!result.error) {
        getActorMenus(actorid)

        $("#modal-config .modal-body").prepend(
          `
            <div class="row" id="modal-config-alert">
              <div class="col-12">
                <div class="alert alert-success">
                  ${result.message}
                </div>
              </div>
            </div>
            `
        )

        setTimeout(() => {
          $("#modal-config-alert").remove()
        }, 4000)
      } else {
        $("#modal-config .modal-body").prepend(
          `
            <div class="row" id="modal-config-alert">
              <div class="col-12">
                <div class="alert alert-danger">
                  ${result.message}
                </div>
              </div>
            </div>
            `
        )

        setTimeout(() => {
          $("#modal-config-alert").remove()
        }, 4000)
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      if (textStatus == "parsererror") window.location.href = `${siteUrl}${window.location.pathname}`
      toastr.error(textStatus)
    }
  })

  $("#modal-config-menu").focus()
})

$("#modal-config-table tbody").on("click", "#hapusin", function (e) {
  e.preventDefault()
  const baris = $(this).closest("tr")
  const kolom = baris.find("td")

  const detailid = kolom.eq(1).find("input:hidden").val()
  const actorid = kolom.eq(2).find("input:hidden").val()
  const name = kolom.eq(1).text().trim()

  $.ajax({
    url: `${siteUrl}/actors/action/delmenufromactor`,
    type: "POST",
    data: { detailid, name },
    dataType: "json",
    success: function (result) {
      if (!result.error) {
        getActorMenus(actorid)

        $("#modal-config .modal-body").prepend(
          `
            <div class="row" id="modal-config-alert">
              <div class="col-12">
                <div class="alert alert-success">
                  ${result.message}
                </div>
              </div>
            </div>
            `
        )

        setTimeout(() => {
          $("#modal-config-alert").remove()
        }, 4000)
      } else {
        $("#modal-config .modal-body").prepend(
          `
            <div class="row" id="modal-config-alert">
              <div class="col-12">
                <div class="alert alert-danger">
                  ${result.message}
                </div>
              </div>
            </div>
            `
        )

        setTimeout(() => {
          $("#modal-config-alert").remove()
        }, 4000)
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      if (textStatus == "parsererror") window.location.href = `${siteUrl}${window.location.pathname}`
      toastr.error(textStatus)
    }
  })
})

$("#modal-user-table tbody").on("click", "#hapusin", function (e) {
  e.preventDefault()
  const baris = $(this).closest("tr")
  const kolom = baris.find("td")

  const id = kolom.eq(0).find("input:hidden").val()
  const userid = kolom.eq(1).find("input:hidden").val()


  $.ajax({
    url: `${siteUrl}/actors/action/deluser`,
    type: "POST",
    data: { id },
    dataType: "json",
    success: function (result) {
      if (!result.error) {
        getActorUsers(userid)

        $("#modal-user .modal-body").prepend(
          `
            <div class="row" id="modal-user-alert">
              <div class="col-12">
                <div class="alert alert-success">
                  ${result.message}
                </div>
              </div>
            </div>
            `
        )

        setTimeout(() => {
          $("#modal-user-alert").remove()
        }, 4000)
      } else {
        $("#modal-user .modal-body").prepend(
          `
            <div class="row" id="modal-user-alert">
              <div class="col-12">
                <div class="alert alert-danger">
                  ${result.message}
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
})