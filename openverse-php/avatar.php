<?php
if(preg_match('/^[a-z0-9]{11,13}$/', $avatar)) {
if($feeling_id == 1){
$feeling_id = 'happy';
} elseif($feeling_id == 2){
$feeling_id = 'like';
} elseif($feeling_id == 3){
$feeling_id = 'surprised';
} elseif($feeling_id == 4){
$feeling_id = 'frustrated';
} elseif($feeling_id == 5){
$feeling_id = 'puzzled';
} else {
$feeling_id = 'normal';
}
if(isset($feeling_id) && $feeling_id != false){
$avatar = 'https://mii-secure.cdn.nintendo.net/' . htmlspecialchars($avatar) . '_' . $feeling_id . '_face.png';
} else {
$avatar = 'https://mii-secure.cdn.nintendo.net/' . htmlspecialchars($avatar) . '_normal_face.png';
}} elseif (strlen($avatar)==0) {
$avatar = '/assets/img/anonymous-mii.png';
} else {
$avatar = htmlspecialchars($avatar);
}