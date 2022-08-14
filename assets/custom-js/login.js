const username = $("#username")
const password = $("#password")
const btnLogin = $("#btn-login")

btnLogin.on("click", function (e) {
  e.preventDefault()


  const uname = username.val()
  const psswd = password.val()

  console.log(`${siteUrl}/auth/action/login`)

  const lgCheck = getJSON(`${siteUrl}/auth/action/login`, {
    username: uname,
    password: psswd
  })

  if (lgCheck.success) {
    toastr.success(lgCheck.message)

    localStorage.setItem("username", lgCheck.data.username)
    localStorage.setItem("fullname", lgCheck.data.fullname)
    localStorage.setItem("user_id", lgCheck.data.user_id)

    setTimeout(() => {
      window.location.href = `${siteUrl}/dashboard`
    }, 1200)
  } else {
    toastr.error(lgCheck.message)
  }
})