<?php

/**
 * POC permettant de parcourir le site www.yggtorrent.com et d'en extraire les torrents recherchés
 * User: Anthony Saugrain
 * Date: 26/07/2016
 * Time: 18:08
 */

require_once('lib/simple_html_dom.php');

class Ygg
{
    private $baseUrl;
    private $html;
    private $login;
    private $password;

    /**
     * Ygg constructor.
     * @param string $search
     * @param int $pagination
     */
    public function __construct()
    {
        $configs = include('config.php');
        if($configs['sync']) {
          $this->baseUrl = $this->call('basic', 'https://raw.githubusercontent.com/Guisch/YGG-rss-feed-downloader/master/domain');
        } else {
          $this->baseUrl = fopen('domain', 'r');
        }

        $this->login = $configs['user'];
        $this->password = $configs['pass'];
    }

    /**
     * Login and store cookie
     */
     public function login()
     {
         try {
             $logincall = $this->call('login', $this->baseUrl . '/user/login');
             if ($logincall == "") {
                 $page = $this->call('basic', $this->baseUrl);
                 if ($page !== false) {
                     $this->html = $this->open($page);
                     if ($this->findLink('/user/account')) {
                         return true;
                     } else {
                         throw new Exception('Unable to login');
                     }
                 }
             }
         } catch (Exception $e) {
             throw new Exception($e->getMessage());
         }
     }

    /**
     * Generic cURL call
     * @param $type
     * @param $url
     * @return mixed
     * @throws Exception
     */
    private function call($type, $url)
    {
        try {
            // create curl resource
            $ch = curl_init();

            if ($type == 'login') {
                $datas = "id=" . urlencode($this->login) . "&pass=" . urlencode($this->password);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
            }

            // extra headers
            $headers[] = "Accept: */*";
            $headers[] = "Connection: Keep-Alive";

            $cookie_file_path = "./tmp/cookies.txt";

            //return the transfer as a string
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);


            // $output contains the output string
            $output = curl_exec($ch);

            if ($type == 'download') {
                $destination = "./dl/download.torrent";
                $file = fopen($destination, "w+");
                fputs($file, $output);
                fclose($file);
            }

            // close curl resource to free up system resources
            curl_close($ch);

            return $output;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Open a html file with simple html dom
     * @param $file
     * @return simple_html_dom
     * @throws Exception
     */
    private function open($file)
    {
        try {
            $html = new simple_html_dom();
            $html->load($file);

            return $html;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Find a particular link
     * @param $term
     * @return bool
     * @throws Exception
     */
    private function findLink($term)
    {
        try {
            $links = $this->html->find('a');
            foreach ($links as $link) {
                if (strpos($link->href, $term) !== false) {
                    return true;
                }
            }
            return false;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Download given torrent
     * @param $url
     * @return bool
     * @throws Exception
     */
    public function download($idt)
    {
        try {
            if ($this->call('download', $this->baseUrl . '/engine/download_torrent?id=' . $idt) !== false) {
                return true;
            }

            return false;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
