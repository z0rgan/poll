<?php

// resources for featured poll
function featured_poll() {
	global $c;
	$result = $c->query("SELECT * FROM poll_list ORDER BY RAND() LIMIT 1");
	return (object)$result->fetch_assoc();
}

// returns resources for requested poll id : $id
function getPoll($id) {
	global $c;
	$id = $c->escape_string($id);
	$result = $c->query("SELECT * FROM poll_list WHERE pid='$id'");
	return (object) $result->fetch_assoc();
}

// get resources of requested options id : $id
function getOptionFromId($id) {
	global $c;
	$id = $c->escape_string($id);
	$result = $c->query("SELECT * FROM poll_options WHERE opid='$id'");
	return (object) $result->fetch_assoc();
}

// get resources of all the options of requested poll id: $id
function getAllOptions($id) {
	global $c;
	$id = $c->escape_string($id);
	$result = $c->query("SELECT * FROM poll_options WHERE pid='$id'");
	$data = [];
	while($temp = $result->fetch_assoc()) {
		$data[] = $temp;
	}
	return $data;
}

//counts total vote : usage $var->total
function totalVote($id) {
	global $c;
	$id = $c->escape_string($id);
	$result = $c->query("SELECT SUM(count) as total FROM poll_options WHERE pid='$id'");
	return (object)$result->fetch_assoc();
}

// calculates percentage
function votePercent($value, $total) {
	return round($value/$total*100, 2);
}

// types of poll
function getPollType($type) {
	switch($type) {
		case 0: return "No duplication checking";
		break;
		case 1: return "IP duplication checking";
		break;
		case 2: return "Browser cookie Duplication checking";
		break;
		case 3: return "User Signin duplication checking";
		break;
	}
}

// sigin check
function isSigned() {
	if(!isset($_SESSION['username']) || empty($_SESSION['username'])) {
		return FALSE;
	}
	return TRUE;
}

// get poll comments
function getComments($id, $offset=0, $limit=20) {
	global $c;
	$id = $c->escape_string($id);
	$offset = $c->escape_string($offset);
	$limit = $c->escape_string($limit);
	$result = $c->query("SELECT comments.*,`users`.username FROM comments INNER JOIN users ON `users`.user_id=`comments`.user WHERE pid=$id ORDER BY cid DESC LIMIT $offset,$limit");
	$data = [];
	while($temp = $result->fetch_assoc()) {
		$data[] = $temp;
	}
	return $data;
}

// get user details from username
function getUser($user) {
	global $c;
	$user = $c->escape_string($user);
	$result = $c->query("SELECT * FROM users WHERE username='$user'");
	return (object) $result->fetch_assoc();
}

// check if poll exists
function pollExists($id) {
	global $c;
	$id = $c->escape_string($id);
	$result = $c->query("SELECT pid FROM poll_list WHERE pid='$id'");
	if($result->num_rows > 0) {
		return TRUE;
	}
	return FALSE;
}

// returns site name
function siteName() {
	return siteDetails()->sitename;
}
function siteDetails() {
	global $c;
	$result = $c->query("SELECT * FROM site_details");
	return (object) $result->fetch_assoc();
}

function getUserById($id) {
	global $c;
	$id = $c->escape_string($id);
	$result = $c->query("SELECT * FROM users WHERE user_id='$id'");
	return (object) $result->fetch_assoc();
}

// isadmin
function isAdmin() {
	if($_SESSION['username'] == "zorgan" || $_SESSION['username'] == "admin")
		return true;
	return false;
}


function cookieCheck($id) {
	global $c;
	$cookie = $c->escape_string($_COOKIE['uid']);
	$id = $c->escape_string($id);
	$query = $c->query("SELECT * FROM typecheck WHERE pid='$id' AND cookie='$cookie'");
	if($query->num_rows == 0)
		return 0;
	return 1;
}

function ipCheck($id) {
	global $c;
	$ip = $c->escape_string($_SERVER['REMOTE_ADDR']);
	$id = $c->escape_string($id);
	$query = $c->query("SELECT * FROM typecheck WHERE pid='$id' AND ip='$ip'");
	if($query->num_rows == 0)
		return 0;
	return 1;
}

function userCheck($id) {
	global $c;
	$uid = getUser($_SESSION['username'])->user_id;
	$id = $c->escape_string($id);
	$query = $c->query("SELECT * FROM typecheck WHERE pid='$id' AND uid='$uid'");
	if($query->num_rows == 0)
		return 0;
	return 1;
}

function fileName($length) {
    $key = '';
    $keys = array_merge(range(0, 9), range('a', 'z'));

    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }

    return $key;
}

?>