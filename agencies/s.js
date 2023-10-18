var $ = jQuery;
var X_GOOGLE_API_KEY = 'AIzaSyDtupLwQ2KfzHCo37usxBecGKn3W65WQlQ';
var x = {};
x.change_alias = function(alias) {
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
};
$.fn.serializeObject = function() {
    var o = {};
    $(this).find('input[type="hidden"], input[type="text"], input[type="password"], input[type="checkbox"]:checked, input[type="radio"]:checked, select').each(function() {
        if ($(this).attr('type') == 'hidden') { //if checkbox is checked do not take the hidden field
            var $parent = $(this).parent();
            var $chb = $parent.find('input[type="checkbox"][name="' + this.name.replace(/\[/g, '\[').replace(/\]/g, '\]') + '"]');
            if ($chb != null) {
                if ($chb.prop('checked')) return;
            }
        }
        if (this.name === null || this.name === undefined || this.name === '')
            return;
        var elemValue = null;
        if ($(this).is('select'))
            elemValue = $(this).find('option:selected').val();
        else elemValue = this.value;
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(elemValue || '');
        } else {
            o[this.name] = elemValue || '';
        }
    });
    return o;
};

x.call = function(url, data) {
	x.loading(1);
	return new Promise(function (resolve, reject) {
		$.ajax({
			type: 'POST',
			data: JSON.stringify(data),
			dataType: 'json',
			contentType: "application/json; charset=utf-8",
			url: '/agencies/index.php?m='+url
		}).done(function (res) {
			x.loading(0);
			return resolve(res);
		}).fail(function (res) {
			x.loading(0);
			console.log("[ERROR]", res);
			return reject(res);
		});
	});
};
x.loading = function(v) {
	var a = document.getElementById("loading");
	if (a != null) {
		a.style.display = v ? "block": "none";
	}
};
x.hash_run = function() {
	if (window.location.hash.indexOf('#agencies') !== -1) {
		$('.wp-has-current-submenu').addClass('wp-not-current-submenu').removeClass('wp-has-current-submenu');
		$('.wp-has-current-submenu').removeClass('wp-has-current-submenu');
		$('#menu-agencies,#agencies-link').addClass('wp-has-current-submenu');
		document.title = 'Agencies ‹  — WordPress';
		$('meta[name="description"]').attr("content", 'Agencies');

	}
	if (window.location.hash == '#agencies') {
		$('#wpbody-content').load('/agencies/list.html', function() {
			x.agencies_load();
		});
		return;
	}
	if (window.location.hash.indexOf('#agencies/') !== -1) {
		var param = window.location.hash.replace('#agencies/', '');
		$('#wpbody-content').load('/agencies/form.html', function() {
			$.getScript('https://maps.googleapis.com/maps/api/js?key='+X_GOOGLE_API_KEY+'&callback=xInitMap');
			x.agencies_form_load(param);
		});
		return;
	}
};
var map, marker;
function xInitMap() {
	var lat = 10.771797;
	var lng = 106.697833;
	var myLatLng = {lat: lat, lng: lng};
	map = new google.maps.Map(document.getElementById('map'), {
		center: myLatLng,
		zoom: 6
	});
	marker = new google.maps.Marker({
		position: myLatLng,
		map: map,
		title: 'Agency',
		draggable:true
	});
	google.maps.event.addListener(marker, "dragend", function(event) {
		var lat = event.latLng.lat();
		var lng = event.latLng.lng();
		$('#lat').val(lat);
		$('#lng').val(lng);
	});
}
x.agencies_init = function() {
	window.onhashchange = x.hash_run;
	$('head').append('<link rel="stylesheet" type="text/css" href="/agencies/s.min.css">');
	$('body').prepend('<div id="loading" class="loading"><div class="ball"></div><div class="ball1"></div></div>');
	$('#menu-settings').after('<li class="menu-top menu-icon-settings" id="menu-agencies"><a href="javascript:;" id="agencies-link" class="menu-top menu-icon-settings" aria-haspopup="false"><div class="wp-menu-image dashicons-before dashicons-admin-settings"><br></div><div class="wp-menu-name">Agencies </div></a></li>');
	$('#agencies-link').click(function() {
		location.href = '/wp-admin/#agencies';
	});
	x.hash_run();
};

x.agencies_load = function() {
	$('#search-submit').click(function() {
		var name = x.change_alias($('#post-search-input').val());
		var data = x.agencies_list.slice(0);
		if (name != "") {
			for (i = data.length - 1; i >=0; i--) {
				if (data[i].alias1.indexOf(name) === -1 &&
						data[i].alias2.indexOf(name) === -1) {
					data.splice(i, 1);
				}
			}
		}
		x.render_list(data);
	});
	x.vn_load(function() {
		x.call('agencies_list', {}).then(function(data) {
			for (var i = 0; i < data.length; i++) {
				data[i].alias1 = x.change_alias(data[i].name);
				data[i].alias2 = x.change_alias(data[i].address);
			}
			x.agencies_list = data;
			x.render_list(data);
		});
	});
};
x.render_list = function(data) {
	var list = $('#the-list');
	list.html('');
	var html = $('#agencies_tpl').html();
	$('.total-num').text(data.length);
	for (var i = 0; i < data.length; ++i) {
		var a = data[i];
		list.append(html);
		var nr = $('tr:last-child', list);
		nr.attr('id',"r-"+a.id);
		$('.agency-name', nr).text(a.name);
		$('.agency-phone', nr).text(a.phone);
		$('.agency-address', nr).text(a.address);
		$('.agency-edit-link', nr).attr('href', '#agencies/'+a.id);
		$('.agency-trash-link', nr).attr('data-id', a.id).click(x.agency_remove);
		for (var j in x.vn_data) {
			if (j == a.province) {
				$('.agency-province', nr).text(x.vn_data[j].name);
			}
		}
	}
};

x.vn_load = function(cb) {
	if (cb == null) {
		cb = function(){};
	}
	if (x.vn_data !== undefined) return cb();
	var data = localStorage.getItem('vn_data');
	if (data == null) {
		$.get('/agencies/vn.json', function(res) {
			x.vn_data = res;
			localStorage.setItem('vn_data', JSON.stringify(x.vn_data));
			return cb();
		});
	} else {
		x.vn_data = JSON.parse(data);
		return cb();
	}
};

x.agencies_form_load = function(id) {
	var i, j;
	x.vn_load(function() {
		var str = '<option value="-1">Choose a value</option>';
		for (i in x.vn_data) {
			var p = x.vn_data[i];
			str += '<option value="'+i+'">'+p.name+'</option>';
		}
		$('#province').html(str);
		$('#province').change(function(){
			console.log('not change');
			var str2 = '<option value="-1">Choose a value</option>';
			var v = $(this).val();
			for (i in x.vn_data) {
				if (i == v) {
					var p = x.vn_data[i];
					for (j in p["quan-huyen"]) {
						var d = p["quan-huyen"][j];
						str2 += '<option value="'+j+'">'+d.name+'</option>';
					}
				}
			}
			$('#district').html(str2);
			if (x.agency !== null) {
				$('#district').val(x.agency.district);
			} else {
				$('#district').val(-1);
			}
		});

		if (id == "") {
			x.agency = null;
		} else {
			x.call('agencies_get&id='+id, null).then(function(res) {
				x.agency = res;
				x.agency_form_fill();
			});
		}

	});

	$('#agency_form').submit(function(e){
		e.preventDefault();
		x.call('agencies_put', $(this).serializeObject()).then(function(res) {
			if (!res.result) {
				alert(res.msg);
				return;
			}
			x.agency = null;
			location.hash = '#agencies';
		});
		return false;
	});
};
x.agency_form_fill = function() {
	$('#agency_id').val(x.agency.id);
	$('#name').val(x.agency.name);
	$('#address').val(x.agency.address);
	$('#lat').val(x.agency.lat);
	$('#lng').val(x.agency.lng);
	$('#phone').val(x.agency.phone);
	$('#province').val(x.agency.province).change();
	var myLatLng = {lat: parseFloat(x.agency.lat), lng: parseFloat(x.agency.lng)};
	map.setCenter(myLatLng);
	marker.setPosition(myLatLng);
};
x.agency_remove = function() {
	var id = $(this).attr('data-id');
	if (id == null) {
		alert('Something went error!');
		return;
	}
	x.call('agencies_remove&id='+id, null).then(function(res) {
		if (!res.result) {
			alert(res.msg);
			return;
		}
		alert("Successfully");
		$('#r-'+id).remove();

		var n = $('.total-num').eq(0).text();
		$('.total-num').text(0+n-1);
	});
};
x.agencies_init();
