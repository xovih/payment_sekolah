
const konten = $(".main-content")

const user_id = localStorage.getItem("user_id")
const username = localStorage.getItem("username")
const fullname = localStorage.getItem("fullname")

if ($("#modal-header-reset").length == 0) {
  konten.append(
    `
      <div class="modal fade" role="dialog" id="modal-header-reset">
        <div class="modal-dialog" role="document" style="margin-top: 15%">
          <div class="modal-content">
            <form action="aaaa" name="modal-header-reset-form" id="modal-header-reset-form" method="post">
              <div class="modal-header">
                <h5 class="modal-title" id="modal-header-reset-cop"><i class="fa fa-feather-alt"></i> Reset Password User <span class="text-danger">${username} - ${fullname}</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label>Password Baru</label>
                      <input type="password" class="form-control" placeholder="password" id="modal-header-reset-password" name="password" autocomplete="off" />
                    </div>
                    <input type="hidden" name="user_id" id="modal-header-reset-user_id" value="${user_id}"/>
                    <input type="hidden" name="fullname" id="modal-header-reset-fullname" value="${fullname}" />
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-danger" id="modal-header-reset-submit"><i class="fa fa-check"></i> Change !</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    `
  )

}

$("#modal-header-reset-submit").on("click", (e) => {
  e.preventDefault()
  resetPassword(user_id, $("#modal-header-reset-password").val())
})

const resetPassword = function (id, pw) {
  $.ajax({
    url: `${siteUrl}/users/action/reset`,
    type: "post",
    data: {
      user_id: id,
      password: pw
    },
    dataType: "json",
    success: function (res) {
      if (res.success) {
        toastr.success("Sukses Mengubah Password Anda !")
        $("#modal-header-reset").modal("hide")
      } else {
        $("#modal-header-reset .modal-body").append(
          `
            <div class="row" id="modal-header-reset-alert">
              <div class="col-12">
                <div class="alert alert-danger">
                  Gagal Mengubah Password !
                </div>
              </div>
            </div>
            `
        )

        setTimeout(() => {
          $("#modal-header-reset-alert").remove()
        }, 4000)
      }
    }
  })
}

$('#modal-header-reset').on('hidden.bs.modal', function () {
  window.history.pushState(null, null, path)
  $("#modal-header-reset-form").get(0).reset()
})

$("#page-reset-password").on("click", function (e) {
  e.preventDefault()
  $("#modal-header-reset").modal("show")
})