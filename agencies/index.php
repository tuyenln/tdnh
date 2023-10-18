<?php
require_once '../wp-config.php';
require_once 'f.php';
define ('AGENCIES_TBL', 'xoc_agencies');

app_run('index');

function index_action() {
	$list = dblist(AGENCIES_TBL, ['status' => 1]);
	return json_response($list);
}

function agencies_list_action() {
	$list = dblist(AGENCIES_TBL, ['status' => 1]);
	return json_response($list);
}

function agencies_get_action() {
	$id = get('id');
	if (empty($id)) {
		return json_response(['result'=>false]);
	}
	$list = dbget(AGENCIES_TBL, ['id' => $id]);
	return json_response($list);
}

function agencies_put_action() {
	$agency = json_request();
	if (!is_numeric($agency['id'])) {
		unset($agency['id']);
	}
	if (empty($agency['name'])) {
		return json_response(['result'=>false,'msg'=>'Vui lòng nhập tên']);
	}
	if (empty($agency['province']) || $agency['province'] == -1) {
		return json_response(['result'=>false,'msg'=>'Vui lòng chọn tỉnh thành']);
	}
	if (empty($agency['district']) || $agency['district'] == -1) {
		return json_response(['result'=>false,'msg'=>'Vui lòng chọn quận huyện']);
	}
	$agency['status'] = 1;
	dbsave(AGENCIES_TBL, $agency);
	return json_response(['result'=>true]);
}

function agencies_remove_action() {
	$id = get('id');
	if (empty($id)) {
		return json_response(['result'=>false]);
	}
	$agency = ['id' => $id, 'status' => 0];

	dbsave(AGENCIES_TBL, $agency);
	return json_response(['result'=>true]);
}

function create_action() {
	query('
	CREATE TABLE `xoc_agencies` (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `name` varchar(500) DEFAULT NULL,
	  `phone` varchar(50) DEFAULT NULL,
	  `address` varchar(500) DEFAULT NULL,
	  `province` varchar(20) DEFAULT NULL,
	  `district` varchar(20) DEFAULT NULL,
	  `lat` double DEFAULT NULL,
	  `lng` double DEFAULT NULL,
	  `status` tinyint(1) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
}
