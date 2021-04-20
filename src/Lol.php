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
                "items"             => 3600,
                "champions"         => 3600,
                "summoners"         => 3600,
                "summoner"          => 300,
                "matchList"         => 300,
                "match"             => 604800,
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
     * @param $summonerId
     * @param $accountId
     * @return mixed
     */
    public function getProfile($summonerId, $accountId)
    {
        $summoner   = $this->summoner->byId($summonerId, true);
        $league     = $this->league->bySummoner($summonerId, true);
        $matchList  = $this->matchList->accountId($accountId, [
            "queue" => ["450", "400", "440", "420", "700", "1020", "900"]
        ], true);
        $masterys   = $this->mastery->bySummoner($summonerId, true);

        $data = $this->models["model"]->multiGet([
            [$summoner, ["api_key"  => $this->models["model"]->api_key]],
            [$league, ["api_key"    => $this->models["model"]->api_key]],
            [$matchList, ["api_key" => $this->models["model"]->api_key]],
            [$masterys, ["api_key"  => $this->models["model"]->api_key]]
        ]);

        foreach ($data[1] as $key => $value) { $l[$value->queueType] = $value; }


        $summoner = $data[0];
        $summoner->revisionDate = $this->timestamp($summoner->revisionDate);
        $summoner->stats        = null;
        $summoner->League       = $l;
        $summoner->Lanes        = $this->matchList->Lanes($data[2]->matches);
        $summoner->totalGames   = $data[2]->totalGames;


        if (empty($data[2]->status->status_code)){
            $games   = $this->matchList->matchURLGen($data[2]->matches);
            $kills   = 0;
            $assists = 0;
            $deaths  = 0;
            $win     = 0;

            if (count($games) === 100) {
                $page2 = $this->matchList->matchURLGen($this->matchList->accountId($accountId, ["beginIndex" => 100, "queue" => ["450","400", "440", "420", "700", "1020", "900"]])->matches);
            }

//            die();
            if ($games){
//                $champions = [];
                $totalGames = count($games);
                foreach ($games as $game) {
                    foreach ($game->participants as $participants) {
//                        dd([$participants->summonerId, $summoner->id]);
                        if ($summoner->id === $participants->summonerId){

                            $kills   += $participants->stats->kills;
                            $deaths  += $participants->stats->deaths;
                            $assists += $participants->stats->assists;

                            $win     += $participants->stats->win;

//                            if(count($champions) > 2) continue;
//
//                            if ($champions[$participants->championId]) $champions[$participants->championId]["match"] += 1;
//                            else $champions[$participants->championId]["match"] = 1;
//
//                            $champions[$participants->championId]["kills"] += $participants->stats->kills;
//                            $champions[$participants->championId]["deaths"] += $participants->stats->deaths;
//                            $champions[$participants->championId]["assists"] += $participants->stats->assists;
                        }
                    }
                }

                if ($page2){
                    $totalGames += count($page2);
                    foreach ($page2 as $game) {
                        foreach ($game->participants as $participants) {
                            if ($summoner->id === $participants->summonerId){

                                $kills   += $participants->stats->kills;
                                $deaths  += $participants->stats->deaths;
                                $assists += $participants->stats->assists;

                                $win     += $participants->stats->win;

                            }
                        }
                    }
                }


                $kda = round(($kills+$assists) / $deaths,2);
                if ($totalGames === 200) $summoner->stats["match"] = "+".$totalGames;
                else $summoner->stats["match"] = $totalGames;
                $summoner->stats["winRate"]    = round(($win / $totalGames) * 100,2);
                $summoner->stats["kda"]        = $kda;
//                $summoner->stats["champions"]  = $champions;
            }
        }


        if(!empty($data[3])){
            $allchamps = $this->dDragon->getChampions()->data;

            foreach ($data[3] as $c => $datum) {
                if ($c >= 3) break; $mast[$c] = $datum;
                foreach ($allchamps as $id){
                    if ($id->key == $datum->championId){
                        $mast[$c]->name = $id->name;
                    }
                }
            }
            $summoner->Masterys   = $mast;
        }

        return $summoner;
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