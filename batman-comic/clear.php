<?php
define('DEL_DIR', './gen/');

$files = array();
$yesterday = strtotime('48 hours ago');

if ($handle = opendir(DEL_DIR)) {
	clearstatcache();
	$i = 0;
	while (false !== ($file = readdir($handle))) {
   		if ($file != "." && $file != "..") {
   			$files[$i]['filename'] = DEL_DIR . $file;
			$files[$i]['modified'] = filemtime(DEL_DIR.$file);
			$i++;
   		}
	}
  	closedir($handle);
}

#asort($files);

foreach($files as $i => $file) {
	if($file['modified'] < $yesterday) {
		unlink($file['filename']);
	}
}