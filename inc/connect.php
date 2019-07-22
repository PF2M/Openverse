<?php
if(!(include_once 'settings.php') || !isset($open_db_host)) {
require_once __DIR__ . '/htm.php';
error_log('Either settings.php could not be loaded or the datbase variables are not defined in it.');
openServerErr(503, 'The Openverse server is not configured correctly.');
}


$link = mysqli_connect($open_db_host, $open_db_user, $open_db_pass);

if(!$link || !mysqli_select_db($link, $open_db_name)) {
	require_once __DIR__ . '/htm.php';
	openServerErr(503, 'The database servers appear to be down at the moment, try coming back later.');
}
mysqli_set_charset($link, 'utf8mb4');


session_name('openverse');
if(session_status() == PHP_SESSION_NONE) {
session_start();
}
// It's time for autoauth!

if(!isset($_SESSION['user_pid']) && isset($_COOKIE['openverse-auth'])) {
// Look for token. Can't be older than the date defined in settings.php.
if(!isset($settings['max_token_life'])) { $max_token_life = 5; } 
else { $max_token_life = $settings['max_token_life']; }
// Delete old tokens and then look for new ones.
mysqli_query($link, 'DELETE FROM login_tokens WHERE login_tokens.token_created < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL '.($max_token_life).' DAY))');
$search_token = mysqli_query($link, 'SELECT token_for FROM login_tokens WHERE login_tokens.token_id = "'.mysqli_real_escape_string($link, $_COOKIE['openverse-auth']).'" LIMIT 1');
// If we found things..
if($search_token && mysqli_num_rows($search_token) != 0) {
// .. then look for their user_pid and log them the fuck in! We can't get blank stuff since token_for is cascading on user_pid.
$search_user = mysqli_fetch_assoc(mysqli_query($link, 'SELECT user_id, user_pid, user_name, user_rank, user_avatar FROM users WHERE users.user_pid = "'.mysqli_fetch_assoc($search_token)['token_for'].'"'));
$_SESSION['signed_in'] = true;
$_SESSION['user_id'] = $search_user['user_id'];
$_SESSION['user_pid'] = $search_user['user_pid'];
$_SESSION['user_name'] = $search_user['user_name'];
$_SESSION['user_rank'] = $search_user['user_rank'];
$_SESSION['user_avatar'] = $search_user['user_avatar'];
$_SESSION['user_timezone'] = 'America/New_York';
    }
}
// End of autoauth.

// Wrappers for prepared statements.
// Example: prepared('SELECT * FROM posts WHERE posts.post_id = ? AND posts.post_by = ?', array(12, 1));
function prepared($txt, $values = null) {
global $link;
$stmt = $link->prepare($txt);
if($values !== null) {
	$params = '';
		foreach($values as &$param) {
		$params .= is_int($param) ? 'i' : 's';
		}
	$funcparam = array_merge(array($params), $values);
	foreach($funcparam as $key => $value)
	$tmp[$key] = &$funcparam[$key];
	call_user_func_array([$stmt, 'bind_param'], $tmp);
	}
$stmt->execute();
	if(!$stmt || $stmt->errno) {
	return false;
	} else {
	return $stmt->get_result();	
	}
}
function nice_ins($table, $values) {
global $link;
$stmt = $link->prepare('INSERT INTO '.$table.'('.(implode(', ', array_keys($values))).')
VALUES('.rtrim(str_repeat('?, ', count($values)), ', ').')');
	$params = '';
		foreach($values as &$param) {
		$params .= is_int($param) ? 'i' : 's';
        }
	$funcparam = array_merge(array($params), array_values($values));
	foreach($funcparam as $key => $value) $tmp[$key] = &$funcparam[$key];
	call_user_func_array([$stmt, 'bind_param'], $tmp);
$stmt->execute();
	if(!$stmt || $stmt->errno) {
	return false;
	} else {
	return true;	
	}
}


$classes = [];
$signed_in = (isset($_SESSION['signed_in']) && $_SESSION['signed_in']==true);

/* User variable, set as $user. Null if not logged in. */
if($signed_in) {
$user = mysqli_fetch_assoc(prepared('SELECT * FROM users WHERE users.user_pid = ? LIMIT 1', array($_SESSION['user_pid'])));
} else {
$user = null;
}

date_default_timezone_set('America/New_York');
mysqli_query($link, 'SET time_zone = "-5:00"');
function displayTime($datetime){
$timeSincePost = time() - strtotime($datetime);
if($timeSincePost < 60){
return 'Less than a minute ago';
} elseif($timeSincePost < 120) {
return '1 minute ago';
} elseif($timeSincePost < 3600) {
return strtok($timeSincePost/60, '.') . ' minutes ago';
} elseif($timeSincePost < 7200) {
return '1 hour ago';
} elseif($timeSincePost < 86400) {
return strtok($timeSincePost/3600, '.') . ' hours ago';
} elseif($timeSincePost < 172800) {
return '1 day ago';
} elseif($timeSincePost < 604800) {
return strtok($timeSincePost/86400, '.') . ' days ago';
} else {
return date("m/d/Y g:i A",strtotime($datetime));
}
}
function parsePost($text) { // just implementing this for fun, I don't actually plan on continuing Openverse development anymore. -PF2M 12/20/2017
    $post = htmlspecialchars($text);
    $dir = new DirectoryIterator('../assets/img/emotes');
    foreach ($dir as $fileinfo) {
        if (!$fileinfo->isDot()) {
            $post = str_replace(':' . $fileinfo->getBasename('.png') . ':', '<img src="/assets/img/emotes/' . $fileinfo->getFilename() . '" class="emote" style="height:1.5em;" alt=":' . $fileinfo->getBasename('.png') . ':">', $post);
        }
    }
    return $post;
}

/** Let's put a function here to insert and generate a token, which will be used in login.php.
* That way, if there's something terminally wrong, we just go to this file and either break this or fix it.
*/
function tokenGen($user) {
global $link;
// $user will be a user_pid.
// This is pseudo-random and generates a 32-character string that is very random. Good enough.
$random_thing = substr(preg_replace("/[\/=+]/","",base64_encode(openssl_random_pseudo_bytes(32))),0,32);
// Let's insert it.
$insert = mysqli_query($link, 'INSERT INTO login_tokens (token_id, token_for) VALUES("'.$random_thing.'", "'.mysqli_real_escape_string($link, $user).'")');
// That's it, but now we've got to put it into a cookie.

if(!isset($settings['max_token_life'])) { $max_token_life = 5; } 
else { $max_token_life = $settings['max_token_life']; }

setcookie('openverse-auth', $random_thing, (time() + strtotime($max_token_life . ' days')), '/');
// Done! If we log out, we've got to remove it in logout.php which is what I'll do next.
    }
    
$has_pjax = isset($_SERVER['HTTP_X_PJAX']);

$result = mysqli_query($link, 'SELECT * FROM `bans` WHERE ban_to = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']));
if(mysqli_num_rows($result) > 0){
$row = mysqli_fetch_assoc($result);
if($row['ban_length'] == 0 || time() < (strtotime($row['ban_date']) + ($row['ban_length'] * 86400))) {
require_once __DIR__ . '/htm.php';
$auth = true;
$title = 'Restricted';
if($row['ban_length'] == 0) {
    $ban_length = 'FOREVER, BITCH.';
} else {
    $ban_length = htmlspecialchars($row['ban_length']) . ' days';
}
openServerErr(403, "You have been restricted from interacting with Openverse.<br>Ban issued: " . displayTime($row['ban_date']) . " (this is probably not accurate)<br>Ban length: " . $ban_length . '<br>If you have an issue with this, <a href="https://twitter.com/openverse_admin">DM us on Twitter</a> or <a href="https://www.pf2m.com/contact/">email PF2M directly</a>.');
}
}

if(isset($_SESSION['user_pid'])) {
session_destroy();
}