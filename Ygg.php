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
    const BASE_URL = "https://ww1.yggtorrent.com";
    const CATEGORY_FILMVIDEO = 2145;
    const CATEGORY_FILMVIDEO_ANIMATION = 'filmvideo/2178';
    const CATEGORY_FILMVIDEO_ANIMATIONSERIE = 'filmvideo/2179';
    const CATEGORY_FILMVIDEO_CONCERT = 'filmvideo/2180';
    const CATEGORY_FILMVIDEO_DOCUMENTAIRE = 'filmvideo/2181';
    const CATEGORY_FILMVIDEO_EMISSIONTV = 'filmvideo/2182';
    const CATEGORY_FILMVIDEO_FILM = 'filmvideo/2183';
    const CATEGORY_FILMVIDEO_SERIETV = 'filmvideo/2184';
    const CATEGORY_FILMVIDEO_SPECTACLE = 'filmvideo/2185';
    const CATEGORY_FILMVIDEO_SPORT = 'filmvideo/2186';
    const CATEGORY_FILMVIDEO_VIDEOCLIP = 'filmvideo/2187';

    const CATEGORY_AUDIO = 2139;
    const CATEGORY_AUDIO_KARAOKE = 'audio/2147';
    const CATEGORY_AUDIO_MUSIQUE = 'audio/2148';
    const CATEGORY_AUDIO_PODCASTRADIO = 'audio/2150';
    const CATEGORY_AUDIO_SAMPLES = 'audio/2149';

    const CATEGORY_APPLICATION = 2144;
    const CATEGORY_APPLICATION_AUTRE = 'application/2177';
    const CATEGORY_APPLICATION_FORMATION = 'application/2176';
    const CATEGORY_APPLICATION_LINUX = 'application/2171';
    const CATEGORY_APPLICATION_MACOS = 'application/2172';
    const CATEGORY_APPLICATION_SMARTPHONE = 'application/2174';
    const CATEGORY_APPLICATION_TABLETTE = 'application/2175';
    const CATEGORY_APPLICATION_WINDOWS = 'application/2173';

    const CATEGORY_JEUVIDEO = 2142;
    const CATEGORY_JEUVIDEO_AUTRE = 'jeu-video/2167';
    const CATEGORY_JEUVIDEO_LINUX = 'jeu-video/2159';
    const CATEGORY_JEUVIDEO_MACOS = 'jeu-video/2160';
    const CATEGORY_JEUVIDEO_MICROSOFT = 'jeu-video/2162';
    const CATEGORY_JEUVIDEO_NINTENDO = 'jeu-video/2163';
    const CATEGORY_JEUVIDEO_SMARTPHONE = 'jeu-video/2165';
    const CATEGORY_JEUVIDEO_SONY = 'jeu-video/2164';
    const CATEGORY_JEUVIDEO_TABLETTE = 'jeu-video/2166';
    const CATEGORY_JEUVIDEO_WINDOWS = 'jeu-video/2161';

    const CATEGORY_EBOOK = 2140;
    const CATEGORY_EBOOK_AUDIO = 'ebook/2151';
    const CATEGORY_EBOOK_BDS = 'ebook/2152';
    const CATEGORY_EBOOK_COMICS = 'ebook/2153';
    const CATEGORY_EBOOK_LIVRES = 'ebook/2154';
    const CATEGORY_EBOOK_MANGAS = 'ebook/2155';
    const CATEGORY_EBOOK_PRESSE = 'ebook/2156';

    const CATEGORY_EMULATION = 2141;
    const CATEGORY_EMULATION_EMULATEURS = 'emulation/2157';
    const CATEGORY_EMULATION_ROMS = 'emulation/2158';

    const CATEGORY_GPS = 2143;
    const CATEGORY_GPS_APPLICATIONS = 'gps/2168';
    const CATEGORY_GPS_CARTES = 'gps/2169';
    const CATEGORY_GPS_DIVERS = 'gps/2170';

    const CATEGORY_XXX = 2188;
    const CATEGORY_XXX_FILMS = 'xxx/2189';
    const CATEGORY_XXX_HENTAI = 'xxx/2190';
    const CATEGORY_XXX_IMAGES = 'xxx/2191';

    private $torrents;
    private $html;
    private $search;
    private $pagination;
    private $login;
    private $password;
    private $order;

    /**
     * Ygg constructor.
     * @param string $search
     * @param int $pagination
     */
    public function __construct($search = '', $pagination = 1)
    {
        $this->torrents = array();
        $this->search = $search;
        $this->pagination = $pagination;
        $this->login = 'login';
        $this->password = 'pass';
        $this->order = 'publish_date';
    }

    /**
     * Get category id by category name
     * @param $category
     * @return bool|int
     */
    public static function getCategoryId($category, $subcategory)
    {
        switch ($category) {
            case 'filmvideo':
                switch ($subcategory) {
                    case 'animation':
                        $category_id = self::CATEGORY_FILMVIDEO_ANIMATION;
                        break;
                    case 'animationserie':
                        $category_id = self::CATEGORY_FILMVIDEO_ANIMATIONSERIE;
                        break;
                    case 'concert':
                        $category_id = self::CATEGORY_FILMVIDEO_CONCERT;
                        break;
                    case 'documentaire':
                        $category_id = self::CATEGORY_FILMVIDEO_DOCUMENTAIRE;
                        break;
                    case 'emissiontv':
                        $category_id = self::CATEGORY_FILMVIDEO_EMISSIONTV;
                        break;
                    case 'film':
                        $category_id = self::CATEGORY_FILMVIDEO_FILM;
                        break;
                    case 'serietv':
                        $category_id = self::CATEGORY_FILMVIDEO_SERIETV;
                        break;
                    case 'spectacle':
                        $category_id = self::CATEGORY_FILMVIDEO_SPECTACLE;
                        break;
                    case 'sport':
                        $category_id = self::CATEGORY_FILMVIDEO_SPORT;
                        break;
                    case 'videoclips':
                        $category_id = self::CATEGORY_FILMVIDEO_VIDEOCLIP;
                        break;
                    default:
                        $category_id = self::CATEGORY_FILMVIDEO;
                        break;
                }
                break;
            case 'audio':
                switch ($subcategory) {
                    case 'karaoke':
                        $category_id = self::CATEGORY_AUDIO_KARAOKE;
                        break;
                    case 'musique':
                        $category_id = self::CATEGORY_AUDIO_MUSIQUE;
                        break;
                    case 'podcastradio':
                        $category_id = self::CATEGORY_AUDIO_PODCASTRADIO;
                        break;
                    case 'samples':
                        $category_id = self::CATEGORY_AUDIO_SAMPLES;
                        break;
                    default:
                        $category_id = self::CATEGORY_AUDIO;
                        break;
                }
                break;
            case 'application':
                switch ($subcategory) {
                    case 'autre':
                        $category_id = self::CATEGORY_APPLICATION_AUTRE;
                        break;
                    case 'formation':
                        $category_id = self::CATEGORY_APPLICATION_FORMATION;
                        break;
                    case 'linux':
                        $category_id = self::CATEGORY_APPLICATION_LINUX;
                        break;
                    case 'macos':
                        $category_id = self::CATEGORY_APPLICATION_MACOS;
                        break;
                    case 'smartphone':
                        $category_id = self::CATEGORY_APPLICATION_SMARTPHONE;
                        break;
                    case 'tablette':
                        $category_id = self::CATEGORY_APPLICATION_TABLETTE;
                        break;
                    case 'windows':
                        $category_id = self::CATEGORY_APPLICATION_WINDOWS;
                        break;
                    default:
                        $category_id = self::CATEGORY_APPLICATION;
                        break;
                }
                break;
            case 'jeuvideo':
                switch ($subcategory) {
                    case 'autre':
                        $category_id = self::CATEGORY_JEUVIDEO_AUTRE;
                        break;
                    case 'linux':
                        $category_id = self::CATEGORY_JEUVIDEO_LINUX;
                        break;
                    case 'macos':
                        $category_id = self::CATEGORY_JEUVIDEO_MACOS;
                        break;
                    case 'microsoft':
                        $category_id = self::CATEGORY_JEUVIDEO_MICROSOFT;
                        break;
                    case 'nintendo':
                        $category_id = self::CATEGORY_JEUVIDEO_NINTENDO;
                        break;
                    case 'smartphone':
                        $category_id = self::CATEGORY_JEUVIDEO_SMARTPHONE;
                        break;
                    case 'sony':
                        $category_id = self::CATEGORY_JEUVIDEO_SONY;
                        break;
                    case 'tablette':
                        $category_id = self::CATEGORY_JEUVIDEO_TABLETTE;
                        break;
                    case 'windows':
                        $category_id = self::CATEGORY_JEUVIDEO_WINDOWS;
                        break;
                    default:
                        $category_id = self::CATEGORY_JEUVIDEO;
                        break;
                }
                break;
            case 'ebook':
                switch ($subcategory) {
                    case 'audio':
                        $category_id = self::CATEGORY_EBOOK_AUDIO;
                        break;
                    case 'bds':
                        $category_id = self::CATEGORY_EBOOK_BDS;
                        break;
                    case 'comics':
                        $category_id = self::CATEGORY_EBOOK_COMICS;
                        break;
                    case 'livres':
                        $category_id = self::CATEGORY_EBOOK_LIVRES;
                        break;
                    case 'mangas':
                        $category_id = self::CATEGORY_EBOOK_MANGAS;
                        break;
                    case 'presse':
                        $category_id = self::CATEGORY_EBOOK_PRESSE;
                        break;
                    default:
                        $category_id = self::CATEGORY_EBOOK;
                        break;
                }
                break;
            case 'emulation':
                switch ($subcategory) {
                    case 'emulateurs':
                        $category_id = self::CATEGORY_EMULATION_EMULATEURS;
                        break;
                    case 'roms':
                        $category_id = self::CATEGORY_EMULATION_ROMS;
                        break;
                    default:
                        $category_id = self::CATEGORY_EMULATION;
                        break;
                }
                break;
            case 'gps':
                switch ($subcategory) {
                    case 'applications':
                        $category_id = self::CATEGORY_GPS_APPLICATIONS;
                        break;
                    case 'cartes':
                        $category_id = self::CATEGORY_GPS_CARTES;
                        break;
                    case 'divers':
                        $category_id = self::CATEGORY_GPS_DIVERS;
                        break;
                    default:
                        $category_id = self::CATEGORY_GPS;
                        break;
                }
                break;
            case 'xxx':
                switch ($subcategory) {
                    case 'films':
                        $category_id = self::CATEGORY_XXX_FILMS;
                        break;
                    case 'hentai':
                        $category_id = self::CATEGORY_XXX_HENTAI;
                        break;
                    case 'images':
                        $category_id = self::CATEGORY_XXX_IMAGES;
                        break;
                    default:
                        $category_id = self::CATEGORY_XXX;
                        break;
                }
                break;
            default:
                $category_id = false;
                break;
        }

        return $category_id;
    }

    /**
     * @return array torrents
     */
    public function getTorrents()
    {
        return $this->torrents;
    }

    /**
     * Login and store cookie
     */
    public function login()
    {
        try {
            if ($this->call('login', '/user/login') == "") {
                if (($page = $this->call('basic', '')) !== false) {
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
     * @param $path
     * @return mixed
     * @throws Exception
     */
    private function call($type, $path)
    {
        try {
            // create curl resource
            $ch = curl_init();

            $url = self::BASE_URL . $path;
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
     * Loop and find torrent in given url
     * @param $url
     */
    private function loopForTorrent($url)
    {
        for ($i = 0; $i < $this->pagination; $i++) {
            $page = $this->call('basic', $url . '&page=' . $i);
            $this->html = $this->open($page);
            $this->extractTorrents();
        }
    }


    /**
     * Extract founded torrents
     * @return bool
     * @throws Exception
     */
    private function extractTorrents()
    {
        try {
            $lines = $this->html->find('.content-box-large table tbody tr');
            foreach ($lines as $key => $line) {
                // Do not handle first line (header)
                if ($key > 0) {
                    // Extract torrent link & name
                    $links = $line->children(0)->find('a');
                    foreach ($links as $key2 => $link) {
                        if ($key2 == 0) {
                            $str = $link->innertext;
                            $pos = strpos($str, '<span class="__cf_email__"');

                            if ($pos !==false) {
                                $str = substr($str, 0, $pos);
                            }

                            $name = $str;
                        }
                        
                        if (strpos($link->href, '/torrent/') !== false) {
                            $thref = $link->href;
                            $re = '/\/(?P<id>\d{6})\-/i';
                            preg_match_all($re, $thref, $matches, PREG_SET_ORDER, 0);
                            $href = '?action=download&file=/engine/'.$matches[0]['id'];
                        }
                    }

                    if (is_null($href) || is_null($name)) {
                        continue;
                    }

                    // Extract date
                    $date = $line->children(2)->innertext;

                    // Extract size
                    $size = $line->children(3)->innertext;

                    // Extract seeds
                    $seeds = $line->children(4)->innertext;

                    // Extract leechs
                    $leechs = $line->children(5)->innertext;

                    $this->torrents[] = array(
                        'name' => $name,
                        'href' => $href,
                        'size' => $size,
                        'date' => $date,
                        'seeds' => $seeds,
                        'leechs' => $leechs,
                        'thref' => $thref
                    );
                }
            }

            return true;

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Search last torrent in given category
     * @param $category
     * @throws Exception
     */
    public function searchCategory($category)
    {
        try {
            $this->loopForTorrent('/torrents/' . $category . '?order=desc&sort=publish_date');
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
    public function download($url)
    {
        try {
            if ($this->call('download', $url) !== false) {
                return true;
            }

            return false;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
