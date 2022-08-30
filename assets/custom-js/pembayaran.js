let aliassiswa = ""

function opsiSiswa() {
	const kom = $("#pilihan_siswa")
	const siswa = $("#id_siswa")
	const formclass = ".eventInsForm"
	const tujuan = $("#id_tagihan")

	kom.autocomplete({
		source: function (request, response) {
			// Fetch data
			$.ajax({
				url: `${siteUrl}/siswa/action/list`,
				type: "post",
				dataType: "json",
				data: {
					search: request.term,
					page: 1,
				},
				success: function (res) {
					const data = res.data.data ? res.data.data : []

					if (data.length > 0 && data.length < 2) {
						const item = data[0]

						if (item.id_siswa) {
							siswa.val(item.id_siswa)
							kom.val(`${item.no_induk} - ${item.nama}`)
							kom.autocomplete("close")
							aliassiswa = `${item.no_induk} - ${item.nama}`
							buildTagihan(item.id_siswa)
							setTimeout(() => {
								tujuan.select2("focus")
							}, 250)
						} else {
							siswa.val("")
						}
					} else {
						response(
							$.map(data, function (item) {
								let object = new Object()
								object.label = `${item.no_induk} - ${item.nama}`
								object.value = `${item.no_induk} - ${item.nama}`
								object.id_siswa = item.id_siswa
								return object
							})
						)
					}
				},
			})
		},
		delay: 25,
		autoFocus: true,
		select: function (event, ui) {
			kom.val(ui.item.value)
			siswa.val(ui.item.id_siswa)
			aliassiswa = ui.item.id_siswa
			buildTagihan(ui.item.id_siswa)
			setTimeout(() => {
				tujuan.select2("focus")
			}, 250)
		},
		focus: function (event, ui) {
			event.preventDefault()
		},
	})

	kom.autocomplete("option", "appendTo", formclass)
}

$("#pilihan_siswa").on("keyup", (e) => {
	e.preventDefault()

	const thisvalue = $("#pilihan_siswa").val()

	if (thisvalue.trim() != aliassiswa.trim()) $("#id_siswa").val("").change()
})

$("#id_siswa").on("change", () => {
	const id_siswa = $("#id_siswa").val() != '' ?? "unknown"
	buildTagihan(id_siswa)
})

function buildTagihan(id_siswa) {
	const opsi = $("#id_tagihan")
	opsi.find("option").remove()

	const reqTagihan = getJSON(`${siteUrl}/tagihan/action/list`, { id_siswa })


	if (reqTagihan.success) {
		if (reqTagihan.data.data.length > 0) {
			const tagihan = reqTagihan.data.data

			opsi.append(`<option value="-">Pilih Akun Tagihan</option>`)

			$.each(tagihan, (i, v) => {
				opsi.append(
					`<option value="${v.id_detail}">${v.kode_akun} - ${v.nama_akun}</option>`
				)
			})
		}
	}
}

$("#id_tagihan").on("change", function (e) {
	e.preventDefault()

	const id_detail = $("#id_tagihan :selected").val()

	const reqTagihan = getJSON(`${siteUrl}/tagihan/action/get`, {
		id: id_detail,
	})

	const tagihan = reqTagihan.data.nominal
	const terbayar = reqTagihan.data.terbayar

	$("#nominal_tagihan").val(formatRupiah(tagihan))
	$("#nominal_terbayar").val(formatRupiah(terbayar.toString()))
})

$("#nominal_pembayaran").on("keyup", function () {
	const komponen = $("#nominal_pembayaran")
	const bilangan = formatAngka(komponen.val())
	komponen.val(formatRupiah(bilangan))
})

function newTransaction() {
	$("#pilihan_siswa").val("").change()
	$("#nominal_pembayaran").val("")
	$("#nominal_tagihan").val("")
	$("#nominal_terbayar").val("")
	$("#catatan").val("")
}

$("#formulir-bayar").on("click", (e) => {
	e.preventDefault()
	if (validasiBayar()) {
		const id_tagihan = $("#id_tagihan :selected").val()
		const catatan = $("#catatan").val()
		const nominal_pembayaran = parseInt(formatAngka($("#nominal_pembayaran").val()))
		const nominal_tagihan = parseInt(formatAngka($("#nominal_tagihan").val()))
		const nominal_terbayar = parseInt(formatAngka($("#nominal_terbayar").val()))
		const total = nominal_terbayar + nominal_pembayaran

		const nominal = total > nominal_tagihan ? nominal_tagihan - nominal_terbayar : nominal_pembayaran

		const tunai = nominal_pembayaran
		const sisa = total > nominal_tagihan ? tunai - nominal_tagihan - nominal_terbayar : 0

		$.ajax({
			url: `${siteUrl}/pembayaran/action/add`,
			type: "POST",
			dataType: "json",
			data: {
				id_tagihan,
				nominal,
				tunai,
				sisa,
				catatan
			},
			success: (res) => {
				if (res.success) {
					toastr.success(res.message)
					newTransaction()
					cetakresi(res.trid)
				}
			},
			error: () => {
				toastr.error("Transaksi error, silahkan refresh halaman !")
			}
		})
	}
})

const validasiBayar = function () {
	const id_siswa = $("#id_siswa").val()
	const id_tagihan = $("#id_tagihan :selected").val()
	const nominal_pembayaran = formatAngka($("#nominal_pembayaran").val())

	let msg = ""

	if (id_siswa.length < 1) {
		msg += "Check Data Siswa, "
	}
	if (id_tagihan == undefined) {
		msg += "Check Akun Tagihan, "
	}
	if (nominal_pembayaran.length < 1) {
		msg += "Nominal Pembayaran Tidak Valid, "
	}

	if (msg.length > 0) {
		toastr.error(msg)
		return false
	}

	return true
}


const cetakresi = function (id) {
	const getData = getJSON(`${siteUrl}/pembayaran/action/get`, { id })
	const data = getData.data

	if (getData.success) {
		let kop = `
		<table class="header">
			<tr>
				<td width="18%"><img src="${siteUrl}/assets/images/logo_mts.png" alt="Logo MTS" width="100%" class="center"></td>
				<td width="82%">
					<table class="instansi">
						<tr>
							<th>MTsN 7 KEDITI</th>
						</tr>
						<tr>
							<td>Jl. Kebonsari No. 1, Kencong, Kec. Kepung, Gresik.</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	
	
		<table class="deskripsi">
			<tr>
				<th class="kiri">No. Resi</th>
				<td class="tengah" width="3%">:</td>
				<td class="kiri" width="65%">${id}</td>
			</tr>
			<tr>
				<th class="kiri">Tg. Resi</th>
				<td class="tengah">:</td>
				<td class="kiri">${moment(new Date(data.waktu_transaksi)).format("DD-MM-YYYY HH:mm")}</td>
			</tr>
			<tr>
				<th class="kiri">Petugas</th>
				<td class="tengah">:</td>
				<td class="kiri">${data.nama_petugas}</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<th class="kiri">NISN</th>
				<td class="tengah">:</td>
				<td class="kiri">${data.no_induk}</td>
			</tr>
			<tr>
				<th class="kiri">Nama Siswa</th>
				<td class="tengah">:</td>
				<td class="kiri">${data.nama}</td>
			</tr>
			<tr>
				<th class="kiri">Kelas</th>
				<td class="tengah">:</td>
				<td class="kiri">${data.kelas_tingkat}${data.kelas_label}</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<th class="kiri">Catatan</th>
				<td class="tengah">:</td>
				<td class="kiri">${data.catatan_transaksi}</td>
			</tr>
		</table>
		`

		let isi = `
			<table class="price">
				<tr class="top">
					<th colspan="2">${data.kode_akun} - ${data.nama_akun}</th>
					</tr>
					<tr>
					<td class="kiri">Rp. ${formatRupiah(data.jumlah_bayar)}</td>
      	</tr>
				<tr class="top">
					<td>&nbsp;</td>
				</tr>
			</table>
		`

		let bot = `
		<table class="deskripsi">
			<tr>
				<th class="kiri">Grand Total</th>
				<td class="tengah" width="3%">:</td>
				<td class="kiri" width="65%">Rp. ${formatRupiah(data.jumlah_bayar)}</td>
			</tr>
			<tr>
				<th class="kiri">Tunai</th>
				<td class="tengah">:</td>
				<td class="kiri">Rp. ${formatRupiah(data.tunai)}</td>
			</tr>
			<tr>
				<th class="kiri">Kembalian</th>
				<td class="tengah">:</td>
				<td class="kiri">Rp. ${formatRupiah(data.sisa)}</td>
			</tr>
		</table>

		<p class="tengah" style="margin-right: 4% !important">Resi ini tercetak oleh sitem dan merupakan bukti pembayaran yang sah.</p>
		`

		let mywindow = window.open(
			"",
			"PRINT RESI PEMBAYARAN - MTsN 7 GRESIK",
			"resizable=yes,width=720,height=" + screen.height
		)
		mywindow.document.write(
			`<!DOCTYPE html><html lang="en"><head><title>RESI PEMBAYARAN - MTsN 7 GRESIK"</title></head><link rel="stylesheet" href="${siteUrl}/assets/css/struckjual.css" media="all">`
		)
		mywindow.document.write(
			`<script src="${siteUrl}/assets/js/jquery.min.js"></`
		)
		mywindow.document.write(
			`script><script src="${siteUrl}/assets/custom-js/global_function.js"></`
		)
		mywindow.document.write("script><body>")
		mywindow.document.write(kop)
		mywindow.document.write(isi)
		mywindow.document.write(bot)
		mywindow.document.write("</body></html>")
		mywindow.document.close() // necessary for IE >= 10
		mywindow.focus() // necessary for IE >= 10

		let is_chrome = Boolean(window.chrome)
		if (is_chrome) {
			mywindow.onload = function () {
				setTimeout(function () {
					// wait until all resources loaded
					mywindow.print() // change window to winPrint
					mywindow.close() // change window to winPrint
				}, 200)
			}
		} else {
			mywindow.print()
			mywindow.close()
		}

		return true
	} else {
		toastr.error("Gagal Mencetak resi !")
	}
}