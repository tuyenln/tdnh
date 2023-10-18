var $ = jQuery;
var map;
function agencies_init() {
	$('head').append('<link rel="stylesheet" type="text/css" href="/agencies/c.min.css">');
	$('#main article:first-child').html('<div id="agencies"></div>');
	var margin = (($(window).width() - $('.container').eq(0).width() -20) / 2);
	// $('#agencies').css('margin', '0px -'+margin+'px');
	$('#agencies').load('/agencies/index.html', function() {
		$.getScript('https://maps.googleapis.com/maps/api/js?key=AIzaSyDtupLwQ2KfzHCo37usxBecGKn3W65WQlQ&callback=xInitMap');
	});
}
function fill_x_side(data, province) {
	var list = $('#x-side');
	list.html('');
	var tpl = $('#agency-item-tpl').html();
	var countStr = 'Tìm thấy <span style="color:#CC0000;font-weight:700;">'+data.length+'</span> nhà phân phối';
	if (province != null) {
		if (province in window.vn_data) {
			countStr += ' tại ' + window.vn_data[province].name;
		}
	}
	list.append('<div class="itemCount">'+countStr+'</div>');
	for (var i in data) {
		var a = data[i];
		list.append(tpl);
		var nr = $('div:last-child', list);
		nr.attr('id', 'r-'+a.id).attr('data-lat', a.lat).attr('data-lng', a.lng);
		$('.itemTitle', nr).text(a.name);
		$('.itemAddress', nr).text(a.address);
		$('.itemPhoneData', nr).text(a.phone);
	}
}
function xItemClick(e) {
	e = $(e);
	map.setCenter({lat: parseFloat(e.attr('data-lat')), lng: parseFloat(e.attr('data-lng'))});
	map.setZoom(11);
}

function loadMarker(data) {
	for (var i in data) {
		var a = data[i];
		var myLatLng = {lat: parseFloat(a.lat), lng: parseFloat(a.lng)};
		marker = new google.maps.Marker({
			position: myLatLng,
			map: map,
			title: a.name + "\n" + a.address + "\nĐiện thoại: "+a.phone
		});
	}
}

function fill_province_list() {
	var str = '<option value="-1">Chọn tỉnh thành</option>';
	var data = window.vn_data;
	for (var i in data) {
		var p = data[i];
		str += '<option value="'+i+'">'+p.name+'</option>';
	}
	$('#province').html(str);
	$('#province').change(function(){
		var str2 = '<option value="-1">Chọn quận huyện</option>';
		var v = $(this).val();
		for (var i in data) {
			if (i == v) {
				var p = data[i];
				for (j in p["quan-huyen"]) {
					var d = p["quan-huyen"][j];
					str2 += '<option value="'+j+'">'+d.name+'</option>';
				}
			}
		}
		$('#district').html(str2);
	});
}
function vn_load(cb) {
	if (cb == null) {
		cb = function(){};
	}
	if (window.vn_data !== undefined) return cb();
	var data = localStorage.getItem('vn_data');
	if (data == null) {
		$.get('/agencies/vn.json', function(res) {
			window.vn_data = res;
			localStorage.setItem('vn_data', JSON.stringify(window.vn_data));
			return cb();
		});
	} else {
		window.vn_data = JSON.parse(data);
		return cb();
	}
}
function xInitMap() {
	var lat = 16.461960;
	var lng = 103.603771;
	var myLatLng = {lat: lat, lng: lng};
	map = new google.maps.Map(document.getElementById('map'), {
		center: myLatLng,
		zoom: 6
	});
	vn_load(function() {
		fill_province_list();
		agencies_load().then(function(data) {
			for (var i = 0; i < data.length; i++) {
				data[i].alias1 = x_change_alias(data[i].name);
				data[i].alias2 = x_change_alias(data[i].address);
			}
			window.agencies_list = data;
			searchFormSubmit();
			loadMarker(data);
			fill_x_side(data);
		});
	});
}

function searchFormSubmit() {
	$('#agencies-form').submit(function(e) {
		e.preventDefault();
		var i;
		var province = $('#province').val();
		var district = $('#district').val();
		var name = x_change_alias($('#name').val());
		var data = window.agencies_list.slice(0);//Object.assign({}, window.agencies_list);
		if (province != -1) {
			for (i = data.length - 1; i >=0; i--) {
				if (data[i].province !== province) {
					data.splice(i, 1);
				}
			}
		}
		if (district != -1) {
			for (i = data.length - 1; i >=0; i--) {
				if (data[i].district !== district) {
					data.splice(i, 1);
				}
			}
		}
		if (name != "") {
			for (i = data.length - 1; i >=0; i--) {
				if (data[i].alias1.indexOf(name) === -1 &&
						data[i].alias2.indexOf(name) === -1) {
					data.splice(i, 1);
				}
			}
		}
		fill_x_side(data, province);
		if (data.length > 0) {
			var a = data[0];
			map.setCenter({lat: parseFloat(a.lat), lng: parseFloat(a.lng)});
			map.setZoom(11);
		}
		return false;
	});
}
function agencies_load() {
	return new Promise(function (resolve, reject) {
		$.ajax({
			type: 'POST',
			dataType: 'json',
			contentType: "application/json; charset=utf-8",
			url: '/agencies/index.php'
		}).done(function (res) {
			return resolve(res);
		}).fail(function (res) {
			console.log("[ERROR]", res);
			return reject(res);
		});
	});
}
function x_change_alias(alias) {
    var str = alias;
    str = str.toLowerCase();
    str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g,"a");
    str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g,"e");
    str = str.replace(/ì|í|ị|ỉ|ĩ/g,"i");
    str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g,"o");
    str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g,"u");
    str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g,"y");
	str = str.replace(/đ/g,"d");
    // str = str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'|\"|\&|\#|\[|\]|~|\$|_|`|-|{|}|\||\\/g," ");
    str = str.replace(/ + /g," ");
	str = str.trim();
	return str;
	// return alias;
}
agencies_init();
