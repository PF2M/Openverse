<?php
$title = 'Create Community';
require_once '../../inc/connect.php';
require_once '../../inc/htm.php'; openHead();

if($signed_in&&$_SESSION['user_rank']>2){
if($_SERVER['REQUEST_METHOD'] != 'POST') {
    //the form hasn't been posted yet, display it
    echo "<center><form method='post' action=''><br>
        Community name: <input type='text' name='name' /><br>
        Community title: <input type='text' name='title' /><br>
        Community description: <textarea name='description' /></textarea><br>
        Community banner URL: <input type='text' name='banner' /><br>
        Community icon URL: <input type='text' name='icon' /><br>
        Community type: <select name='type'><option value='1'>Main Community</option><option value='0'>Subcommunity</option></select><br>
        Community platform: <select name='platform'><option value='0'>3DS</option><option value='1'>Wii U</option><option value='2'>get yourself a girl who can do both</option><option value='3'>N/yA</option></select><br>
        <input type='submit' value='Create Community' />
     </form></center>";
} else {
    //the form has been posted, so save it
    $sql = "INSERT INTO communities(community_name,community_title,community_description,community_banner,community_icon, community_type,community_platform) VALUES('" . mysqli_real_escape_string($link,$_POST['name']) . "'," . mysqli_real_escape_string($link,$_POST['title']) . ",'" . mysqli_real_escape_string($link,$_POST['description']) . "','" . mysqli_real_escape_string($link,$_POST['banner']) . "','" . mysqli_real_escape_string($link,$_POST['icon']) . "','" . mysqli_real_escape_string($link,$_POST['type']) . "','" .
mysqli_real_escape_string($link,$_POST['platform']) . "')";
    $result = mysqli_query($link, $sql);
    if(!$result)
    {
        //something went wrong, display the error
        echo 'Error: ' . mysqli_errno($link);
    }
    else
    {
        echo 'New community successfully added.';
    }
}
} else {
openServerErr(403);
}
openFoot();