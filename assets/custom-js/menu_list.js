let menuType = "parent"

const selMenuType = $(`#sel-menu-type`)
selMenuType.on("change", () => {
  menuType = $(`#sel-menu-type :selected`).val()

  getMenus(menuType)
})

const tbl = $("#tbl-menu")
const tblTitleParent = [
  {
    title: "#",
    className: "text-center"
  },
  {
    title: "Menu Title",
    className: ""
  },
  {
    title: "Link",
    className: ""
  },
  {
    title: "Active Class",
    className: ""
  },
  {
    title: "Icon",
    className: "text-center"
  },
  {
    title: "Category",
    className: ""
  },
  {
    title: "Menu Type",
    className: ""
  },
  {
    title: "Sub Menu (Qty)",
    className: "text-center"
  },
  {
    title: "Action",
    className: "text-center"
  },
]
const tblTitleChild = [
  {
    title: "#",
    className: "text-center"
  },
  {
    title: "Menu Title",
    className: ""
  },
  {
    title: "Link",
    className: ""
  },
  {
    title: "Active Class",
    className: ""
  },
  {
    title: "Parent",
    className: ""
  },
  {
    title: "Order No",
    className: "text-center"
  },
  {
    title: "Action",
    className: "text-center"
  },
]

const btnParent = $("#btnParent")
const btnChild = $("#btnChild")
const txtSearch = $("#search")

txtSearch.on("keyup", function (e) {
  e.preventDefault()
  const sr = $(this).val()
  window.location.hash = `#get?q=${sr}`
})

btnParent.on("click", function (e) {
  window.location.hash = "addparent"

  getMenus(menuType)
})

btnChild.on("click", function (e) {
  window.location.hash = "addchild"

  getMenus(menuType)
})

function buildTable(type = "parent") {
  tbl.find("thead").find("tr").remove()
  tbl.find("tbody").find("tr").remove()

  const dataTitle = type == "parent" ? tblTitleParent : tblTitleChild
  let tblTitle = `<tr>`

  dataTitle.forEach((title) => {
    tblTitle += `<th class="${title.className}" nowrap>${title.title}</th>`
  })
  tblTitle += `</tr>`

  tbl.find("thead").append(`${tblTitle}`)
}

function getMenus(menuType, search = null, page = 1, scroll = false) {
  $.ajax({
    url: `${siteUrl}/menus/action/getmenus`,
    type: "POST",
    dataType: "json",
    data: {
      menuType,
      search,
      page,
    },
    success: function (result) {
      if (!result.success) {
        toastr.error(result.message)
      } else {
        buildTable(menuType)
        if (menuType == "parent") {
          $.each(result.data.data, function (i, data) {
            tbl.find("tbody").append(
              `
                <tr>
                  <td class="text-center">${data.no}</td>
                  <td nowrap>
                    ${data.label} &nbsp;
                    <span class="table-links">
                      <a href="#editparent?id=${data.menu_id}">Update</a>
                      <div class="bullet"></div>
                      <a href="#delete?id=${data.menu_id}" class="text-danger">Hapus</a>
                    </span>
                  </td>
                  <td nowrap>${data.link}</td>
                  <td nowrap>${data.active_class}</td>
                  <td class="text-center"><i class="${data.icon}"></i></td>
                  <td nowrap>${data.category}</td>
                  <td nowrap>${data.type}</td>
                  <td class="text-center">${data.child_count}</td>
                  <td class="text-center" nowrap>
                    <a href="#editparent?id=${data.menu_id}" class="btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                    <a href="#delete?id=${data.menu_id}" class="btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                  </td>
                <tr>
              `
            )
          })
        } else {
          $.each(result.data.data, function (i, data) {
            tbl.find("tbody").append(
              `
                <tr>
                  <td class="text-center">
                    ${data.no}
                  </td>
                  <td nowrap>
                    ${data.label}  &nbsp;
                    <span class="table-links">
                      <a href="#editchild?id=${data.menu_id}">Update</a>
                      <div class="bullet"></div>
                      <a href="#delete?id=${data.menu_id}" class="text-danger">Hapus</a>
                    </span>
                  </td>
                  <td nowrap>${data.link}</td>
                  <td nowrap>${data.active_class}</td>
                  <td>${data.parent_name}</td>
                  <td class="text-center">${data.order_no}</td>
                  <td class="text-center" nowrap>
                    <a href="#editchild?id=${data.menu_id}" class="btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                    <a href="#delete?id=${data.menu_id}" class="btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                  </td>
                <tr>
              `
            )
          })
        }

        // Pagination
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
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      if (textStatus == "parsererror") window.location.href = `${siteUrl}${window.location.pathname}`
      toastr.error(textStatus)
    }
  })
}

function newPage(type = menuType) {
  if (type == "parent") {
    getMenus(menuType)
  } else {
    getMenus(menuType)
  }

  window.history.pushState(null, null, window.location.pathname)
}

function childGetParent() {
  const parentMenu = getJSON(`${siteUrl}/menus/action/getparents`)
  const dataParent = parentMenu.data

  $("#modal-child-parentid").find("option").remove()
  $.each(dataParent, (i, menu) => {
    $("#modal-child-parentid").append(
      `
      <option value="${menu.menu_id}">${menu.label} (${menu.link})</option>
      `
    )
  })
}

$("#modal-child-parentid").on("change", () => {
  const id = $("#modal-child-parentid :selected").val()
  const cat = getMenuCategory(id)
  $("#modal-child-category").val(cat)
})

function getMenuCategory(id) {
  const menu = getJSON(`${siteUrl}/menus/action/getmenu`, { id }).data
  return menu.category
}

$(window).hashchange(function () {
  const hash = $.param.fragment()
  const path = window.location.pathname

  if (path.search('menus') > 0) {
    if (hash.search('delete') == 0) {
      const id = getUrlVars()['id']
      const menu = getJSON(`${siteUrl}/menus/action/getmenu`, { id }).data

      $("#modal-delete-cop").html(`<i class="fa fa-trash"></i> Deletion Confirmation`)

      $("#delete-label").html(`Apakah yakin mau hapus menu &nbsp;<b><span class="text-danger">${menu.label} (${menu.link})</span></b> ?`)
      $("#delete-id").val(id)
      $("#delete-name").val(`${menu.label} (${menu.link})`)
      $("#modal-delete").modal("show")

    } else if (hash.search("addparent") == 0) {
      $("#modal-parent-cop").html(`<i class="fa fa-feather-alt"></i> Adding a Parent Menu`)
      $("#modal-parent-submit").html(`<i class="fa fa-check"></i> Add`)
      $("#modal-parent-form").attr("action", "add")
      $("#modal-parent").modal("show")

    } else if (hash.search("addchild") == 0) {
      childGetParent()
      const id = $("#modal-child-parentid option:first").val()
      $("#modal-child-parentid").val(id).change()
      $("#modal-child-cop").html(`<i class="fa fa-feather-alt"></i> Adding a Sub Menu`)
      $("#modal-child-submit").html(`<i class="fa fa-check"></i> Add`)
      $("#modal-child-form").attr("action", "add")
      $("#modal-child").modal("show")

    } else if (hash.search("editparent") == 0) {
      const id = getUrlVars()['id']
      const menu = getJSON(`${siteUrl}/menus/action/getmenu`, { id }).data

      $("#modal-parent-cop").html(`<i class="fa fa-edit"></i> Update Parent Menu`)
      $("#modal-parent-submit").html(`<i class="fa fa-check"></i> Update`)
      $("#modal-parent-form").attr("action", "update")

      $("#modal-parent-menuid").val(id)
      $("#modal-parent-title").val(menu.label)
      $("#modal-parent-link").val(menu.link)
      $("#modal-parent-orderno").val(menu.order_no)
      $("#modal-parent-menutype").val(menu.type).change()
      $("#modal-parent-category").val(menu.category).change()
      $("#modal-parent-activeclass").val(menu.active_class)
      $("#modal-parent-icon").val(menu.icon)

      $("#modal-parent").modal("show")

    } else if (hash.search("editchild") == 0) {
      const id = getUrlVars()['id']
      const menu = getJSON(`${siteUrl}/menus/action/getmenu`, { id }).data

      $("#modal-child-cop").html(`<i class="fa fa-edit"></i> Update Sub Menu`)
      $("#modal-child-submit").html(`<i class="fa fa-check"></i> Update`)
      $("#modal-child-form").attr("action", "update")

      childGetParent()

      $("#modal-child-menuid").val(id)
      $("#modal-child-title").val(menu.label)
      $("#modal-child-link").val(menu.link)
      $("#modal-child-orderno").val(menu.order_no)
      $("#modal-child-activeclass").val(menu.active_class)
      $("#modal-child-icon").val(menu.icon)
      $("#modal-child-parentid").val(menu.parent_id).change()

      $("#modal-child").modal("show")

    } else if (hash.search("get") == 0) {
      const query = getUrlVars()
      const q = query["q"]
      const p = query["p"]

      getMenus(menuType, q, p, false)
    }
  }

  $('#modal-delete').on('hidden.bs.modal', function () {
    window.history.pushState(null, null, path)
  })

  $('#modal-parent').on('hidden.bs.modal', function () {
    window.history.pushState(null, null, path)
    $("#modal-parent-form").get(0).reset()
  })

  $('#modal-child').on('hidden.bs.modal', function () {
    window.history.pushState(null, null, path)
    $("#modal-child-form").get(0).reset()
  })
})

/** MODAL ACTION */
$("#modal-parent-submit").on("click", function (e) {
  e.preventDefault()

  if (!parentValidate()) {
    setTimeout(() => {
      $("#modal-parent-alert").remove()
    }, 4000)
  } else {
    const action = $("#modal-parent-form").attr("action")
    let data = $("#modal-parent-form").serialize()
    data += "&parentid="
    $.ajax({
      url: `${siteUrl}/menus/action/${action}`,
      data,
      dataType: "json",
      type: "POST",
      success: function (result) {
        if (result.success) {
          toastr.success(result.message)
          $("#modal-parent").modal("hide")
          getMenus(menuType)
        } else {
          const msg = result.message
          $("#modal-parent .modal-body").append(
            `
            <div class="row" id="modal-parent-alert">
              <div class="col-12">
                <div class="alert alert-danger">
                  ${msg}
                </div>
              </div>
            </div>
            `
          )

          setTimeout(() => {
            $("#modal-parent-alert").remove()
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

$("#modal-child-submit").on("click", function (e) {
  e.preventDefault()

  if (!childValidate()) {
    setTimeout(() => {
      $("#modal-child-alert").remove()
    }, 4000)
  } else {
    const action = $("#modal-child-form").attr("action")
    let data = $("#modal-child-form").serialize()
    data += "&menutype=child"
    $.ajax({
      url: `${siteUrl}/menus/action/${action}`,
      data,
      dataType: "json",
      type: "POST",
      success: function (result) {
        if (result.success) {
          toastr.success(result.message)
          $("#modal-child").modal("hide")
          getMenus(menuType)
        } else {
          const msg = result.message
          $("#modal-child .modal-body").append(
            `
            <div class="row" id="modal-child-alert">
              <div class="col-12">
                <div class="alert alert-danger">
                  ${msg}
                </div>
              </div>
            </div>
            `
          )
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        if (textStatus == "parsererror") window.location.href = `${siteUrl}${window.location.pathname}`
        toastr.error(textStatus)
      }
    })
  }
})

$("#modal-delete-submit").on("click", function (e) {
  e.preventDefault()
  $.ajax({
    url: `${siteUrl}/menus/action/delete`,
    data: $("#modal-delete-form").serialize(),
    dataType: "json",
    type: "POST",
    success: function (result) {
      if (result.success) {
        toastr.success(result.message)
        $("#modal-delete").modal("hide")
        getMenus(menuType)
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
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      if (textStatus == "parsererror") window.location.href = `${siteUrl}${window.location.pathname}`
      toastr.error(textStatus)
    }
  })
})

/** VALIDATION */
function parentValidate() {
  let msg = ""

  if ($("#modal-parent-title").val().length < 2) {
    msg += "Title, "
  }

  if ($("#modal-parent-link").val().length < 2) {
    msg += "Link, "
  }

  if (parseInt($("#modal-parent-orderno").val()) < 1) {
    msg += "Orderno, "
  }

  if ($("#modal-parent-activeclass").val().length < 2) {
    msg += "Active Class, "
  }

  if ($("#modal-parent-icon").val().length < 2) {
    msg += "Icon, "
  }

  if (msg.length > 0) {
    $("#modal-parent .modal-body").append(
      `
      <div class="row" id="modal-parent-alert">
        <div class="col-12">
          <div class="alert alert-danger">
            ${msg} Tidak Boleh Kosong !
          </div>
        </div>
      </div>
      `
    )
    return false
  }

  return true
}

function childValidate() {
  let msg = ""

  if ($("#modal-child-title").val().length < 2) {
    msg += "Title, "
  }

  if ($("#modal-child-link").val().length < 2) {
    msg += "Link, "
  }

  if (parseInt($("#modal-child-orderno").val()) < 1) {
    msg += "Orderno, "
  }

  if ($("#modal-child-activeclass").val().length < 2) {
    msg += "Active Class, "
  }

  if (msg.length > 0) {
    $("#modal-child .modal-body").append(
      `
      <div class="row" id="modal-child-alert">
        <div class="col-12">
          <div class="alert alert-danger">
            ${msg} Tidak Boleh Kosong !
          </div>
        </div>
      </div>
      `
    )
    return false
  }

  return true
}