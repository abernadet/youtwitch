<?php
namespace App\Service;

class TwitchApiService
{
    private $client_id = 'wb57fz1kqexwbl5w03vrig184qh78h';

    public function getLiveStream($channelID)
    {
        $url = 'https://api.twitch.tv/helix/streams?user_id'.$channelID;

        //Option de requête
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "Client-ID: ".$this->client_id
            ]
        ];
        $context = stream_context_create($opts);
        $json_result = file_get_contents($url, false, $context);
        $result = json_decode($json_result);

        return $result;
    }

    public function getLiveStreamFromLogin($login)
    {
        $url = 'https://api.twitch.tv/helix/streams?user_login='.$login;

        //Option de requête
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "Client-ID: ".$this->client_id
            ]
        ];
        $context = stream_context_create($opts);
        $json_result = file_get_contents($url, false, $context);
        $result = json_decode($json_result);

        return $result;
    }
    
    //Return all live streams from an array of channel IDs
    public function getLiveStreams(array $channelIDs)
    {
        $url = 'https://api.twitch.tv/helix/streams';
        $i = 1;
        foreach($channelIDs as $channelID){
            if($i === 1){
                $url .= '?user_id='.$channelID;
                $i++;
            }else{
                $url .= '&user_id='.$channelID;
            }
        }

        //Option de requête
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "Client-ID: ".$this->client_id
            ]
        ];
        $context = stream_context_create($opts);
        $json_result = file_get_contents($url, false, $context);
        $result = json_decode($json_result);

        return $result;
    }

    //Return a twitch user's data from twitch user's id
    public function getUserFromId($user_id)
    {
        $url = 'https://api.twitch.tv/helix/users?id='.$user_id;
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "Client-ID: ".$this->client_id
            ]
        ];
        $context = stream_context_create($opts);
        $json_result = file_get_contents($url, false, $context);
        $result = json_decode($json_result);

        return $result;
    }

    //Return multiple twitch user's data from and array of twitch user's id
    public function getUsersFromId(array $users_id)
    {
        $url = 'https://api.twitch.tv/helix/users';
        $i = 0;
        foreach($users_id as $user_id)
        {
            if($i == 0)
            {
                $url .= '?id='.$user_id;
                $i++;
            }
            else
            {
                $url .= '&id='.$user_id;
            }
        }

        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "Client-ID: ".$this->client_id
            ]
        ];
        $context = stream_context_create($opts);
        $json_result = file_get_contents($url, false, $context);
        $result = json_decode($json_result);

        return $result->data;
    }

    //Return a twitch user's data from user's twitch login
    public function getUserFromLogin($login)
    {
        $url = 'https://api.twitch.tv/helix/users?login='.$login;
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "Client-ID: ".$this->client_id
            ]
        ];
        $context = stream_context_create($opts);
        $json_result = file_get_contents($url, false, $context);
        $result = json_decode($json_result);

        return $result;
    }

    //Return user's twitch id from user's twitch login
    public function getUserIdFromLogin($login)
    {

        try{
            $user_data = $this->getUserFromLogin($login);
            $user_id = $user_data->data[0]->id;
            return $user_id;
        }catch(\Exception $e){
            return false;
        }
        
    }

    public function getUserFollowsId($user_id)
    {

        $url = 'https://api.twitch.tv/helix/users/follows?from_id='.$user_id.'&first=100';
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "Client-ID: ".$this->client_id
            ]
        ];
        $context = stream_context_create($opts);
        $json_result = file_get_contents($url, false, $context);
        $results = json_decode($json_result);

        $channels_id = [];

        $data = $results->data;

        foreach($data as $follow)
        {
            $channels_id[] = $follow->to_id;
        }

        return $channels_id;
    }

    /**
     * $max : number of clips returned
     * array $channel : logins of channels where we search for clips
     * $trending : get results by popularity, otherwise get most viewed clips (default = true)
     */
    public function getClipsFromChannels(array $channel_names, $period = 'week', $max = 5, $trending = true)
    {
        $url = 'https://api.twitch.tv/kraken/clips/top?channel=';

        $i = 1;
        foreach($channel_names as $channel_name)
        {
            if($i == 1)
            {
                $url .= $channel_name;
                $i++;
            }
            else
            {
                $url .= ','.$channel_name;
            }
        }
        $url .= '&trending='.$trending;
        $url .= '&limit='.$max;
        
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "Client-ID: ".$this->client_id . "\r\n" .
                            "Accept: application/vnd.twitchtv.v4+json\r\n"
            ]
        ];
        $context = stream_context_create($opts);
        $json_result = file_get_contents($url, false, $context);
        $result = json_decode($json_result);

        return $result;
    }

    public function getClipsFromChannel($channel_name, $period = 'week', $max = 5)
    {
        $url = 'https://api.twitch.tv/kraken/clips/top?channel='.$channel_name.'&limit='.$max.'&period='.$period;

        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "Client-ID: ".$this->client_id . "\r\n" .
                            "Accept: application/vnd.twitchtv.v5+json\r\n"
            ]
        ];

        $context = stream_context_create($opts);
        $json_result = file_get_contents($url, false, $context);
        $result = json_decode($json_result);

        dump($result);

        return $result;
    }

    public function getClipData($slug)
    {
        $url = 'https://api.twitch.tv/kraken/clips/'.$slug;

        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "Client-ID: ".$this->client_id . "\r\n" .
                            "Accept: application/vnd.twitchtv.v5+json\r\n"
            ]
        ];

        $context = stream_context_create($opts);
        $json_result = file_get_contents($url, false, $context);
        $result = json_decode($json_result);

        dump($result);
        return $result;
    }

    public function getVideosFromChannel($user_id, $period = 'week', $max = '5', $sort = 'time')
    {
        $url = 'https://api.twitch.tv/helix/videos?user_id='.$user_id.'&period='.$period.'&first='.$max.'&sort='.$sort;

        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "Client-ID: ".$this->client_id
            ]
        ];

        $context = stream_context_create($opts);
        $json_result = file_get_contents($url, false, $context);
        $result = json_decode($json_result);

        return $result;

    }

    public function getVideoData($vid_id)
    {
        $url = 'https://api.twitch.tv/helix/videos?id='.$vid_id;

        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "Client-ID: ".$this->client_id
            ]
        ];

        $context = stream_context_create($opts);
        $json_result = file_get_contents($url, false, $context);
        $result = json_decode($json_result);

        return $result;
    }

    public function getRandomUserFollowsId($user_id, $max)
    {
        $follows_id = $this->getUserFollowsId($user_id);
        $random_follows_id = [];

        if($follows_id <= $max)
        {
            return $follows_id;
        }

        $random_index_array = [];

        for($i=0; $i<$max; $i++)
        {
            $loop = true;
            $random_nb = 0;

            while($loop === true)
            {
                $random_nb = rand(1, count($follows_id) - 1);

                if(!in_array($random_nb, $random_index_array))
                {
                    $loop = false;
                    $random_index_array[] = $random_nb;
                }
            }

            $random_follows_id[] = $follows_id[$random_nb];
        }

        return $random_follows_id;
    }
}