<?php

require_once('Ygg.php');

header("Content-Type: application/rss+xml; charset=UTF-8");

$rssFeed = '<?xml version="1.0" encoding="UTF-8"?>';
$rssFeed .= '<rss version="2.0">';
$rssFeed .= '<channel>';
$rssFeed .= '<title>YGGtorrent - Guisch RSS generator</title>';
$rssFeed .= '<link>https://yggtorrent.com</link>';
$rssFeed .= '<description>Latest Torrents on YGG</description>';
$rssFeed .= '<language>en-US</language>';
$rssFeed .= '<image>';
$rssFeed .= '<title>YGGtorrent - Guisch RSS generator</title>';
$rssFeed .= '<url>https://yggtorrent.com</url>';
$rssFeed .= '<link>https://yggtorrent.com</link>';
$rssFeed .= '<width>100</width>';
$rssFeed .= '<height>30</height>';
$rssFeed .= '<description>YGGtorrent - Guisch RSS generator</description>';
$rssFeed .= '</image>';
$rssFeed .= '<copyright>No copyright</copyright>';
$rssFeed .= '<webMaster>no@mail.com</webMaster>';
$rssFeed .= '<lastBuildDate>' . date('D, d M Y H:i:s O') . '</lastBuildDate>';
$rssFeed .= '<ttl>20</ttl>';
$rssFeed .= '<generator>YGGtorrent - Guisch RSS generator</generator>';



$ygg = new Ygg();
if (isset($_GET['category'])) {
    $subcategory = isset($_GET['subcategory']) ? $_GET['subcategory'] : NULL;
    $category_id = $ygg::getCategoryId($_GET['category'], $subcategory);
    $ygg->searchCategory($category_id);

    $parent_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);

    foreach ($ygg->getTorrents() as $torrent) {
        $rssFeed .= '<item>';
        $rssFeed .= '<title>' . $torrent['name'] .'</title>';
        $rssFeed .= '<description><![CDATA[ ';
        $rssFeed .= 'Link: <a href="' . $torrent['thref'] . '">' . $torrent['thref'] . '</a><br />';
        $rssFeed .= 'Author: ' . $torrent['author'] . '<br />';
        $rssFeed .= 'Size: ' . $torrent['size'] . '<br />';
        $rssFeed .= 'Date: ' . $torrent['date'] . '<br />';
        $rssFeed .= 'Seeds: ' . $torrent['seeds'] . '<br />';
        $rssFeed .= 'Leechs: ' . $torrent['leechs'];
        $rssFeed .= ' ]]></description>';
        $rssFeed .= '<link>'. $parent_url . '/dl.php?action=download&idt=' . $torrent['idt'] . '</link>';
        $rssFeed .= '<author>'.$torrent['author'].'</author>';
        $rssFeed .= '<category>' . $_GET['category'] . ' - ' . $subcategory . '</category>';
        $rssFeed .= '<pubDate>' . date('Y-m-d H:i:s') . '</pubDate>';
        $rssFeed .= '</item>';
    }
} else {
    echo 'Unable to find category';
}

$rssFeed .= '</channel>';
$rssFeed .= '</rss>';
echo $rssFeed;
