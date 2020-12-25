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
     * @return object
     */
    public function bySummoner(string $id): object
    {
        if ($this->initialize()->has("league_".base64_encode($id))) return $this->initialize()->get("league_".base64_encode($id));
        $data = $this->get(Str::Replace($this->api_url.$this->endpoints["leagueBySummoner"], [
            'server'    => $this->options["servers"][$this->options["region"]],
            'id'        => $id
        ]), ["api_key"  => $this->api_key]);
        foreach ($data as $key => $value) { $l[$value->queueType] = $value; }
        $this->initialize()->set("league_".base64_encode($id), json_decode(json_encode($l, true), false), $this->options["cache"]["DDragon"]["league"]);
        return (object) json_decode(json_encode($l, true), false);
    }

    /**
     * Get the challenger league for given queue.
     *
     * @param string $league
     * @return object
     */
    public function challengerLeague(string $league): object
    {
        if ($this->initialize()->has("challengerLeague")) return $this->initialize()->get("challengerLeague");
        $data = $this->get(Str::Replace($this->api_url.$this->endpoints["leagueChallengerLeagues"], [
            'server'    => $this->options["servers"][$this->options["region"]],
            'league'    => $league
        ]), ["api_key"  => $this->api_key]);
        $this->initialize()->set("challengerLeague", json_decode(json_encode($data)), $this->options["cache"]["DDragon"]["challengerLeague"]);
        return (object) json_decode(json_encode($data));
    }
}