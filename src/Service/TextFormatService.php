<?php
namespace App\Service;

class TextFormatService
{

    public function format_twitch_video_thumbnail_url($url, $width= '260', $height='147')
    {
        $url = preg_replace('#(.+)%{width}(.+)#', '$1 '. $width .' $2', $url);
        $url = preg_replace('#(.+)%{height}(.+)#', '$1 '. $height .' $2', $url);
        $url = str_replace(' ', '', $url);

        return $url;
    }

}