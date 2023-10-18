<?php
define('ENVIRONMENT', 1);

function connect($database=[]) {
	if (empty($GLOBALS['dbi'])) {
		if (empty($database)) {
			$database=[];
			$database['connect_string'] = 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';charset='.DB_CHARSET;
			$database['username'] = DB_USER;
			$database['password'] = DB_PASSWORD;
		}
		$GLOBALS['dbi'] = new PDO($database['connect_string'], $database['username'], $database['password']) or die;
		$GLOBALS['dbi']->exec("set names ".DB_CHARSET);
	}
	return $GLOBALS['dbi'];
}
function query($sql, $w=0) {
	global $dbi;//, $count_query;
	if ($w==1) {
		echo $sql;
	}
	//++$count_query;
	return $dbi->query($sql);
}
function execute($sql, $w=0) {
	global $dbi;
	if ($w == 1) {
		echo $sql;
	}
	return $dbi->exec($sql);
}
function quote($var, $p=null) {
	return $GLOBALS['dbi']->quote($var, $p);
}
function disconnect() {
	$GLOBALS['dbi'] = null;
}
function fetch($rs) {
	return $rs->fetch(PDO::FETCH_ASSOC);
}
function fetch_num($rs) {
	return $rs->fetch(PDO::FETCH_NUM);
}
function insert_id() {
	global $dbi;
	return $dbi->lastInsertId();
}
function upload_file($file, $dir, $accept_types=null, $size=null) {
	if (empty($file) || !is_uploaded_file($file['tmp_name'])) {
		return 2;
	} else {
		if ($size) {
			if ($file['size'] > $size) {
				return 3;
			}
		}
		$tmp = explode('.', $file['name']);
		$file_ext = $tmp[count($tmp)-1];
		if (is_array($accept_types)) {
			if (!in_array($file_ext, $accept_types)) {
				return 4;
			}
		}
		$newfile = md5(uniqid(rand(1000,9999))).'.'.$file_ext;
		$fullpath = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$newfile;
		move_uploaded_file($file['tmp_name'], $fullpath);
		return $newfile;
	}
}
function curl($url, $post=false, &$cookie='', $timeout=10, $agent='') {
	$result = false;
	$ln = curl_init();
	curl_setopt($ln, CURLOPT_URL, $url);
	if (!empty($agent)) {
		curl_setopt($ln, CURLOPT_USERAGENT, $agent);
	}
	// 	curl_setopt($ln, CURLOPT_HTTPHEADER, array($context));
	//curl_setopt($ln, CURLOPT_FOLLOWLOCATION, 1); //allow redirection
	curl_setopt($ln, CURLOPT_FORBID_REUSE, 1); //force close conn
	if ($cookie) {
		curl_setopt($ln, CURLOPT_COOKIESESSION, true);
		curl_setopt($ln, CURLOPT_COOKIE, $cookie);
	}
	curl_setopt($ln, CURLOPT_FRESH_CONNECT, true);
	curl_setopt($ln, CURLOPT_AUTOREFERER, false);
	curl_setopt($ln, CURLOPT_FAILONERROR, false);
	curl_setopt($ln, CURLOPT_FOLLOWLOCATION, false);
	curl_setopt($ln, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ln, CURLOPT_HEADER, true);
	if (is_array($post)) {
		curl_setopt($ln, CURLOPT_POST, true);
		curl_setopt($ln, CURLOPT_POSTFIELDS, http_build_query($post));
	}
	curl_setopt($ln, CURLOPT_RETURNTRANSFER, true);
 	curl_setopt($ln, CURLOPT_CONNECTTIMEOUT, $timeout);
 	curl_setopt($ln, CURLOPT_TIMEOUT, $timeout);
// 	curl_setopt($ln, CURLOPT_TIMEOUT_MS, $timeout);

	$response = curl_exec($ln);

	$info = curl_getinfo($ln);
	curl_close($ln);
	preg_match_all("#Set-Cookie: (.*?; path=.*?;.*?)\n#", $response, $matches);
	array_shift($matches);
	$cookie = implode("\n", $matches[0]);

	if (!empty($response) && $info['http_code'] == 200) {
		$lines = explode("\r\n",$response);
		$status = false;
		$has_content = false;
		foreach ($lines as $i => $line){
			if (preg_match("/200 OK/", $line)) {
				$status = true;
			}
			if($line == "" && $status){
				$has_content = $i;
				break;
			}
		}
		if($has_content !== false){
			$result = implode("\r\n", array_slice($lines, $has_content));
		}
	}
	$result = preg_replace('/\x{EF}\x{BB}\x{BF}/','', $result);
	return $result;
}
function dbsave($table, $o, $w=0) {
	$sk = '';
	$sv = '';
	foreach ($o as $k => $v) {
		if (is_null($v)) continue;
		$v = quote($v);
		$sk .= ',`'.$k.'`';
		$sv .= ','.$v;
		$su .= ',`'.$k.'`='.$v;
	}
	$sk = substr($sk, 1);
	$sv = substr($sv, 1);
	$su = substr($su, 1);
	$sql = "INSERT INTO $table($sk) VALUES ($sv) ON DUPLICATE KEY UPDATE $su";
	unset ($sk, $sv);
	return query($sql, $w);
}
function dbget($table, $fil, $c=0, $w=0) {
	if ($c > 0) {
		$key = $table.md5(json_encode($fil));
	}
	$result = false;
	$ls = array();
	$field = '*';
	if (isset($fil['__field'])) {
		$field = $fil['__field'];
		unset($fil['__field']);
	}
	if (isset($fil['__group'])) {
		$group = ' GROUP BY '. $fil['__group'];
		unset($fil['__group']);
	}
	$sql = "SELECT $field FROM `$table` WHERE 1";
	foreach ($fil as $k => $v) {
		$operator = '=';
		if (is_array($v)) {
			$operator = $v[0];
			$v = $v[1];
			if (is_array($v)) {
				$operator = strtoupper($operator);
				if ($operator == 'IN' || $operator == 'NOT IN') {
					foreach ($v as $k => $vi) {
						$v[$k] = quote($vi);
					}
					$v = implode(',',$v);
				} else if ($operator == 'BETWEEN') {
					$v = quote($v[0]) . ' AND ' . quote($v[1]);
				}
				$operator = ' '.$operator.' ';
			} else {
				$v = quote($v);
			}
		} else {
			$v = quote($v);
		}
		$sql .= ' AND `'.$k.'`'.$operator.$v;
	}
	$sql .= $group;
	$rs = query($sql, $w);
	while ($r = fetch($rs)) {
		$ls[] = $r;
	}
	$rs = null;

	if (count($ls) > 0) {
		$result = $ls[0];
	}
	return $result;
}
function dblist($table, $fil, $c=0, $w=0) {
	$main_table = $table;
	$table = "`$table`";
	$result = false;
	$ls = array();
	$field = '*';
	if (isset($fil['__field'])) {
		$field = $fil['__field'];
		unset($fil['__field']);
	}
	$group = '';
	if (isset($fil['__group'])) {
		$group = ' GROUP BY '. $fil['__group'];
		unset($fil['__group']);
	}
	$order = '';
	if (isset($fil['__order'])) {
		$order = ' ORDER BY '. $fil['__order'];
		unset($fil['__order']);
	}
	$limit = '';
	if (isset($fil['__limit'])) {
		$limit = ' LIMIT '. $fil['__limit'];
		unset($fil['__limit']);
	}
	$offset = '';
	if (isset($fil['__offset'])) {
		$limit = ' OFFSET '. $fil['__offset'];
		unset($fil['__offset']);
	}
	if (isset($fil['__join'])) {
		foreach ($fil['__join'] as $join_table => $join) {
 			$table .= " LEFT JOIN `$join_table` ";
			foreach ($join as $a => $b) {
				$table .= " ON(`$main_table`.`$a` = `$join_table`.`$b`)";
			}
		}
		unset($fil['__join']);
	}
	$sql = "SELECT $field FROM $table WHERE 1";
	foreach ($fil as $k => $v) {
		$operator = '=';
		if (is_array($v)) {
			$operator = $v[0];
			$v = $v[1];
			if (is_array($v)) {
				$operator = strtoupper($operator);
				if ($operator == 'IN' || $operator == 'NOT IN') {
					foreach ($v as $ki => $vi) {
						$v[$ki] = quote($vi);
					}
					$v = '(' . implode(',',$v) . ')';
				} else if ($operator == 'BETWEEN') {
					$v = quote($v[0]) . ' AND ' . quote($v[1]);
				}
				$operator = ' '.$operator.' ';
			} else {
				$v = quote($v);
			}
		} else {
			$v = quote($v);
		}
		if (strpos($k, '.') === FALSE) {
			$k = "`$k`";
		}
		$sql .= ' AND '.$k.' '.$operator.' '.$v;
	}
	$sql .= $group.$order.$limit.$offset;
	$rs = query($sql, $w);
	while ($r = fetch($rs)) {
		$ls[] = $r;
	}
	$rs = null;
	return $ls;
}
function now() {
	return date('Y-m-d H:i:s', current_time());
}

function app_run($default='', $database='') {
	if (empty($_GET['m'])) {
		if ($default) {
			$_GET['m'] = $default;
		} else {
			return false;
		}
	}
	connect($database);
	$f = $_GET['m'].'_action';
	@$f();
	disconnect();
}

function json_request() {
	$inputJSON = file_get_contents('php://input');
	$input = json_decode($inputJSON, TRUE);
	return $input;
}

function get($name, $default=null) {
	return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
}

function is_post() {
	return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function json_response($json_string) {
	header('Content-Type: application/json');
	echo json_encode($json_string);
}
