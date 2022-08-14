/*******************************************/
$(document).ready(function () {
	toastr.options = {
		closeButton: true,
		debug: false,
		newestOnTop: true,
		progressBar: false,
		positionClass: "toast-top-right",
		preventDuplicates: false,
		showDuration: "1000",
		hideDuration: "1000",
		timeOut: "3200",
		extendedTimeOut: "1000",
		showEasing: "swing",
		hideEasing: "linear",
		showMethod: "fadeIn",
		hideMethod: "fadeOut",
		progressBar: true,
	};
});

/* *************************************** */
/*          JAVASCRIPT FUNCTION            */
/* *************************************** */

var delay = (function () {
	var timer = 0;
	return function (callback, ms) {
		clearTimeout(timer);
		timer = setTimeout(callback, ms);
	};
})();

function titleCase(str) {
	var splitStr = str.toLowerCase().split(" ");
	for (var i = 0; i < splitStr.length; i++) {
		// You do not need to check if i is larger than splitStr length, as your for does that for you
		// Assign it back to the array
		splitStr[i] =
			splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);
	}
	// Directly return the joined string
	return splitStr.join(" ");
}

function getUrlVars() {
	var vars = [],
		hash;
	var hashes = window.location.href
		.slice(window.location.href.indexOf("?") + 1)
		.split("&");
	for (var i = 0; i < hashes.length; i++) {
		hash = hashes[i].split("=");
		vars.push(hash[0]);
		vars[hash[0]] = hash[1];
	}
	return vars;
}

function arrayKey(arr, val) {
	for (var i = 0; i < arr.length; i++) if (arr[i] === val) return i;
	return false;
}

moment.locale("id");

function daysDiff(dayOne, dayTwo) {
	const one = moment(moment(new Date(dayOne)).format("YYYY-MM-DD"));
	const two = moment(moment(new Date(dayTwo)).format("YYYY-MM-DD"));
	const duration = moment.duration(one.diff(two));
	return Math.abs(duration.asDays());
}

function daysInThisMonth() {
	var now = new Date();
	return new Date(now.getFullYear(), now.getMonth() + 1, 0).getDate();
}

function daysInMonth(month, year) {
	return new Date(year, month, 0).getDate();
}

String.prototype.replaceArray = function (find, replace) {
	var replaceString = this;
	var regex;
	for (var i = 0; i < find.length; i++) {
		regex = new RegExp(find[i], "g");
		replaceString = replaceString.replace(regex, replace[i]);
	}
	return replaceString;
};

function titleCase(str) {
	str = str.toLowerCase();

	str = str.split(" ");

	for (var i = 0; i < str.length; i++) {
		str[i] = str[i].charAt(0).toUpperCase() + str[i].slice(1);
	}
	return str.join(" ");
}

function formatDate(date) {
	let d = new Date(date),
		month = "" + (d.getMonth() + 1),
		day = "" + d.getDate(),
		year = d.getFullYear();

	if (month.length < 2) month = "0" + month;
	if (day.length < 2) day = "0" + day;

	return [year, month, day].join("-");
}

function unikue(date) {
	let d = new Date(date),
		month = "" + (d.getMonth() + 1),
		day = "" + d.getDate(),
		year = d.getFullYear();
	h = d.getHours();
	i = d.getMinutes();
	s = d.getSeconds();

	if (month.length < 2) month = "0" + month;
	if (day.length < 2) day = "0" + day;

	return [year, month, day, h, i, s].join("");
}

function uniqueId() {
	let d = new Date(),
		month = "" + (d.getMonth() + 1),
		day = "" + d.getDate(),
		year = d.getFullYear().toString().substr(2, 2);
	h = (d.getHours() < 10 ? "0" : "") + d.getHours();
	i = (d.getMinutes() < 10 ? "0" : "") + d.getMinutes();
	s = (d.getSeconds() < 10 ? "0" : "") + d.getSeconds();

	if (month.length < 2) month = "0" + month;
	if (day.length < 2) day = "0" + day;

	return [year, month, day, h, i, s].join("");
}

const formatRupiah = (angka, prefix) => {
	let number_string = angka.replace(/[^,\d]/g, "").toString(),
		split = number_string.split(","),
		sisa = split[0].length % 3,
		rupiah = split[0].substr(0, sisa),
		ribuan = split[0].substr(sisa).match(/\d{3}/gi);

	// tambahkan titik jika yang di input sudah menjadi angka ribuan
	if (ribuan) {
		separator = sisa ? "." : "";
		rupiah += separator + ribuan.join(".");
	}

	rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
	return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
};

const formatAngka = (apapun) => {
	return apapun.replace(/[^,\d]/g, "").toString();
};

/* *************************************** */
/*         END JAVASCRIPT FUNCTION         */
/* *************************************** */

const prot = location.protocol;
const host = window.location.hostname;
const siteUrl = `${prot}//${host}`;

function getJSON(url, data) {
	return JSON.parse(
		$.ajax({
			type: "POST",
			url: url,
			data: data,
			dataType: "json",
			global: false,
			async: false,
			success: function (result) {},
			error: function (jqXHR, textStatus, errorThrown) {
				// if (textStatus == "parsererror") window.location.href = `${siteUrl}${window.location.pathname}`
			},
		}).responseText
	);
}

const logout = () => {
	localStorage.clear();

	window.location.href = `${siteUrl}/auth/logout`;
};

const cDate = new Date();
const fDate = new Date(cDate.getFullYear(), cDate.getMonth(), 1);
const fDay = moment(fDate).format("YYYY-MM-DD 00:00:00");
const cDay = moment(cDate).format("YYYY-MM-DD 23:59:59");

$.fn.inputFilter = function (callback, errMsg) {
	return this.on(
		"input keydown keyup mousedown mouseup select contextmenu drop focusout",
		function (e) {
			if (callback(this.value)) {
				// Accepted value
				if (["keydown", "mousedown", "focusout"].indexOf(e.type) >= 0) {
					$(this).removeClass("input-error");
					this.setCustomValidity("");
				}
				this.oldValue = this.value;
				this.oldSelectionStart = this.selectionStart;
				this.oldSelectionEnd = this.selectionEnd;
			} else if (this.hasOwnProperty("oldValue")) {
				// Rejected value - restore the previous one
				$(this).addClass("input-error");
				this.setCustomValidity(errMsg);
				this.reportValidity();
				this.value = this.oldValue;
				this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
			} else {
				// Rejected value - nothing to restore
				this.value = "";
			}
		}
	);
};
