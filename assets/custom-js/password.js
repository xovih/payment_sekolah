const btnChangePassword = $("#password-button")
const modalPassword = $("#modal-password")
const btnSubmitPassword = $("#modal-password-submit")

btnChangePassword.on("click", (e) => {
  e.preventDefault()

  $("#modal-password-form").attr("action", "changepassword")
  $("#modal-password-userid").val(localStorage.getItem("userid"))

  modalPassword.modal("show")
})

btnSubmitPassword.on("click", (e) => {
  e.preventDefault()

  const mForm = $("#modal-password-form")
  const action = mForm.attr("action")

  if (validatePassword()) {
    $.ajax({
      url: `${siteUrl}/users/action/${action}`,
      type: "POST",
      data: mForm.serialize(),
      dataType: "json",
      success: function (result) {
        if (!result.error) {
          toastr.success(result.message)
          $("#modal-password").modal("hide")
        } else {
          $("#modal-password .modal-body").append(
            `
            <div class="row" id="modal-password-alert">
              <div class="col-12">
                <div class="alert alert-danger">
                  ${result.message}
                </div>
              </div>
            </div>
            `
          )

          setTimeout(() => {
            $("#modal-password-alert").remove()
          }, 4000)
        }
      },
      error: function (e) {
        console.log(e)
      }
    })
  } else {
    setTimeout(() => {
      $("#modal-password-alert").remove()
    }, 4000)
  }
})

const btnProfile = $("#profile-button")
const btnProfileSubmit = $("#modal-profile-submit")

btnProfile.on("click", (e) => {
  e.preventDefault()

  const id = localStorage.getItem("userid")
  const getUser = getJSON(`${siteUrl}/users/action/getuser`, { id })

  if (!getUser.error) {
    const selecteduser = getUser.data

    $("#modal-profile-form").attr("action", "changeprofile")
    $("#modal-profile-userid").val(id)
    $("#modal-profile-fullname").val(selecteduser.fullname)
    $("#modal-profile-alias").val(selecteduser.alias)
    $("#modal-profile-avatar").val(selecteduser.avatar)
    $("#modal-profile-dateofbirth").daterangepicker({
      locale: { format: 'YYYY-MM-DD' },
      singleDatePicker: true,
    })
    $("#modal-profile-dateofbirth").val(selecteduser.dateofbirth).change()

    $("#modal-profile").modal("show")

  } else {
    toastr.error("Sesi Berakhir, Silahkan logout lalu login ulang !")
  }

})

btnProfileSubmit.on("click", (e) => {
  e.preventDefault()

  const mForm = $("#modal-profile-form")
  const action = mForm.attr("action")

  if (validateProfile()) {
    $.ajax({
      url: `${siteUrl}/users/action/${action}`,
      type: "POST",
      data: mForm.serialize(),
      dataType: "json",
      success: function (result) {
        if (!result.error) {
          toastr.success(result.message)
          $("#modal-profile").modal("hide")

          setTimeout(() => {
            window.location.reload()
          }, 3000)
        } else {
          $("#modal-profile .modal-body").append(
            `
            <div class="row" id="modal-profile-alert">
              <div class="col-12">
                <div class="alert alert-danger">
                  ${result.message}
                </div>
              </div>
            </div>
            `
          )

          setTimeout(() => {
            $("#modal-profile-alert").remove()
          }, 4000)
        }
      },
      error: function (e) {
        console.log(e)
      }
    })
  } else {
    setTimeout(() => {
      $("#modal-profile-alert").remove()
    }, 4000)
  }
})

/** VALIDATION FUNCTION */
function validatePassword() {
  const oldpassword = $("#modal-password-oldpassword").val()
  const newpassword = $("#modal-password-newpassword").val()
  const confpassword = $("#modal-password-confpassword").val()
  let msg = ""

  if (newpassword != confpassword) {
    msg = "Password Baru dan Password Konfirmasi Tidak Cocok, "
  } else {
    msg = ""
  }

  if (oldpassword.length < 2) {
    msg += "Password Lama, "
  }

  if (newpassword.length < 2) {
    msg += "Password Baru, "
  }

  if (confpassword.length < 2) {
    msg += "Konfirmasi Password, "
  }

  if (msg.length > 0) {
    msg += "Tidak Boleh Kosong !"
    $("#modal-password .modal-body").append(
      `
      <div class="row" id="modal-password-alert">
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

function validateProfile() {

  let msg = ""

  const fullname = $("#modal-profile-fullname").val()
  const alias = $("#modal-profile-alias").val()

  if (fullname.length < 2) {
    msg += "Fullname setidaknya harus 2 Char, "
  }

  if (alias.length < 2) {
    msg += "Alias setidaknya harus 2 Char, "
  }

  if (msg.length > 0) {
    $("#modal-profile .modal-body").append(
      `
        <div class="row" id="modal-profile-alert">
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

