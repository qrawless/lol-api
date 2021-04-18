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
        if ($url === true) {if (empty($options)) {return $str;} else {if (!empty($options["queue"])){$strr = sprintf("%s?%s", $str, http_build_query($options));foreach ($options["queue"] as $key => $option) {$strr = str_replace("%5B$key%5D","", $strr);} return $strr;}else{return sprintf("%s?%s", $str, http_build_query($options));}}}
        if ($this->initialize()->has($this->options["servers"][$this->options["region"]]."_".base64_encode(json_encode($options))."_matchlist_".base64_encode($accountId))) return $this->initialize()->get($this->options["servers"][$this->options["region"]]."_".base64_encode(json_encode($options))."_matchlist_".base64_encode($accountId));
        $options["api_key"] = $this->api_key;
        $data = $this->get($str, $options);
        $this->initialize()->set($this->options["servers"][$this->options["region"]]."_".base64_encode(json_encode($options))."_matchlist_".base64_encode($accountId), json_decode(json_encode($data)), $this->options["cache"]["DDragon"]["matchList"]);
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
            if($match->queue === 450) continue;
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


    public function matchURLGen(array $list)
    {
        $data = [];
        $new  = [];
        $ids  = [];
        $diffs = 604800;

        foreach ($list as $match){
            $platformId = $match->platformId;
            $gameId     = $match->gameId;
            $timestamp  = date('Y-m-d H:i:s', $match->timestamp / 1000);
            $diff = time() - strtotime($timestamp);


            if ($diff < $diffs){
                if (!$this->initialize()->has($platformId."_match_".$gameId)) {
                    array_push($new, [Str::Replace($this->api_url.$this->endpoints["match"], ['server' => $this->options["servers"][$this->options["region"]], 'matchId' => $gameId]),["api_key"  => $this->api_key]]);
                }
            }
        }

        foreach ($this->multiGet($new) as $datum) {
            foreach ($datum->participantIdentities as $participantIdentity) {
                $datum->participants[$participantIdentity->participantId-1]->summonerId = $participantIdentity->player->summonerId;
                $datum->participants[$participantIdentity->participantId-1]->platformId = $participantIdentity->player->platformId;
                $datum->participants[$participantIdentity->participantId-1]->currentPlatformId = $participantIdentity->player->currentPlatformId;
                $datum->participants[$participantIdentity->participantId-1]->summonerName = $participantIdentity->player->summonerName;
            }
            $this->initialize()->set($datum->platformId."_match_".$datum->gameId, json_decode(json_encode($datum)), $this->options["cache"]["DDragon"]["match"]);
        }

        foreach ($list as $match){
            $platformId = $match->platformId;
            $gameId     = $match->gameId;
            $timestamp  = date('Y-m-d H:i:s', $match->timestamp / 1000);
            $diff = time() - strtotime($timestamp);

            if ($diff < $diffs){
                if ($this->initialize()->has($platformId."_match_".$gameId)) {
                    array_push($ids, [$platformId, $gameId]);
                }
            }
        }

        foreach ($ids as $id) {
            $platformId = $id[0];
            $gameId     = $id[1];
            array_push($data, $this->initialize()->get($platformId."_match_".$gameId));
        }

        return $data;
    }
//    public function match(string $matchId, $url = false)
//    {
//        $str = Str::Replace($this->api_url.$this->endpoints["match"], ['server' => $this->options["servers"][$this->options["region"]], 'matchId' => $matchId]);
//        $options["api_key"] = $this->api_key;
//        die($str);
//        $data = $this->get($str, $options);
//        return json_decode(json_encode($data));
//    }
}