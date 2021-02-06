<?php



namespace Qrawless\Lol;

use Exception;
use Qrawless\Lol\Models\MatchList;
use Qrawless\Lol\Models\Summoner;
use Qrawless\Lol\Models\League;
use Qrawless\Lol\Models\Mastery;
use Qrawless\Lol\Models\CommunityDragon;
use Qrawless\Lol\Models\DDragon;
use Qrawless\Lol\Traits\Model;

/**
 * Class Lol
 *
 * @property Summoner $summoner
 * @property Mastery $mastery
 * @property League $league
 * @property DDragon $dDragon
 * @property MatchList $matchList
 * @property CommunityDragon $communityDragon
 * @package Qrawless\Lol
 */
class Lol
{
    /**
     * @var array $models
     */
    private array $models;

    /**
     * @var array|string[] $servers
     */
    private array $servers = [
        "NORTH_AMERICA"   => "na1",
        "EUROPE_WEST"     => "euw1",
        "EUROPE_EAST"     => "eun1",
        "LAMERICA_SOUTH"  => "la2",
        "LAMERICA_NORTH"  => "la1",
        "BRASIL"          => "br1",
        "RUSSIA"          => "ru",
        "TURKEY"          => "tr1",
        "OCEANIA"         => "oc1",
        "KOREA"           => "kr",
        "JAPAN"           => "jp1",
        "AMERICAS"        => "americas",
        "EUROPE"          => "europe",
        "ASIA"            => "asia"
    ];

    /**
     * @var array|string[] $regions
     */
    private array $regions = [
        "NORTH_AMERICA"   => "na",
        "EUROPE_WEST"     => "euw",
        "EUROPE_EAST"     => "eune",
        "LAMERICA_SOUTH"  => "las",
        "LAMERICA_NORTH"  => "lan",
        "BRASIL"          => "br",
        "RUSSIA"          => "ru",
        "TURKEY"          => "tr",
        "OCEANIA"         => "oce",
        "KOREA"           => "kr",
        "JAPAN"           => "jp",
        "AMERICAS"        => "americas",
        "EUROPE"          => "europe",
        "ASIA"            => "asia"
    ];

    /**
     * @var array
     */
    private array $options = [
        'api_key'   => null,
        'region'    => "EUROPE_WEST",
        'language'  => "en_US",
        'curl'      => [],
        'servers'   => null,
        'regions'   => null,
        'cache' => [
            "DDragon"   => [
                "versions"          => 3600,
                "languages"         => 3600,
                "champions"         => 3600,
                "summoners"         => 3600,
                "summoner"          => 300,
                "matchList"         => 300,
                "mastery"           => 300,
                "league"            => 300,
                "challengerLeague"  => 3600,
            ]
        ]
    ];

    /**
     * Lol constructor.
     * @param array $options
     * @throws Exception
     */
    public function __construct(array $options)
    {
        foreach ($options as $key => $value) {
            $this->options[$key] = $value;
        }

        $this->options['servers'] = $this->servers;
        $this->options['regions'] = $this->regions;

        $this->models = [
            'model'             => new Model($this->options),
            'summoner'          => new Summoner($this->options),
            'matchList'         => new MatchList($this->options),
            'league'            => new League($this->options),
            'mastery'           => new Mastery($this->options),
            'communityDragon'   => new CommunityDragon($this->options),
            'dDragon'           => new DDragon($this->options),
        ];
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->models[$name];
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->models = [];
        $this->options[$name] = $value;
        $this->models = [
            'model'             => new Model($this->options),
            'summoner'          => new Summoner($this->options),
            'matchList'         => new MatchList($this->options),
            'league'            => new League($this->options),
            'mastery'           => new Mastery($this->options),
            'communityDragon'   => new CommunityDragon($this->options),
            'dDragon'           => new DDragon($this->options),
        ];
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name): bool
    {
        return isset($this->options[$name]);
    }

    /**
     * @return Lol
     */
    public function close(): Lol
    {
        foreach ($this->models as $model) {
            curl_close($model->curl);
        }
        return $this;
    }

    /**
     * @param $timestamp
     * @return false|string
     */
    public function timestamp($timestamp)
    {
        return date('Y-m-d H:i:s', $timestamp / 1000);
    }
}