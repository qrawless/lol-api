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
     * @param false $url
     * @return mixed|string|null
     */
    public function accountId(string $accountId, array $options = null, $url = false)
    {
        $str = Str::Replace($this->api_url.$this->endpoints["matchLists"], ['server' => $this->options["servers"][$this->options["region"]], 'accountId' => $accountId]);
        if ($url === true) if (empty($options)) { return $str; } else { return sprintf("%s?%s", $str, http_build_query($options)); }
        if ($this->initialize()->has($this->options["servers"][$this->options["region"]]."matchlist_".base64_encode($accountId))) return $this->initialize()->get($this->options["servers"][$this->options["region"]]."matchlist_".base64_encode($accountId));
        $options["api_key"] = $this->api_key;
        $data = $this->get($str, $options);
        $this->initialize()->set($this->options["servers"][$this->options["region"]]."matchlist_".base64_encode($accountId), json_decode(json_encode($data)), $this->options["cache"]["DDragon"]["matchList"]);
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
            "ADC"       => 0,
            "SUPPORT"   => 0,
        ];
        foreach ($matchList as $match) {
            if ($match->lane === "TOP") $Lanes["TOP"]++;
            if ($match->lane === "MID") $Lanes["MID"]++;
            if ($match->lane === "JUNGLE") $Lanes["JUNGLE"]++;
            if ($match->lane === "BOTTOM") {
                if ($match->role === "DUO_SUPPORT") { $Lanes["SUPPORT"]++; } else { $Lanes["ADC"]++; }
            }
        }

        arsort($Lanes);
        return $Lanes;
    }
}