<?php
require_once("Config.class.php");

class Flickr
{
    public static function getImage($search = null) {
        // Fetch only 1 initially in order to get number of pages info
        $data = file_get_contents("https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=" . Config::FLICKR_KEY . "&tags=" . $search . "&license=2&format=json&per_page=1");
        $obj = json_decode(substr($data, 14, -1));

        $page = 1;

        // Now choose a random page to get an image from
        do {
            $data = file_get_contents("https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=" . Config::FLICKR_KEY . "&tags=" . $search . "&license=2&format=json&per_page=500&tag_mode=all&page=" . $page);
            $obj = json_decode(substr($data, 14, -1));
        } while (count($obj->photos->photo) == 0);
        $use = mt_rand(0, count($obj->photos->photo));
        echo "https://farm{$obj->photos->photo[$use]->farm}.staticflickr.com/{$obj->photos->photo[$use]->server}/{$obj->photos->photo[$use]->id}_{$obj->photos->photo[$use]->secret}.jpg\n";
    }
}
?>
