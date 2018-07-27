<?php
namespace App\Service;

class TextFormatService
{

    public function format_twitch_video_thumbnail_url($url)
    {
        $url = preg_replace('#(.+)%{width}(.+)#', '$1 260 $2', $url);
        $url = preg_replace('#(.+)%{height}(.+)#', '$1 147 $2', $url);
        $url = str_replace(' ', '', $url);

        return $url;
    }

}