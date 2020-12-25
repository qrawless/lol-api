<?php


namespace Qrawless\Lol\Models;


use Phpfastcache\Helper\Psr16Adapter;
use Qrawless\Lol\Helpers\Str;
use Qrawless\Lol\Traits\Cache;
use Qrawless\Lol\Traits\Model;

class DDragon extends Model
{
    use Cache;

    /**
     * @var object|Psr16Adapter
     */
    public object $cache;

    /**
     * @var string
     */
    public string $default_lang = "en_US";

    /**
     * @var string
     */
    public string $version;

    /**
     * DDragon constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        parent::__construct($options);
        $this->options = $options;

        $this->version = $this->getVersion()->latest;

    }

    /**
     * get versions.json
     *
     * @return object
     */
    public function getVersion(): object
    {
        if ($this->initialize()->has("versions")) return $this->initialize()->get("versions");
        $data = json_decode(json_encode($this->get("https://DDragon.leagueoflegends.com/api/versions.json"),false),true);
        $data["latest"] = $data[0];
        $this->initialize()->set("versions", json_decode(json_encode($data)), $this->options["cache"]["DDragon"]["versions"]);
        return (object) json_decode(json_encode($data));
    }

    /**
     * Get supported languages list.
     *
     * @return object
     */
    public function getLanguages(): object
    {
        if ($this->initialize()->has("languages")) return $this->initialize()->get("languages");
        $data = $this->get("https://DDragon.leagueoflegends.com/cdn/languages.json");
        $this->initialize()->set("languages", json_decode(json_encode($data)), $this->options["cache"]["DDragon"]["languages"]);
        return (object) json_decode(json_encode($data));
    }

    /**
     * Get all champion data.
     *
     * @return object
     */
    public function getChampions(): object
    {
        if ($this->initialize()->has("champions")) return $this->initialize()->get("champions");
        $data = $this->get(Str::Replace("http://DDragon.leagueoflegends.com/cdn/:version/data/:language/champion.json", [
            'version'    => $this->version,
            'language'  => $this->default_lang
        ]));
        $this->initialize()->set("champions", json_decode(json_encode($data)), $this->options["cache"]["DDragon"]["champions"]);
        return (object) json_decode(json_encode($data));
    }

    /**
     * get data by Champion Id.
     *
     * @param int $championId
     * @return object|bool
     */
    public function getChampionById(int $championId)
    {
        if ($this->initialize()->has("champions")) {
            foreach ($this->initialize()->get("champions")->data as $champion => $data) {
                if ($data->key == $championId) return $data;
            }
            return false;
        }
        else return $this->getChampions();
    }

    /**
     * get data by Champion key.
     *
     * @param string $championKey
     * @return false|mixed|object
     */
    public function getChampionByKey(string $championKey)
    {
        if ($this->initialize()->has("champions")) {
            foreach ($this->initialize()->get("champions")->data as $champion => $data) {
                if (strtolower($data->id) == strtolower($championKey)) return $data;
            }
            return false;
        }
        else return $this->getChampions();
    }
}