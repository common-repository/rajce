<?php

use WPFW\Caching\Cache;

class RajceFactory
{

    /**
     * Show content for widgetL album, photo
     * @param $attsArray
     */
    public static function getRajceContent( $attsArray ) {

        if (preg_match("/^.*\#.+\.(jpg|jpeg|png|gif)$/i", $attsArray['url'])) {
            //#DSC_6315-Edit.jpg - one photo

            return self::getPhoto($attsArray);
        } else {
            //album
            return self::getAlbum($attsArray);
        }

    }

    /**
     * Get URL DOM
     * @param $url
     * @return mixed
     */
    private static function getUrlDOM($url) {
        $html = wp_remote_fopen($url);

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($html);
        $DOMxpath = new DOMXPath($doc);

        return $DOMxpath;
    }

    /**
     * Get album from Rajce
     * @param $attsArray
     */
    private static function getAlbum($attsArray){

        $storage = new WPFW\Caching\Storages\FileStorage('temp');
        $cache = new WPFW\Caching\Cache($storage);

        $cachedValue = $cache->load($attsArray['url']);

        if( $cachedValue == NULL) {

            $DOMxpath = self::getUrlDOM($attsArray['url']);

            $photoNodes = $DOMxpath->query("//a[@class='photoThumb']");

            $out = '<div class="gallery gallery-columns-5 gallery-size-thumbnail">';

            foreach($photoNodes as $i => $photoNode) {
                $out.= '<dl class="gallery-item">';
                $out.= '<dt class="class=\"gallery-item\">';
                $out.= "<a href=\"{$photoNode->getAttribute('href')}\">";
                $out.= "<img src=\"". self::convertBigPhotoToThumbnail($photoNode->getAttribute('href'))."\" alt=\"\" class=\"attachment-thumbnail size-thumbnail\"/> <br/>";
                $out.= "</a>";
                //<a href="http://www.skolkatemelin.cz/wp-content/uploads/2015/10/PA050043.jpg" rel="lightbox[16]"><img width="150" height="150" src="http://www.skolkatemelin.cz/wp-content/uploads/2015/10/PA050043-150x150.jpg" class="attachment-thumbnail size-thumbnail" alt="OLYMPUS DIGITAL CAMERA" aria-describedby="gallery-1-140"></a>
                $out.= '</dt>';
                $out.= '</dl>';

                if(($i+1)%5 == 0) {
                    $out.= '<br style="clear: both">';
                }
            }

            $out.= '</div>';

            $cachedValue = $out;

            //save value to cache
            $cache->save($attsArray['url'], $cachedValue, array(
                Cache::EXPIRE => '1 day',
            ));

        }

        return $cachedValue;
    }

    /**
     * Get photo from rajce
     * @param $attsArray
     */
    private static function getPhoto($attsArray){

        $storage = new WPFW\Caching\Storages\FileStorage('temp');
        $cache = new WPFW\Caching\Cache($storage);

        $cachedValue = $cache->load($attsArray['url']);

        if( $cachedValue == NULL) {

            $DOMxpath = self::getUrlDOM($attsArray['url']);

            $photoNodes = $DOMxpath->query("//a[@class='photoThumb']");
            //I will only one photo, it is first photo
            $photoNode = $photoNodes->item(0);

            foreach($photoNodes as $i => $photoNode) {

                //echo $photoNode->getAttribute('href') . "|" . $attsArray['url'] . "<br/>";
                //echo "<hr/>";
                //echo self::getPhotoNameFromRajceGallery($photoNode->getAttribute('href'));
                //echo "<br/>";
                //echo self::getPhotoNameFromDetailUrl($attsArray['url']);
                //echo "<hr/>";

                if( self::getPhotoNameFromRajceGallery($photoNode->getAttribute('href')) == self::getPhotoNameFromDetailUrl($attsArray['url'])) {
                    //photo as from detail
                    $photoNode = $photoNode;
                    break;
                } else {
                    //go next photo
                    continue;
                }
            }

            $out = "";



            if( $attsArray['size'] == "big" ) {
                //show big photo
                $out.= "<a href=\"{$photoNode->getAttribute('href')}\">";
                $out.= "<img src=\"". $photoNode->getAttribute('href')."\" alt=\"\"/>";
                $out.= "</a>";
            } else {
                //show thumb
                $out.= "<a href=\"{$photoNode->getAttribute('href')}\">";
                $out.= "<img src=\"". self::convertBigPhotoToThumbnail($photoNode->getAttribute('href'))."\" alt=\"\" class=\"attachment-thumbnail size-thumbnail\"/>";
                $out.= "</a>";
            }

            $cachedValue = $out;

            //save value to cache
            $cache->save($attsArray['url'], $cachedValue, array(
                Cache::EXPIRE => '1 day',
            ));

        }

        return $cachedValue;
    }

    /**
     * Get photo name: http://sdh-zabori.rajce.idnes.cz/Stedry_den_v_hospode_20.12.2015#DSC_6246.jpg -> DSC_6246.jpg
     * @param $url
     * @return string
     */
    private static function getPhotoNameFromDetailUrl($url) {
        preg_match("/^.*\#(.+\..+)$/i", $url, $matches);

        return @$matches[1];
    }

    /**
     * Get photo name: http://img15.rajce.idnes.cz/d1501/12/12259/12259449_71d2e9d48e174b852b636df28e06e77b/images/DSC_6315-Edit.jpg -> DSC_6315-Edit.jpg
     * @param $url
     * @return string
     */
    private static function getPhotoNameFromRajceGallery($url) {
        preg_match("/^.*\/(.+)$/i", $url, $matches);

        return @$matches[1];
    }

    /**
     * Get photo from rajce
     * @param $url
     */
    public static function convertBigPhotoToThumbnail($urlBigPhoto){
        return str_replace("/images/","/thumb/", $urlBigPhoto);
    }

}