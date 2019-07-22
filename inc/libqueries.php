<?php
// OpenverseLibQueries: Does regulated queries.
/*
communityGetEyecatchPosts(); - Gets eyecatch posts in an associative array.
communityGetMetaData(array that has a community element); - Gets metadata for a community such as platform type, community name, etc etc.
*/

function communityGetEyecatchPosts() {
global $settings;
global $link;
$result = prepared('SELECT * FROM posts d1 LEFT JOIN users ON user_pid = post_by LEFT JOIN communities ON community_id = post_community LEFT JOIN titles ON title_id = community_title WHERE LENGTH(post_screenshot) AND post_community IN (?) ORDER BY post_id DESC LIMIT 0,10', [join(', ', $settings['featured_communities'])]);
if(mysqli_error($link)) {
    error_log('Failed to grab featured posts at '.time(). '.<br>' . mysqli_error($link) . '<br>' . join(', ', $settings['featured_communities']));
	return false;
	}
		$arr = array();
		while($row = mysqli_fetch_assoc($result)) {
		$arr[] = $row;
		}
		return $arr;
}

function userGetActivityFeed($pid, $limit = 50) {
global $link;
global $offset;
$query = prepared('SELECT * FROM posts LEFT JOIN users ON user_pid = post_by LEFT JOIN communities ON community_id = post_community WHERE posts.post_by IN (SELECT user_pid FROM users WHERE user_pid IN (SELECT follow_to FROM follows WHERE follow_by = ? union select ?)) AND posts.post_status = 0 ORDER BY posts.post_date DESC LIMIT ? OFFSET ?',
[$pid, $pid, $limit, $offset]);
    if($query&&$query->num_rows != 0) {
    return mysqli_fetch_all($query, MYSQLI_ASSOC);
    } else {
    return false;
    }
}

function titleGetMetaData($c) {
		/*
		echo $avatar . '" class="icon community-eyecatch-usericon"></span><p class="community-eyecatch-balloon"><span>' . htmlspecialchars($row['post_content']) . '</span></p></a><a href="/titles/' . htmlspecialchars($row['community_title']) . '/' . htmlspecialchars($row['community_id']) . '" class="community-eyecatch-info"><img src="' . htmlspecialchars($row['community_icon']) . '" width="40" height="40" class="community-eyecatch-infoicon"><h4 class="community-game-title" data-index="1"> ' . htmlspecialchars($row['community_name']) . '</h4><p class="community-game-device">';
		if($row['title_platform']<3) {
		echo '<span class="platform-tag"><img src="/assets/img/platform-tag-'; 
		if($row['title_platform']==0) {
		echo '3ds';
		} elseif($row['title_platform']==1){
		echo 'wiiu';
		} elseif($row['title_platform']==2){
		echo 'wiiu-3ds';
		}
		echo '.png"></span>';
		}
		echo '<span class="text">';
		if($row['title_type']==0){
		if($row['title_platform']==0){
		echo '3DS Games';
		} elseif($row['title_platform']==1){
		echo 'Wii U Games';
		} elseif($row['title_platform']==2){
		echo 'Wii U Games·3DS Games';
		} else {
		echo 'General Community';
		}
		} elseif($row['title_type']==2){
		echo 'Special Community';
		} else {
		echo 'General Community';
		}
		echo '</span></p></a></div>';
		}
*/
	$arr = array(
	'community_id' => +$c['community_id'],
	'community_name' => htmlspecialchars($c['community_name']),
	'community_icon' => htmlspecialchars($c['community_icon']),
	);
	return $arr;
}