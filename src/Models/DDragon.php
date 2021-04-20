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
     * @var array
     */
    public array $options = [];

    /**
     * @var object|Psr16Adapter
     */
    public object $cache;

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
        if ($this->initialize()->has("champions_".$this->options["language"])) return $this->initialize()->get("champions_".$this->options["language"]);
        $data = $this->get(Str::Replace("http://DDragon.leagueoflegends.com/cdn/:version/data/:language/champion.json", [
            'version'    => $this->version,
            'language'  => $this->options["language"]
        ]));
        $this->initialize()->set("champions_".$this->options["language"], json_decode(json_encode($data)), $this->options["cache"]["DDragon"]["champions"]);
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
        if ($this->initialize()->has("champions_".$this->options["language"])) {
            foreach ($this->initialize()->get("champions_".$this->options["language"])->data as $champion => $data) {
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
        if ($this->initialize()->has("champions_".$this->options["language"])) {
            foreach ($this->initialize()->get("champions_".$this->options["language"])->data as $champion => $data) {
                if (strtolower($data->id) == strtolower($championKey)) return $data;
            }
            return false;
        }
        else return $this->getChampions();
    }

    /**
     * @param int $iconId
     * @return string
     */
    public function profileIcon(int $iconId): string
    {
        return (string) Str::Replace("http://ddragon.leagueoflegends.com/cdn/10.25.1/img/profileicon/:icon.png", [
            'version'   => $this->version,
            'language'  => $this->options["language"],
            'icon'      => $iconId
        ]);
    }


    /**
     * @return object
     */
    public function getSummoners(): object
    {
        if ($this->initialize()->has("summoner_".$this->options["language"])) return $this->initialize()->get("summoner_".$this->options["language"]);
        $data = $this->get(Str::Replace("http://ddragon.leagueoflegends.com/cdn/:version/data/:language/summoner.json", [
            'version'    => $this->version,
            'language'  => $this->options["language"]
        ]));
        $this->initialize()->set("summoner_".$this->options["language"], json_decode(json_encode($data)), $this->options["cache"]["DDragon"]["summoners"]);
        return (object) json_decode(json_encode($data));
    }

    /**
     * @param int $summonerKey
     * @return object
     */
    public function getSummonerByKey(int $summonerKey): object
    {
        if ($this->initialize()->has("summoner_".$this->options["language"])) {
            foreach ($this->initialize()->get("summoner_".$this->options["language"])->data as $summoner_key => $summoner) {
                if (strtolower($summoner->key) == strtolower($summonerKey)) return $summoner;
            }
        }
        else return $this->getSummoners();
    }

    /**
     * @param string $summonerId
     * @return object
     * @throws \ReflectionException
     */
    public function getSummonerById(string $summonerId): object
    {
        if ($this->initialize()->has("summoner_".$this->options["language"])) {
            foreach ($this->initialize()->get("summoner_".$this->options["language"])->data as $summoner_key => $summoner) {
                if (strtolower($summoner->id) == strtolower($summonerId)) return $summoner;
            }
        }
        else return $this->getSummoners();
    }

    /**
     * @param int $summonerKey
     * @return string
     */
    public function summonerIconByKey(int $summonerKey): string
    {
        return (string) Str::Replace("http://ddragon.leagueoflegends.com/cdn/:version/img/spell/:icon", [
            'version'   => $this->version,
            'icon'      => $this->getSummonerByKey($summonerKey)->image->full
        ]);
    }

    /**
     * @param int $summonerId
     * @return string
     * @throws \ReflectionException
     */
    public function summonerIconById(int $summonerId): string
    {
        return (string) Str::Replace("http://ddragon.leagueoflegends.com/cdn/:version/img/spell/:icon", [
            'version'   => $this->version,
            'icon'      => $this->getSummonerById($summonerId)->image->full
        ]);
    }


    /**
     * @return object
     * @throws \ReflectionException
     */
    public function getItems(): object
    {
        if ($this->initialize()->has("items_".$this->options["language"])) return $this->initialize()->get("items_".$this->options["language"]);
        $data = $this->get(Str::Replace("http://DDragon.leagueoflegends.com/cdn/:version/data/:language/item.json", [
            'version'    => $this->version,
            'language'  => $this->options["language"]
        ]));
        $this->initialize()->set("items_".$this->options["language"], json_decode(json_encode($data)), $this->options["cache"]["DDragon"]["items"]);
        return (object) json_decode(json_encode($data));
    }

    /**
     * @param int $id
     * @return false|mixed|object
     * @throws \ReflectionException
     */
    public function getItem(int $id)
    {
        if ($this->initialize()->has("items_".$this->options["language"])) {
            foreach ($this->initialize()->get("items_".$this->options["language"])->data as $item => $data) {
                if (strtolower($item) == $id) return $data;
            }
            return false;
        }
        else return $this->getItems();
    }

    /**
     * @param int $id
     * @return string
     */
    public function getItemIcon(int $id): string
    {
        return (string) Str::Replace("http://ddragon.leagueoflegends.com/cdn/:version/img/item/:id.png", [
            'version'   => $this->version,
            'id'        => $id
        ]);
    }
}