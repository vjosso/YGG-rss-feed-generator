<?php

require_once('Ygg.php');

switch ($_GET['action']) {
    case 'download':
        if (isset($_GET['file'])) {
            download($_GET['file']);
        }
        break;

    default:
        echo 'Bad action';
        break;
}

// SAMPLE -- download torrent
function download($file)
{
    $ygg = new Ygg();
    if ($ygg->download($file)) {
        $location = './dl/download.torrent';
        $re = '/id=(\d{5,})/';
        $str = $_GET['file'];
        preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
        
        header("Content-Disposition: attachment; filename=".$matches[0][1].".torrent");
		header("Content-Type: application/force-download");
		header("Content-Length: " . filesize($location));
		header('Content-Transfer-Encoding: binary');
		header("Connection: close");
		readfile($location);
    }
}

 ?>
