<?php


namespace Qrawless\Lol;


use Qrawless\Lol\Helpers\Str;
use Qrawless\Lol\Traits\Model;

/**
 * Class League
 * @property $api_key
 * @package Qrawless\Lol
 */
class League extends Model
{
    /**
     * @var array $options
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
     * Get league entries in all queues for a given summoner ID.
     *
     * @param string $id
     * @return string
     */
    public function bySummoner(string $id)
    {
        $league = $this->get(Str::Replace($this->api_url.$this->endpoints["leagueBySummoner"], [
            'server'    => $this->options["region"],
            'id'        => $id
        ]), ["api_key"  => $this->api_key]);
        foreach ($league as $key => $value) { $l[$value->queueType] = $value; }
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
        return (object) $this->get(Str::Replace($this->api_url.$this->endpoints["leagueChallengerLeagues"], [
            'server'    => $this->options["region"],
            'league'    => $league
        ]), ["api_key"  => $this->api_key]);
    }
}