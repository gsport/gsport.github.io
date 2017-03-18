<?php
// Here you have to set your security key (if is enableSecurityKey false, empty this value):
$securityKey = "";

// Choose if you want to create PNG banner or TXT file, use "banner" or "file"
$create = "banner";

// Then edit the name for .TXT file with server status:
$fileName = "status.txt";

// Don't edit the code below if you don't understand PHP programming!
function ping($host, $port) {
$tB = microtime(true);
$fp = fsockopen($host, $port, $errno, $errstr, 30);
if (!$fp) return false;
$tA = microtime(true);
return round((($tA - $tB) * 1000), 0);
}
$key = $_GET["sec"]; 
if($key == $securityKey) {
$status = $_GET["status"];
$name = $_GET["name"]; 
$motd = $_GET["motd"];
$ip = $_GET["ip"];   
$port = $_GET["port"];
$plsnum = $_GET["plsnum"];
$maxnum = $_GET["maxnum"];
if($create == "file") {
$players = str_replace("-", ", ", substr_replace($_GET["players"],"",-1));
$fh = fopen($fileName, 'w') or die("Can't open file!");
$string = "Status: ".$status."\nServer name: ".$name."\nIP adress: ".$ip.":".$port."\nNumber of online players: ".$plsnum."/".$maxnum."\nList of online players: ".$players."\nMOTD: ".$motd."\nPing: ".ping($ip, $port)."ms";
fwrite($fh, $string);
fclose($fh);
} elseif($create == "banner") {
if($status == "Offline") { $ping = -1; } else { $ping = ping($ip, $port); }
$im = imagecreatefromjpeg("source/bg.jpg");
$white = imagecolorallocate($im, 255, 255, 255);
$whiteshadow = imagecolorallocate($im, 63, 63, 63);
$redshadow = imagecolorallocate($im, 42, 0, 0);
$red = imagecolorallocate($im, 170, 0, 0);
$grey = imagecolorallocate($im, 170, 170, 170);
$greyshadow = imagecolorallocate($im, 42, 42, 42);
$darkgrey = imagecolorallocate($im, 48, 48, 48);
$font = "source/font.ttf";
$firstline = $name;
if(-1 < $ping && $ping < 16) { $firstlineright = imagecreatefrompng("source/ping5.png"); }
elseif(15 < $ping && $ping < 31) { $firstlineright = imagecreatefrompng("source/ping4.png"); }
elseif(30 < $ping && $ping < 61) { $firstlineright = imagecreatefrompng("source/ping3.png"); }
elseif(60 < $ping && $ping < 101) { $firstlineright = imagecreatefrompng("source/ping2.png"); }
elseif(100 < $ping) { $firstlineright = imagecreatefrompng("source/ping1.png"); }
else { $firstlineright = imagecreatefrompng("source/ping0.png"); }
imagealphablending($firstlineright, 1);
$secondline = $motd;
$secondlineright = $plsnum."/".$maxnum;
if($port == "25565") { $thirdline = $ip; } else { $thirdline = $ip.":".$port; }
imagettftext($im, 18, 0, 12, 30, $whiteshadow, $font, $firstline);
imagettftext($im, 18, 0, 9, 27, $white, $font, $firstline);
imagecopy($im, $firstlineright, 618, 6, 0, 0, 30, 21);
if($ping != -1) {
imagettftext($im, 18, 0, 12, 63, $greyshadow, $font, $secondline);
imagettftext($im, 18, 0, 9, 60, $grey, $font, $secondline);
$dimensions = imagettfbbox(18, 0, $font, $secondlineright);
$textWidth = abs($dimensions[4] - $dimensions[0]);
$x = imagesx($im) - $textWidth;
imagettftext($im, 18, 0, $x-60, 63, $greyshadow, $font, $secondlineright);
imagettftext($im, 18, 0, $x-63, 60, $grey, $font, $secondlineright);
} else {
imagettftext($im, 18, 0, 12, 63, $redshadow, $font, "Can't reach server");
imagettftext($im, 18, 0, 9, 60, $red, $font, "Can't reach server");
}
imagettftext($im, 18, 0, 9, 93, $darkgrey, $font, $thirdline);
imagepng($im, "banner.png");
imagedestroy($firstlineright);
imagedestroy($im);
}
}
?>
