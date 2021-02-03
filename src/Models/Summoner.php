<?php


namespace Qrawless\Lol\Models;



use Qrawless\Lol\Helpers\Str;
use Qrawless\Lol\Traits\Model;
use Qrawless\Lol\Traits\Cache;

/**
 * Class Summoner
 * @property $api_key
 * @uses \Qrawless\Lol\Traits\Model
 * @uses \Qrawless\Lol\Lol
 * @package Qrawless\Lol
 */
class Summoner extends Model
{
    use Cache;

    /**
     * @var array
     */
    public array $options = [];

    /**
     * Summoner constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        parent::__construct($options);
        $this->options = $options;
    }

    /**
     * Search for username on all servers.
     *
     * @param string $summonerName
     * @return array
     */
    public function checkAllServers(string $summonerName): array
    {
        $data = [];
        $summonerName = str_replace(' ', '+', $summonerName);
//
        foreach ($this->options["servers"] as $server => $serverCode) {
            if ($serverCode == "americas" || $serverCode == "asia" || $serverCode == "europe") continue;

            $data[$serverCode] = array(Str::Replace($this->api_url.$this->endpoints["summonerByName"], [
                'server'    => $serverCode,
                'summoner'  => $summonerName
            ]), ["api_key"  => $this->api_key]);
        }
        $data = $this->multiGet($data);
        $dataA = [];
        $c = 0;
        foreach ($this->options["servers"] as $server => $serverCode) {
            if (empty($data[$c])) continue;
            $dataA[$serverCode] = $data[$c];
            $c++;
        }
        return $dataA;
    }


    public function byName(string $summonerName): object
    {
        $summonerName = str_replace(' ', '+', $summonerName);
        if ($this->initialize()->has($this->options["servers"][$this->options["region"]]."summoner_".base64_encode($summonerName))) return $this->initialize()->get($this->options["servers"][$this->options["region"]]."summoner_".base64_encode($summonerName));
        $data = $this->get(Str::Replace($this->api_url.$this->endpoints["summonerByName"], [
            'server'    => $this->options["servers"][$this->options["region"]],
            'summoner'  => $summonerName
        ]), ["api_key"  => $this->api_key]);
        $this->initialize()->set($this->options["servers"][$this->options["region"]]."summoner_".base64_encode($summonerName), json_decode(json_encode($data)), $this->options["cache"]["DDragon"]["summoner"]);
        return (object) json_decode(json_encode($data));
    }

    /**
     * Get Summoner by Id.
     *
     * @param string $id
     * @return object
     */
    public function byId(string $id): object
    {
        if ($this->initialize()->has($this->options["servers"][$this->options["region"]]."summoner_".base64_encode($id))) return $this->initialize()->get($this->options["servers"][$this->options["region"]]."summoner_".base64_encode($id));
        $data = $this->get(Str::Replace($this->api_url.$this->endpoints["summonerById"], [
            'server'    => $this->options["servers"][$this->options["region"]],
            'id'        => $id
        ]), ["api_key"  => $this->api_key]);
        $this->initialize()->set($this->options["servers"][$this->options["region"]]."summoner_".base64_encode($id), json_decode(json_encode($data)), $this->options["cache"]["DDragon"]["summoner"]);
        return (object) json_decode(json_encode($data));
    }

    /**
     * Get Summoner by AccountId.
     *
     * @param string $accountId
     * @return object
     */
    public function byAccountId(string $accountId): object
    {
        if ($this->initialize()->has($this->options["servers"][$this->options["region"]]."summoner_".base64_encode($accountId))) return $this->initialize()->get($this->options["servers"][$this->options["region"]]."summoner_".base64_encode($accountId));
        $data = $this->get(Str::Replace($this->api_url.$this->endpoints["summonerByAccountId"], [
            'server'    => $this->options["servers"][$this->options["region"]],
            'accountId' => $accountId
        ]), ["api_key"  => $this->api_key]);
        $this->initialize()->set($this->options["servers"][$this->options["region"]]."summoner_".base64_encode($accountId), json_decode(json_encode($data)), $this->options["cache"]["DDragon"]["summoner"]);
        return (object) json_decode(json_encode($data));
    }

    /**
     * Get Summoner by Puuid.
     *
     * @param string $puuid
     * @return object
     */
    public function byPuuid(string $puuid): object
    {
        if ($this->initialize()->has($this->options["servers"][$this->options["region"]]."summoner_".base64_encode($puuid))) return $this->initialize()->get($this->options["servers"][$this->options["region"]]."summoner_".base64_encode($puuid));
        $data = $this->get(Str::Replace($this->api_url.$this->endpoints["summonerByPuuid"], [
            'server'    => $this->options["servers"][$this->options["region"]],
            'puuid'     => $puuid
        ]), ["api_key"  => $this->api_key]);
        $this->initialize()->set($this->options["servers"][$this->options["region"]]."summoner_".base64_encode($puuid), json_decode(json_encode($data)), $this->options["cache"]["DDragon"]["summoner"]);
        return (object) json_decode(json_encode($data));
    }
}