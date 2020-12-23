<?php


namespace Qrawless\Lol;



use Qrawless\Lol\Helpers\Str;
use Qrawless\Lol\Traits\Model;

/**
 * Class Summoner
 * @property $api_key
 * @package Qrawless\Lol
 */
class Summoner extends Model
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
     * Get Summoner by Name.
     *
     * @param string $summonerName
     * @return object
     */
    public function byName(string $summonerName): object
    {
        $summonerName = str_replace(' ', '+', $summonerName);
        return (object) $this->get(Str::Replace($this->api_url.$this->endpoints["summonerByName"], [
            'server'    => $this->options["region"],
            'summoner'  => $summonerName
        ]), ["api_key"  => $this->api_key]);
    }

    /**
     * Get Summoner by Id.
     *
     * @param string $id
     * @return object
     */
    public function byId(string $id): object
    {
        return (object) $this->get(Str::Replace($this->api_url.$this->endpoints["summonerById"], [
            'server'    => $this->options["region"],
            'id'        => $id
        ]), ["api_key"  => $this->api_key]);
    }

    /**
     * Get Summoner by AccountId.
     *
     * @param string $accountId
     * @return object
     */
    public function byAccountId(string $accountId): object
    {
        return (object) $this->get(Str::Replace($this->api_url.$this->endpoints["summonerByAccountId"], [
            'server'    => $this->options["region"],
            'accountId' => $accountId
        ]), ["api_key"  => $this->api_key]);
    }

    /**
     * Get Summoner by Puuid.
     *
     * @param string $puuid
     * @return object
     */
    public function byPuuid(string $puuid): object
    {
        return (object) $this->get(Str::Replace($this->api_url.$this->endpoints["summonerByPuuid"], [
            'server'    => $this->options["region"],
            'puuid'        => $puuid
        ]), ["api_key"  => $this->api_key]);
    }
}