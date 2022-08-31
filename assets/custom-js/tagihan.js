let path = window.location.pathname

const tbl = $("#tbl-tagihan")
const tblTitle = [
	{
		title: "#",
		className: "text-center",
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
		title: "Due Date",
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

const txtSearch = $("#search")
txtSearch.on("keyup", function (e) {
	e.preventDefault()
	const sr = $(this).val()
	window.location.hash = `#get?q=${sr}`
})

function getTagihan(search = null, page = 1, scroll = false) {
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
		url: `${siteUrl}/tagihan/action/list`,
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
				const updatelink = siteUrl + "/tagihan/update/" + d.id_tagihan
				let status = `<span class="badge badge-warning">Belum Lunas<span>`
				if (d.jumlah_bayar >= parseInt(d.nominal)) {
					status = `<span class="badge badge-success">Lunas<span>`
				} else if (d.jumlah_bayar == 0) {
					status = `<span class="badge badge-secondary">Belum Dibayar<span>`
				}
				tbl.find("tbody").append(
					`
            <tr>
              <td class="text-center">${d.no}</td>
              <td nowrap>
                ${d.no_induk} &nbsp;
                  <span class="table-links">
                    <a href="${updatelink}" class="text-danger">Detail</a>
                  </span>
              </td>
							<td nowrap>${d.nama}</td>
							<td class="text-center">${d.kelas_tingkat}${d.kelas_label}</td>
							<td>${d.kode_akun} - ${d.nama_akun}</td>
							<td class="text-center">${formatRupiah(d.nominal)}</td>
							<td class="text-center">${d.tenggat_waktu}</td>
							<td class="text-center">${status}</td>
              <td class="text-center" nowrap>
                <a href="${updatelink}" class="btn-sm btn-dark"><i class="fa fa-edit"></i> Detail</a>
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

	if (path.search("tagihan") > 0) {
		if (hash.search("get") == 0) {
			const query = getUrlVars()
			const q = query["q"]
			const p = query["p"]
			const s = p ? true : false

			getTagihan(q, p, s)
		}
		else if (hash.search('import') == 0) {
			$("#modal-import").modal("show")
		}
	}

	$('#modal-import').on('hidden.bs.modal', function () {
		window.history.pushState(null, null, path)
		$("#modal-import-form").get(0).reset()
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
					getTagihan()
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
				if (textStatus == "parsererror")
					window.location.href = `${siteUrl}${window.location.pathname}`
				toastr.error(textStatus)
			},
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
				getTagihan()
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
			if (textStatus == "parsererror")
				window.location.href = `${siteUrl}${window.location.pathname}`
			toastr.error(textStatus)
		},
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
		if (tipe == "add") {
			console.log("eksek")
			const tabel = $("#tabel-input-tagihan")

			if (pilihan == "all") {
				const reqSiswa = getJSON(`${siteUrl}/siswa/action/list`, {
					id_kelas: kelas,
				})
				const siswa = reqSiswa.data.data

				let no = tabel.find("tbody").find("tr").length + 1
				const unik = moment(new Date()).format("YYYYMMDDHHmmss")
				$.each(siswa, (i, v) => {
					if (verifikasiAdaSiswa(v.id_siswa)) {
						tabel.find("tbody").prepend(
							`
							<tr>
								<td class="text-center">${no++}</td>
								<td class="text-center">
									${v.no_induk}
									<input type="hidden" id="id_${unik}" value="${v.id_siswa}">
								</td>
								<td>${v.nama}</td>
								<td class="text-center">${v.kelas_tingkat}${v.kelas_label}</td>
								<td class="text-center"><a href="#" class="btn-sm btn-danger btn-hapus-siswa"><i class="fa fa-trash"></i></a></td>
							</tr>
							`
						)
					}
				})
			} else {
				const reqSiswa = getJSON(`${siteUrl}/siswa/action/get`, {
					id: pilihan,
				})
				const v = reqSiswa.data
				let no = tabel.find("tbody").find("tr").length + 1
				const unik = moment(new Date()).format("YYYYMMDDHHmmss")
				if (verifikasiAdaSiswa(v.id_siswa)) {
					tabel.find("tbody").prepend(
						`
							<tr>
								<td class="text-center">${no++}</td>
								<td class="text-center">
									${v.no_induk}
									<input type="hidden" id="id_${unik}" value="${v.id_siswa}">
								</td>
								<td>${v.nama}</td>
								<td class="text-center">${v.kelas_tingkat}${v.kelas_label}</td>
								<td class="text-center"><a href="#" class="btn-sm btn-danger btn-hapus-siswa"><i class="fa fa-trash"></i></a></td>
							</tr>
							`
					)
				}
			}
		} else if (tipe == "filter") {
			localStorage.setItem("id_siswa", pilihan)
			$("#filter-siswa").val($("#modal-pilihan-id_siswa :selected").text())
			getTagihan(null, 1, false)
		}

		$("#modal-pilihan").modal("hide")
	} else {
		setTimeout(() => {
			$("#pilihan-error").remove()
		}, 3000)
	}
})

$("#tabel-input-tagihan").on("click", ".btn-hapus-siswa", function (e) {
	e.preventDefault()
	$(this).closest("tr").remove()
})

function verifikasiAdaSiswa(id_siswa) {
	let countData = 0
	const tabel = $("#tabel-input-tagihan tbody")
	tabel.find("tr").each(function (i, el) {
		const kolom = $(this).find("td")
		if (kolom.eq(1).find("input").val() == id_siswa) countData++
	})

	if (countData > 0) return false
	return true
}

const getSiswaTertagih = function () {
	let dataSiswa = []
	const tabel = $("#tabel-input-tagihan tbody")
	tabel.find("tr").each(function (i, el) {
		const kolom = $(this).find("td")
		const id_siswa = kolom.eq(1).find("input").val()
		dataSiswa.push(id_siswa)
	})

	return dataSiswa
}

const validasiInputTagihan = function () {
	let msg = ""

	const nominal = formatAngka($("#formulir-nominal").val())
	const tanggal = $("#formulir-tenggat_waktu").val()
	const siswa = getSiswaTertagih()

	if (nominal.length < 3) {
		msg = `${msg} Nominal Tidak Valid, `
	}
	if (tanggal.length != 10) {
		msg = `${msg} tanggal Tidak Valid, `
	}
	if (siswa.length < 1) {
		msg = `${msg} Tidak Ada Siswa Terpilih !`
	}

	if (msg.length > 0) {
		toastr.error(msg)
		return false
	}

	return true
}

$("#formulir-simpan").on("click", (e) => {
	e.preventDefault()

	if (validasiInputTagihan()) {
		const id_akun = $("#formulir-id_akun :selected").val()
		const nominal = formatAngka($("#formulir-nominal").val())
		const tenggat_waktu = $("#formulir-tenggat_waktu").val()
		const catatan = $("#formulir-catatan").val()
		const details = getSiswaTertagih()

		$.ajax({
			url: `${siteUrl}/tagihan/action/add`,
			type: "POST",
			dataType: "json",
			data: { id_akun, nominal, catatan, tenggat_waktu, details },
			success: (res) => {
				if (res.success) {
					toastr.success(res.message)
					newTransaction()
				} else {
					toastr.error(res.message)
				}
			},
		})
	}
})

// UPDATE Tagihan
const getTagihanTerpilih = function (id) {
	const reqTagihan = getJSON(`${siteUrl}/tagihan/action/list`, {
		id_tagihan: id,
	})

	if (reqTagihan.success) {
		const tagihan = reqTagihan.data.data
		console.log(tagihan[0])

		$("#formulir-id_tagihan").val(tagihan[0].id_tagihan)
		$("#formulir-id_akun").val(tagihan[0].id_akun).change()
		$("#formulir-nominal").val(tagihan[0].nominal).change()
		$("#formulir-tenggat_waktu").val(tagihan[0].tenggat_waktu).change()
		$("#formulir-catatan").val(tagihan[0].catatan)

		const tabel = $("#tabel-input-tagihan")
		tabel.find("tbody").find("tr").remove()

		let no = tabel.find("tbody").find("tr").length + 1

		$.each(tagihan, (i, v) => {
			const unik = moment(new Date()).format("YYYYMMDDHHmmss")
			if (verifikasiAdaSiswa(v.id_siswa)) {
				tabel.find("tbody").prepend(
					`
					<tr>
						<td class="text-center">${no++}</td>
						<td class="text-center">
							${v.no_induk}
							<input type="hidden" id="id_${unik}" value="${v.id_siswa}">
						</td>
						<td>${v.nama}</td>
						<td class="text-center">${v.kelas_tingkat}${v.kelas_label}</td>
						<td class="text-center"><a href="#" class="btn-sm btn-danger btn-hapus-siswa"><i class="fa fa-trash"></i></a></td>
					</tr>
					`
				)
			}
		})
	} else {
		toastr.error("Gagal Memuat Data")
		setTimeout(() => {
			window.location.reload()
		}, 2000)
	}
}

$("#formulir-update").on("click", (e) => {
	e.preventDefault()

	if (validasiInputTagihan()) {
		const id_tagihan = $("#formulir-id_tagihan").val()
		const id_akun = $("#formulir-id_akun :selected").val()
		const nominal = formatAngka($("#formulir-nominal").val())
		const tenggat_waktu = $("#formulir-tenggat_waktu").val()
		const catatan = $("#formulir-catatan").val()
		const details = getSiswaTertagih()

		$.ajax({
			url: `${siteUrl}/tagihan/action/update`,
			type: "POST",
			dataType: "json",
			data: { id_tagihan, id_akun, nominal, catatan, tenggat_waktu, details },
			success: (res) => {
				if (res.success) {
					toastr.success(res.message)
					newTransaction()
				} else {
					toastr.error(res.message)
				}
			},
		})
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
	getTagihan()
})

$("#filter-reset").on("click", (e) => {
	e.preventDefault()
	resetFilter()
	getTagihan()
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
	getTagihan()
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
			getTagihan()
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
		url: `${siteUrl}/tagihan/action/importExcel`,
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
				getTagihan()
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