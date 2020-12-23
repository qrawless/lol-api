<?php


namespace Qrawless\Lol;


use Qrawless\Lol\Helpers\Str;
use Qrawless\Lol\Traits\Model;

/**
 * Class League
 * @property $api_key
 * @package Qrawless\Lol
 */
class Mastery extends Model
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
     * Get all champion mastery entries sorted by number of champion points descending.
     *
     * @param string $id
     * @return object
     */
    public function bySummoner(string $id): object
    {
        return (object) $this->get(Str::Replace($this->api_url.$this->endpoints["masteryBySummoner"], [
            'server'    => $this->options["region"],
            'id'        => $id
        ]), ["api_key"  => $this->api_key]);
    }
}