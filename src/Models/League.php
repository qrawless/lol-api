<?php


namespace Qrawless\Lol\Models;


use Qrawless\Lol\Helpers\Str;
use Qrawless\Lol\Traits\Cache;
use Qrawless\Lol\Traits\Model;

/**
 * Class League
 * @property $api_key
 * @package Qrawless\Lol
 */
class League extends Model
{
    use Cache;

    /**
     * @var array $options
     */
    public array $options = [];

    /**
     * League constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        parent::__construct($options);
        $this->options = $options;
    }

    /**
     * Get league entries in all queues for a given summoner ID.
     *
     * @param string $id
     * @return string
     */
    public function bySummoner(string $id, $url=false)
    {
        $str = Str::Replace($this->api_url.$this->endpoints["leagueBySummoner"], ['server' => $this->options["servers"][$this->options["region"]], 'id' => $id]);
        if ($url === true) return $str;
        if ($this->initialize()->has($this->options["servers"][$this->options["region"]]."league_".base64_encode($id))) return $this->initialize()->get($this->options["servers"][$this->options["region"]]."league_".base64_encode($id));
        $data = $this->get($str, ["api_key"  => $this->api_key]);
        foreach ($data as $key => $value) { $l[$value->queueType] = $value; }
        if (!empty($l)){
            $this->initialize()->set($this->options["servers"][$this->options["region"]]."league_".base64_encode($id), json_decode(json_encode($l, true), false), $this->options["cache"]["DDragon"]["league"]);
            return (object) json_decode(json_encode($l, true), false);
        }
        return (object) json_decode(json_encode($data, true), false);
    }

    /**
     * Get the challenger league for given queue.
     *
     * @param string $league
     * @return object
     */
    public function challengerLeague(string $league): object
    {
        if ($this->initialize()->has($this->options["servers"][$this->options["region"]]."challengerLeague")) return $this->initialize()->get($this->options["servers"][$this->options["region"]]."challengerLeague");
        $data = $this->get(Str::Replace($this->api_url.$this->endpoints["leagueChallengerLeagues"], [
            'server'    => $this->options["servers"][$this->options["region"]],
            'league'    => $league
        ]), ["api_key"  => $this->api_key]);
        $this->initialize()->set($this->options["servers"][$this->options["region"]]."challengerLeague", json_decode(json_encode($data)), $this->options["cache"]["DDragon"]["challengerLeague"]);
        return (object) json_decode(json_encode($data));
    }
}