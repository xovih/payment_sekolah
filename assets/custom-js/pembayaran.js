let aliassiswa = "";

function opsiSiswa() {
	const kom = $("#pilihan_siswa");
	const siswa = $("#id_siswa");
	const formclass = ".eventInsForm";
	const tujuan = $("#id_tagihan");

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
					const data = res.data.data ? res.data.data : [];

					if (data.length > 0 && data.length < 2) {
						const item = data[0];

						if (item.id_siswa) {
							siswa.val(item.id_siswa);
							kom.val(`${item.no_induk} - ${item.nama}`);
							kom.autocomplete("close");
							aliassiswa = `${item.no_induk} - ${item.nama}`;
							buildTagihan(item.id_siswa);
							setTimeout(() => {
								tujuan.select2("focus");
							}, 250);
						} else {
							siswa.val("");
						}
					} else {
						response(
							$.map(data, function (item) {
								let object = new Object();
								object.label = `${item.no_induk} - ${item.nama}`;
								object.value = `${item.no_induk} - ${item.nama}`;
								object.id_siswa = item.id_siswa;
								return object;
							})
						);
					}
				},
			});
		},
		delay: 25,
		autoFocus: true,
		select: function (event, ui) {
			kom.val(ui.item.value);
			siswa.val(ui.item.id_siswa);
			aliassiswa = ui.item.id_siswa;
			buildTagihan(ui.item.id_siswa);
			setTimeout(() => {
				tujuan.select2("focus");
			}, 250);
		},
		focus: function (event, ui) {
			event.preventDefault();
		},
	});

	kom.autocomplete("option", "appendTo", formclass);
}

$("#pilihan_siswa").on("keyup", (e) => {
	e.preventDefault();

	const thisvalue = $("#pilihan_siswa").val();

	if (thisvalue.trim() != aliassiswa.trim()) $("#id_siswa").val("");
});

function buildTagihan(id_siswa) {
	const reqTagihan = getJSON(`${siteUrl}/tagihan/action/list`, { id_siswa });

	const opsi = $("#id_tagihan");
	opsi.find("option").remove();

	if (reqTagihan.success) {
		if (reqTagihan.data.data.length > 0) {
			const tagihan = reqTagihan.data.data;

			opsi.append(`<option value="-">Pilih Akun Tagihan</option>`);

			$.each(tagihan, (i, v) => {
				opsi.append(
					`<option value="${v.id_detail}">${v.kode_akun} - ${v.nama_akun}</option>`
				);
			});
		}
	}
}

$("#id_tagihan").on("change", function (e) {
	e.preventDefault();

	const id_detail = $("#id_tagihan :selected").val();

	const reqTagihan = getJSON(`${siteUrl}/tagihan/action/get`, {
		id: id_detail,
	});

	const tagihan = parseInt(reqTagihan.data.nominal);
	const terbayar = parseInt(reqTagihan.data.terbayar);
	const kekurangan = tagihan - terbayar < 0 ? 0 : tagihan - terbayar;

	$("#nominal_tagihan").val(formatRupiah(reqTagihan.data.nominal));
	$("#nominal_terbayar").val(formatRupiah(kekurangan.toString()));
});

$("#nominal_pembayaran").on("keyup", function () {
	const komponen = $("#nominal_pembayaran");
	const bilangan = formatAngka(komponen.val());
	komponen.val(formatRupiah(bilangan));
});

$("#formulir-bayar").on("click", (e) => {
	if (validasi()) {
	}
});
