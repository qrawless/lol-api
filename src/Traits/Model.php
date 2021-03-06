<?php


namespace Qrawless\Lol\Traits;



use CurlHandle;

/**
 * Class Model
 * @property $curl
 * @package Qrawless\Lol\Traits
 */
class Model
{
    /**
     * @var CurlHandle|false|resource
     */
    protected $curl;

    /**
     * @var string
     */
    protected string $api_url = "https://:server.api.riotgames.com";

    /**
     * @var array|string[]
     */
    protected array $endpoints = [
        // Summoner
        "summonerByName"            => "/lol/summoner/v4/summoners/by-name/:summoner",
        "summonerById"              => "/lol/summoner/v4/summoners/:id",
        "summonerByAccountId"       => "/lol/summoner/v4/summoners/by-account/:accountId",
        "summonerByPuuid"           => "/lol/summoner/v4/summoners/by-puuid/:puuid",
        // League
        "leagueBySummoner"          => "/lol/league/v4/entries/by-summoner/:id",
        "leagueChallengerLeagues"   => "/lol/league/v4/challengerleagues/by-queue/:league",
        // Mastery
        "masteryBySummoner"         => "/lol/champion-mastery/v4/champion-masteries/by-summoner/:id",
        // Matchlist
        "matchLists"                => "/lol/match/v4/matchlists/by-account/:accountId",
        "match"                     => "/lol/match/v4/matches/:matchId",
        // Live
        "live"                      => "/lol/spectator/v4/active-games/by-summoner/:id",
        // Live
        "championRotations"         => "/lol/platform/v3/champion-rotations",
    ];

    /**
     * @var array
     */
    protected array $options = [];

    /**
     * Model constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->curl, CURLOPT_HEADER, 1);
        curl_setopt($this->curl, CURLOPT_TIMEOUT_MS, 3000);

        if (isset($this->options['curl'])) {
            if (isset($this->options['curl']['verify']) && $this->options['curl']['verify'] == false) {
                curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
            }
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->$name)) return $this->$name;
        return $this->options[$name];
    }

    /**
     * @param string $url
     * @param array $options
     * @return int|mixed
     */
    public function get(string $url, array $options = [])
    {
        $curl = $this->curl;

        if (isset($options)) $url = sprintf("%s?%s", $url, http_build_query($options));

        if (@$options["queue"]){
            foreach ($options["queue"] as $key => $option) { $url = str_replace("%5B$key%5D","", $url); }
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HEADER, 0);

        $data = json_decode(curl_exec($curl), false);

        return $data;
    }

    /**
     * Soo experimental.... xd
     *
     * @param array $urls
     * @return array|false
     */
    public function multiGet(array $urls)
    {
        if (empty($urls)) return false;
        $nodes = [];
        foreach ($urls as $url){
            if (is_array($url)){
                if (preg_match_all('/(\?)/', $url[0])){
                    $url = sprintf("%s&%s", $url[0], http_build_query($url[1]));
                } else {
                    $url = sprintf("%s?%s", $url[0], http_build_query($url[1]));
                }
            }
            array_push($nodes, $url);
        }

//        print_r($nodes);
//        die();

        $node_count = count($nodes);

        $curl_arr = array();
        $master = curl_multi_init();

        for($i = 0; $i < $node_count; $i++)
        {
            $url =$nodes[$i];
            $curl_arr[$i] = curl_init($url);
            curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);
            curl_multi_add_handle($master, $curl_arr[$i]);
        }

        do {
            curl_multi_exec($master,$running);
        } while($running > 0);


        for($i = 0; $i < $node_count; $i++)
        {
            $results[] = json_decode(curl_multi_getcontent($curl_arr[$i]), false);
        }
        return $results;
    }
}