<?php


namespace Qrawless\Lol\Models;


use Qrawless\Lol\Helpers\Str;
use Qrawless\Lol\Traits\Cache;
use Qrawless\Lol\Traits\Model;

/**
 * Class MatchList
 * @property $api_key
 * Class MatchList
 * @package Qrawless\Lol\Models
 */
class MatchList extends Model
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
     * @param string $accountId
     * @param array|null $options
     * @return mixed|object|null
     */
    public function accountId(string $accountId, array $options = null): object
    {
        $options["api_key"] = $this->api_key;
        if ($this->initialize()->has("matchlist_".base64_encode($accountId))) return $this->initialize()->get("matchlist_".base64_encode($accountId));
        $data = $this->get(Str::Replace($this->api_url.$this->endpoints["matchlists"], [
            'server'    => $this->options["servers"][$this->options["region"]],
            'accountId' => $accountId
        ]), $options);
        $this->initialize()->set("matchlist_".base64_encode($accountId), json_decode(json_encode($data)), $this->options["cache"]["DDragon"]["matchList"]);
        return json_decode(json_encode($data));
    }

    /**
     * @param array|null $matchList
     * @return false|int[]
     */
    public function Lanes(array $matchList=null)
    {
        if (empty($matchList)) return false;

        $Lanes = [
            "TOP"       => 0,
            "JUNGLE"    => 0,
            "MID"       => 0,
            "BOTTOM"    => 0,
            "SUPPORT"   => 0,
        ];
        foreach ($matchList as $match) {
            if ($match->lane === "TOP") $Lanes["TOP"]++;
            if ($match->lane === "MID") $Lanes["MID"]++;
            if ($match->lane === "JUNGLE") $Lanes["JUNGLE"]++;
            if ($match->lane === "BOTTOM") {
                if ($match->role === "DUO_SUPPORT") { $Lanes["SUPPORT"]++; } else { $Lanes["BOTTOM"]++; }
            }
        }

        arsort($Lanes);
        return $Lanes;
    }
}