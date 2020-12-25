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
class Mastery extends Model
{
    use Cache;

    /**
     * @var array $options
     */
    public array $options = [];

    /**
     * Mastery constructor.
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
        if ($this->initialize()->has("mastery_".base64_encode($id))) return $this->initialize()->get("mastery_".base64_encode($id));
        $data = $this->get(Str::Replace($this->api_url.$this->endpoints["masteryBySummoner"], [
            'server'    => $this->options["servers"][$this->options["region"]],
            'id'        => $id
        ]), ["api_key"  => $this->api_key]);
        $this->initialize()->set("mastery_".base64_encode($id), json_decode(json_encode($data)), $this->options["cache"]["DDragon"]["summoner"]);
        return (object) json_decode(json_encode($data));
    }
}