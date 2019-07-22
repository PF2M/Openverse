<?php

function imgurUpload($data) {
	global $open_imgur_clientid;
	if(isset($open_imgur_clientid)) {
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $open_imgur_clientid));
curl_setopt($ch, CURLOPT_POSTFIELDS, array('image' => $data));
$response = curl_exec($ch);
if(curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200) {
//what the fuck you don't need to escape this
return str_replace('http://', 'https://', json_decode($response)->data->link);
} else {
return false;
    }
} else {
header('Content-Type: application/json');
exit('{"success":0,"errors":[{"message":"The server isn\'t configured for Imgur.","error_code":6661337}],"code":400}');
}
}

function Drawing($data) {
if($data == 'iVBORw0KGgoAAAANSUhEUgAAAUAAAAB4CAYAAACDziveAAADhUlEQVR4Xu3UAREAAAgCMelf2iA/GzA8do4AAQJRgUVzi02AAIEzgJ6AAIGsgAHMVi84AQIG0A8QIJAVMIDZ6gUnQMAA+gECBLICBjBbveAECBhAP0CAQFbAAGarF5wAAQPoBwgQyAoYwGz1ghMgYAD9AAECWQEDmK1ecAIEDKAfIEAgK2AAs9ULToCAAfQDBAhkBQxgtnrBCRAwgH6AAIGsgAHMVi84AQIG0A8QIJAVMIDZ6gUnQMAA+gECBLICBjBbveAECBhAP0CAQFbAAGarF5wAAQPoBwgQyAoYwGz1ghMgYAD9AAECWQEDmK1ecAIEDKAfIEAgK2AAs9ULToCAAfQDBAhkBQxgtnrBCRAwgH6AAIGsgAHMVi84AQIG0A8QIJAVMIDZ6gUnQMAA+gECBLICBjBbveAECBhAP0CAQFbAAGarF5wAAQPoBwgQyAoYwGz1ghMgYAD9AAECWQEDmK1ecAIEDKAfIEAgK2AAs9ULToCAAfQDBAhkBQxgtnrBCRAwgH6AAIGsgAHMVi84AQIG0A8QIJAVMIDZ6gUnQMAA+gECBLICBjBbveAECBhAP0CAQFbAAGarF5wAAQPoBwgQyAoYwGz1ghMgYAD9AAECWQEDmK1ecAIEDKAfIEAgK2AAs9ULToCAAfQDBAhkBQxgtnrBCRAwgH6AAIGsgAHMVi84AQIG0A8QIJAVMIDZ6gUnQMAA+gECBLICBjBbveAECBhAP0CAQFbAAGarF5wAAQPoBwgQyAoYwGz1ghMgYAD9AAECWQEDmK1ecAIEDKAfIEAgK2AAs9ULToCAAfQDBAhkBQxgtnrBCRAwgH6AAIGsgAHMVi84AQIG0A8QIJAVMIDZ6gUnQMAA+gECBLICBjBbveAECBhAP0CAQFbAAGarF5wAAQPoBwgQyAoYwGz1ghMgYAD9AAECWQEDmK1ecAIEDKAfIEAgK2AAs9ULToCAAfQDBAhkBQxgtnrBCRAwgH6AAIGsgAHMVi84AQIG0A8QIJAVMIDZ6gUnQMAA+gECBLICBjBbveAECBhAP0CAQFbAAGarF5wAAQPoBwgQyAoYwGz1ghMgYAD9AAECWQEDmK1ecAIEDKAfIEAgK2AAs9ULToCAAfQDBAhkBQxgtnrBCRAwgH6AAIGsgAHMVi84AQIG0A8QIJAVMIDZ6gUnQMAA+gECBLICBjBbveAECDxJRAB5u6RDsAAAAABJRU5ErkJggg==') {
return false;
}
$image = base64_decode($data);
if(!$image) { return false; }
$verify = @getimagesizefromstring($image);
if(!$verify || $verify[0] != 320 || $verify[1] != 120) { return false; }
/*
$im = new Imagick();
$im->readImageBlob($image);
$im->setImageBackgroundColor('white');
$im->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
$im->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
$im->resizeImage(320, 120, Imagick::FILTER_UNDEFINED, 2, false);
$im->posterizeImage(2, false);
$im->setImageDepth(2);
$im->setImageFormat('bmp');
$im->setImageDepth(1);
return $im->getImageBlob();
*/
return $image;
}