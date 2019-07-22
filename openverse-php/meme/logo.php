<?php
require_once '../../inc/connect.php';
require_once '../../inc/htm.php';
if($_SERVER['REQUEST_METHOD'] != 'POST') {
openHead();
?><div class="main-column">
	<div class="post-list-outline">
		<h2 class="label">Openverse Logo Generator</h2>
		<p style="padding-top:10px;padding-left:10px;padding-right:20px">This will generate a nice custom Openverse logo to use in your very own memes. The font used in the Openverse logo is <a href="http://www.dafont.com/comfortaa.font">Comfortaa Bold.</a><br>(for now this uses <a href="https://ariankordi.net/openverse_logo_gen">Arian's server</a> for processing)</p>
<div class="setting-form">
			<ul class="settings-list">
				<li class="scroll">   
					<form method="post" action="https://ariankordi.net/openverse_logo_gen">
					<input type="checkbox" name="b" value="0" onchange="if(this.checked){this.value=1}else{this.value=0}">Sad face<br>
					<h1>Input</h1>
					<input type="text" maxlength="50" name="a"><br>
                    <button type="submit">Submit</button>
					</form>
					 
				</li>
			</ul>
		</div>
	</div>
</div><?php
openFoot();
} else {
    if(empty($_POST['openlogo-query'])) {
    openServerErr(400, 'The Openverse logo query was empty.');
    }
    if(mb_strlen($_POST['openlogo-query']) > 50) {
    openServerErr(400, 'Did you really think you could spam my server that easily?');
    }
// do magick of image
// Yes, I know this is annoying and confusing, but just try to deal with it for now.
header('Content-Type: text/plain; charset=UTF-8');
ini_set('display_errors', true);
ini_set('html_errors', false);

$txt = $_POST['openlogo-query'];

$image = new Imagick();
$d = new ImagickDraw();
$color = new ImagickPixel('#0280ff');
$background = new ImagickPixel('none'); // Transparent
$openman = new Imagick(__DIR__ . '/open-man.png');

// Properties
$d->setFont(__DIR__ . '/open-font.ttf');
$d->setFontSize(48);
$d->setFontWeight(900);
$d->setFillColor($color);
$d->setTextKerning(-1.0);
$d->setTextAntialias(true);
$d->setTextEncoding('UTF-8');
$metrics = $image->queryFontMetrics($d, $txt);
$d->annotation(69, $metrics['ascender']+10, $txt);
$image->newImage($metrics['textWidth']+71, $metrics['textHeight']+10, $background);
$image->setImageFormat('png');
$image->compositeImage($openman, Imagick::COMPOSITE_MATHEMATICS, 0, 0);
$image->drawImage($d);

// Save image?
header('Content-Type: image/png');
echo $image->getImageBlob();

$d->clear();
$d->destroy();
$image->clear();
$image->destroy();
}