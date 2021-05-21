<?php


namespace Qrawless\Lol\Models;


use Qrawless\Lol\Helpers\Str;
use Qrawless\Lol\Traits\Cache;
use Qrawless\Lol\Traits\Model;

class CommunityDragon extends Model
{
    use Cache;

    /**
     * @var array
     */
    public array $options = [];

    /**
     * @var string
     */
    protected string $dragon_url = "https://cdn.communitydragon.org/:version";

    /**
     * @var string
     */
    public string $version = "latest";

    /**
     * @var array|string[]
     */
    protected array $endpoints = [
        "profile-icon" => "/profile-icon/:profileIconId",
        "champion-data-key" => "https://raw.communitydragon.org/:version/plugins/rcp-be-lol-game-data/global/:language/v1/champions/:championId.json",
    ];

    /**
     * CommunityDragon constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        parent::__construct($options);
        $this->options = $options;
    }

    /**
     * Get Summoner profile picture
     *
     * @param int $profileIconId
     * @return string
     */
    public function profileIconId(int $profileIconId = 0): string
    {
        return Str::Replace($this->dragon_url.$this->endpoints["profile-icon"], [
            'version'       => $this->version,
            'profileIconId' => $profileIconId
        ]);
    }

    public function getChampionDataByKey(int $championId, $url = false)
    {
        $url = Str::Replace($this->endpoints["champion-data-key"], [
            'version'       => $this->version,
            'language'      => "tr_tr",
            'championId'    => $championId
        ]);
        if ($url) return $url;
        return json_decode(file_get_contents($url));
    }
}