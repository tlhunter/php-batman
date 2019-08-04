<?php
include('clear.php'); // Self-cleaning with every generate

if ($_POST['comic'] == 1) {
	define('SOURCE_IMAGE', "./source/slap.jpg");
	define('FONT_FILE', "./comic.ttf");
	define('FONT_SIZE', 8);
	define('LINE_HEIGHT', 14);
	define('ROBIN_X', 80);
	define('ROBIN_Y', 14);
	define('ROBIN_WIDTH', 150);
	define('BATMAN_X', 240);
	define('BATMAN_Y', 14);
	define('BATMAN_WIDTH', 150);
} else if ($_POST['comic'] == 2) {
	define('SOURCE_IMAGE', "./source/hospital.jpg");
	define('FONT_FILE', "./comic.ttf");
	define('FONT_SIZE', 9);
	define('LINE_HEIGHT', 16);
	define('ROBIN_X', 265);
	define('ROBIN_Y', 40);
	define('ROBIN_WIDTH', 130);
	define('BATMAN_X', 110);
	define('BATMAN_Y', 30);
	define('BATMAN_WIDTH', 180);
} else if ($_POST['comic'] == 3) {
	define('SOURCE_IMAGE', "./source/sauna.jpg");
	define('FONT_FILE', "./comic.ttf");
	define('FONT_SIZE', 8);
	define('LINE_HEIGHT', 14);
	define('ROBIN_X', 235);
	define('ROBIN_Y', 30);
	define('ROBIN_WIDTH', 130);
	define('BATMAN_X', 70);
	define('BATMAN_Y', 50);
	define('BATMAN_WIDTH', 140);
} else {
	die("If you look at numbers on my face you won't find 13 anyplace.");
}
$image = imagecreatefromjpeg(SOURCE_IMAGE);
$black = imagecolorallocate($image, 0, 0, 0);
if (empty($_POST['robin']) || empty($_POST['batman'])) {
	die("Tear 1 off and scatch my head, what once was red is black instead.");
}
$robin = formatText($_POST['robin'], ROBIN_WIDTH);
$batman = formatText($_POST['batman'], BATMAN_WIDTH);

if (sizeof($robin) <= 2) {
	array_unshift($robin, '');
}

$i = 0;
foreach($robin AS $r_line) {
	imagettftextalign($image, FONT_SIZE, 0, ROBIN_X, ROBIN_Y + $i * LINE_HEIGHT, $black, FONT_FILE, $r_line, 'C');
	$i++;
}

if (sizeof($batman) <= 2) {
	array_unshift($batman, '');
}

$i = 0;
foreach($batman AS $b_line) {
	imagettftextalign($image, FONT_SIZE, 0, BATMAN_X, BATMAN_Y + $i * LINE_HEIGHT, $black, FONT_FILE, $b_line, 'C');
	$i++;
}

$filename = 'gen/' . date('YmdHis') . '_' . uniqid("") . '.jpg';
imagejpeg($image, $filename, 85);
header("Location: $filename");



function imagettftextalign($image, $size, $angle, $x, $y, $color, $font, $text, $alignment='L') {
   $bbox = imagettfbbox ($size, $angle, $font, $text);
   $textWidth = $bbox[2] - $bbox[0];
   switch ($alignment) {
       case "R":
           $x -= $textWidth;
           break;
       case "C":
           $x -= $textWidth / 2;
           break;
   }

   imagettftext ($image, $size, $angle, $x, $y, $color, $font, $text);

}

function formatText($text, $width) {
	$text = substr($text, 0, 80);
	$text = strtoupper($text);
	$text = stripslashes($text);
	$text = makeTextBlock($text, FONT_FILE, FONT_SIZE, $width);
	return $text;
}

function makeTextBlock($text, $fontfile, $fontsize, $width) {
	$words = explode(' ', $text);
	$lines = array($words[0]);
	$currentLine = 0;
	for ($i = 1; $i < count($words); $i++) {
		$lineSize = imagettfbbox($fontsize, 0, $fontfile, $lines[$currentLine] . ' ' . $words[$i]);
		if ($lineSize[2] - $lineSize[0] < $width) {
			$lines[$currentLine] .= ' ' . $words[$i];
		} else {
			$currentLine++;
			$lines[$currentLine] = $words[$i];
		}
	}

	return $lines;
}
