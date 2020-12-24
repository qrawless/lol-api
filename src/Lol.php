<?php



namespace Qrawless\Lol;

/**
 * Class Lol
 * @method $models
 * @method $summoner
 * @method $options
 * @package Qrawless\Lol
 */
class Lol
{
    /**
     * @var array $models
     */
    private array $models;

    /**
     * @var array $servers
     */
    private array $servers;

    /**
     * @var array
     */
    private array $options = [
        'api_key'   => null,
        'region'    => null,
        'curl'      => []
    ];

    /**
     * Lol constructor.
     * @param array $options
     * @throws \Exception
     */
    public function __construct(array $options)
    {
        foreach ($options as $key => $value) {
            $this->options[$key] = $value;
        }


        $this->servers = [
            "br"      => "br1",
            "eune"    => "eun1",
            "euw"     => "euw1",
            "jp"      => "jp1",
            "kr"      => "kr",
            "lan"     => "la1",
            "las"     => "la2",
            "na"      => "na1",
            "oce"     => "oc1",
            "tr"      => "tr1",
            "ru"      => "ru"
        ];

        if (!$this->servers($this->options["region"])) throw new \Exception("Region not found");

        $this->options["region"] = $this->servers($this->options["region"]);

        $this->models = [
            'summoner'  => new Summoner($this->options),
            'league'    => new League($this->options),
            'mastery'   => new Mastery($this->options),
        ];
    }

    /**
     * @param $name
     * @return mixed|string
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
        $this->options[$name] = $value;
    }

    /**
     * @param string|null $server
     * @return array|mixed|string|string[]
     */
    public function servers(?string $server = null)
    {
        if ($server === null) return $this->servers;
        else if(array_key_exists($server, $this->servers)) return $this->servers[$server];
        else return false;
    }

    /**
     * Set api region.
     *
     * @param string|null $server
     * @return bool
     */
    public function setServer(?string $server = null): bool
    {
        if($this->servers($server)){
            $this->options["region"]    = $this->servers[$server];
            $this->summoner->options    = $this->options;
            $this->mastery->options     = $this->options;
            $this->league->options      = $this->options;
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getServer()
    {
        return $this->options["region"];
    }

    /**
     * Curl close.
     */
    public function close()
    {
        foreach ($this->models as $model) {
            curl_close($model->curl);
        }
    }
}