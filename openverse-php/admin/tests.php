<?php
require_once '../../inc/connect.php';
require_once '../../inc/htm.php';

	if(isset($_GET['soap'])) {
			if($_GET['soap'] == 'e') {
				if(empty($_SESSION['err'])) {
				$_SESSION['err'] == true;
				echo 1;
				} else {
				$_SESSION['err'] == false;
				echo 0;
				}
			}
		if(isset($_POST['bcrypt'])) {
		header('Content-type: text/plain');
		echo $_POST['bcrypt']. '//' . password_hash($_POST['bcrypt'], PASSWORD_BCRYPT);
		}
		exit();
	}
if(!$signed_in || $_SESSION['user_rank'] < 5) {
// this isn't good enough 
// openServerErr(403, 'Only developers can debug.');
// this is more like it
// well, we need to make sure Discord doesn't directly display the video
	if(strpos('Discord', $_SERVER['HTTP_USER_AGENT'])) {
	http_response_code(404);
	exit();
	}
header('Location: https://www.youtube.com/watch?v=dQw4w9WgXcQ', true, 302);
}
$title = 'Debug';
openHead(); ?>
<div class="main-column">
	<div class="post-list-outline">
		<h2 class="label">Debug</h2>
		<div class="setting-form">
			<ul class="settings-list">
				<li class="scroll">   
					<h1>Show server errors</h1><br>
					<button onclick="$.ajax({url: '/debug?soap=e', success: function(c) { alert(c ? 'On' : 'Off'); }});">Toggle (not implemented?)</button>
				</li>
				<li class="scroll">   
					<h1>Progress bar</h1><br>
					<button onclick='Pace.restart();'>Start</button> 
					<button onclick='Pace.stop();'>Stop</button>
				</li>
				<li class="scroll">   
					<h1>Bcrypt</h1><br>
					<input type="text" name="bcrypt-query">
					<button onclick="var a = $('input[type=text][name=bcrypt-query]');
					if(a !== undefined && a != '') {
					$.ajax({url: '/debug?soap', type: 'POST', data: 'bcrypt=' + a.val(),
						success: function(b) {
						prompt('Did &quot;'+b.split('//')[0]+'&quot;', b.split('//')[1]);
						}
					});
					}">Do it</button> 
				</li>
			</ul>
		</div>
	</div>
</div>
<?php openFoot();