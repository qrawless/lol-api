<?php


namespace Qrawless\Lol\Traits;


use Qrawless\Lol;
use Qrawless\Lol\Exceptions;

/**
 * Class Model
 * @property $curl
 * @package Qrawless\Lol\Traits
 */
class Model
{
    /**
     * @var $curl
     */
    protected $curl;

    /**
     * @var string
     */
    protected string $api_url = "https://:server.api.riotgames.com";

    /**
     * @var array|string[]
     */
    protected array $servers = [
      "br"      => "br1",
      "eune"    => "eun1",
      "euw"     => "euw1",
      "jp"      => "jp1",
      "kr"      => "kr",
      "lan"     => "la1",
      "las"     => "la2",
      "na"      => "na1",
      "oce"     => "oc1",
      "tr"      => "tr1",
      "ru"      => "ru"
    ];

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
    ];

    /**
     * @var array
     */
    protected array $options = [

    ];

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
     * HTTP GET action
     *
     * @param string $url
     * @param array $options
     * @return object
     */
    public function get(string $url, array $options = []): object
    {
        $curl = $this->curl;

        curl_setopt($curl, CURLOPT_URL, sprintf("%s?%s", $url, http_build_query($options)));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HEADER, 0);

        retry:
        $data = json_decode(curl_exec($curl), false);
        $info = curl_getinfo($curl);
        if($info["http_code"] === 200) return (object) $data;
        else if ($info["http_code"] === 429) sleep(3); goto retry;
    }
}