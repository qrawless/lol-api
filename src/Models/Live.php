<?php


namespace Qrawless\Lol\Models;



use Qrawless\Lol\Helpers\Str;
use Qrawless\Lol\Traits\Model;
use Qrawless\Lol\Traits\Cache;

/**
 * Class Live
 * @property $api_key
 * @uses \Qrawless\Lol\Traits\Model
 * @uses \Qrawless\Lol\Lol
 * @package Qrawless\Lol
 */
class Live extends Model
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

    public function check($summonerId, $url = null){
        if ($this->initialize()->has($this->options["servers"][$this->options["region"]]."live_".base64_encode($summonerId))) $data = $this->initialize()->get($this->options["servers"][$this->options["region"]]."live_".base64_encode($summonerId));
        $data = $this->get(Str::Replace($this->api_url.$this->endpoints["live"], [
            'server'    => $this->options["servers"][$this->options["region"]],
            'id'        => $summonerId
        ]), ["api_key"  => $this->api_key]);
        $this->initialize()->set($this->options["servers"][$this->options["region"]]."live_".base64_encode($summonerId), json_decode(json_encode($data)), $this->options["cache"]["live"]);
        $data = json_decode(json_encode($data));
        if (@$data->gameId){
            return true;
        }

        return false;
    }
    public function activeGame($summonerId, $url = null){
        if ($this->initialize()->has($this->options["servers"][$this->options["region"]]."live_".base64_encode($summonerId))) return $this->initialize()->get($this->options["servers"][$this->options["region"]]."live_".base64_encode($summonerId));
        $data = $this->get(Str::Replace($this->api_url.$this->endpoints["live"], [
            'server'    => $this->options["servers"][$this->options["region"]],
            'id'        => $summonerId
        ]), ["api_key"  => $this->api_key]);
        $this->initialize()->set($this->options["servers"][$this->options["region"]]."live_".base64_encode($summonerId), json_decode(json_encode($data)), $this->options["cache"]["live"]);
        return (object) json_decode(json_encode($data));
    }
}