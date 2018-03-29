<?php

require_once('Ygg.php');

switch ($_GET['action']) {
    case 'download':
        if (isset($_GET['idt'])) {
            download($_GET['idt']);
        }
        break;

    default:
        echo 'Bad action';
        break;
}

// SAMPLE -- download torrent
function download($idt) {
    $ygg = new Ygg();
    if ($ygg->login()) {
    	if ($ygg->download($idt)) {
        	$location = './dl/download.torrent';
        
        	header("Content-Disposition: attachment; filename=" . $idt . ".torrent");
		header("Content-Type: application/force-download");
		header("Content-Length: " . filesize($location));
		header('Content-Transfer-Encoding: binary');
		header("Connection: close");
		readfile($location);
    
	}
    }
}

 ?>
