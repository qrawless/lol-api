<?php



namespace Qrawless\Lol;

use Exception;
use Qrawless\Lol\Helpers\Str;
use Qrawless\Lol\Models\MatchList;
use Qrawless\Lol\Models\Summoner;
use Qrawless\Lol\Models\League;
use Qrawless\Lol\Models\Mastery;
use Qrawless\Lol\Models\CommunityDragon;
use Qrawless\Lol\Models\DDragon;
use Qrawless\Lol\Models\Live;
use Qrawless\Lol\Traits\Cache;
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
 * @property Live $live
 * @property Model $model
 * @package Qrawless\Lol
 */
class Lol
{
    use Cache;

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
    public array $options = [
        'api_key'   => null,
        'region'    => "EUROPE_WEST",
        'language'  => "en_US",
        'curl'      => [],
        'servers'   => null,
        'regions'   => null,
        'cache' => [
            "championRotations"     => 3600,
            "live"                  => 300,
            "DDragon"   => [
                "versions"          => 3600,
                "languages"         => 3600,
                "items"             => 3600,
                "champions"         => 3600,
                "summoners"         => 3600,
                "summoner"          => 300,
                "matchList"         => 300,
                "match"             => 10080,
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
            'live'              => new Live($this->options),
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
            'live'              => new Live($this->options),
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
     * @return mixed|object|null
     */
    public function championRotations()
    {
        if ($this->initialize()->has($this->options["servers"][$this->options["region"]]."_championRotations")) return $this->initialize()->get($this->options["servers"][$this->options["region"]]."_championRotations");
        $data = $this->model->get(Str::Replace($this->model->api_url . $this->model->endpoints["championRotations"], [
            'server'    => $this->options["servers"][$this->options["region"]],
        ]), ["api_key"  => $this->model->api_key]);
        $this->initialize()->set($this->options["servers"][$this->options["region"]]."_championRotations", json_decode(json_encode($data)), $this->options["cache"]["championRotations"]);
        return (object) json_decode(json_encode($data));
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
        $live       = $this->live->check($summonerId, true);

        $data = $this->models["model"]->multiGet([
            [$summoner, ["api_key"  => $this->models["model"]->api_key]],
            [$league, ["api_key"    => $this->models["model"]->api_key]],
            [$matchList, ["api_key" => $this->models["model"]->api_key]],
            [$masterys, ["api_key"  => $this->models["model"]->api_key]]
        ]);

        foreach ($data[1] as $key => $value) { $l[$value->queueType] = $value; }


        $summoner = $data[0];
        $summoner->revisionDate = $this->timestamp($summoner->revisionDate);
        $summoner->live         = $live;
        $summoner->stats        = null;
        $summoner->League       = $l;
        $summoner->Lanes        = $this->matchList->Lanes($data[2]->matches);
        $summoner->totalGames   = $data[2]->totalGames;


        if (empty($data[2]->status->status_code)){
            $games                                  = $this->matchList->matchURLGen($data[2]->matches);
            $kills                                  = 0;
            $assists                                = 0;
            $deaths                                 = 0;
            $win                                    = 0;

            $doubleKills                            = 0;
            $firstBloodKill                         = 0;
            $firstBloodAssist                       = 0;

            $gameDuration                           = 0;
            $minMinion                              = 0;

            $totalMinionsKilled                     = 0;
            $neutralMinionsKilled                   = 0;
            $neutralMinionsKilledTeamJungle         = 0;
            $neutralMinionsKilledEnemyJungle        = 0;


//            if (count($games) === 100) {
//                $page2 = $this->matchList->matchURLGen($this->matchList->accountId($accountId, ["beginIndex" => 100, "queue" => ["450","400", "440", "420", "700", "1020", "900"]])->matches);
//            }

//            die();
            if ($games){
//                $champions = [];
                $totalGames = count($games);
                foreach ($games as $game) {

                    $game_duration      = $game->gameDuration;
                    $game_queueId       = $game->queueId;
                    $game_platformId    = $game->platformId;

                    foreach ($game->participants as $participants) {
//                        dd([$participants->summonerId, $summoner->id]);
                        if ($summoner->id === $participants->summonerId){

                            $kills                                  += $participants->stats->kills;
                            $deaths                                 += $participants->stats->deaths;
                            $assists                                += $participants->stats->assists;

                            $win                                    += $participants->stats->win;

                            $doubleKills                            += $participants->stats->doubleKills;
                            $firstBloodKill                         += $participants->stats->firstBloodKill;
                            $firstBloodAssist                       += $participants->stats->firstBloodAssist;


                            $game_duration_m                        = number_format(($game_duration / 60));
                            $minMinion                              += ($game_duration_m * 6.3);
                            $gameDuration                           += $game_duration;

//                            echo "\n\n\n";
//                            echo ($game_duration_m * 9.5);
//                            echo "\n\n\n";

                            $totalMinionsKilled                     += @$participants->stats->totalMinionsKilled;
                            $neutralMinionsKilled                   += @$participants->stats->neutralMinionsKilled;
                            $neutralMinionsKilledTeamJungle         += @$participants->stats->neutralMinionsKilledTeamJungle;
                            $neutralMinionsKilledEnemyJungle        += @$participants->stats->neutralMinionsKilledEnemyJungle;

                        }
                    }
                }

                $kda = round(($kills+$assists) / $deaths,2);
                if ($totalGames === 200) $summoner->stats["match"] = "+".$totalGames;
                else $summoner->stats["match"]  = $totalGames;
                $summoner->stats["winRate"]     = round(($win / $totalGames) * 100,2);
                $summoner->stats["kda"]         = $kda;

                $summoner->stats["doubleKills"] = $doubleKills;
                $summoner->stats["firstBloodKill"] = $firstBloodKill;
                $summoner->stats["firstBloodAssist"] = $firstBloodAssist;

                $summoner->stats["totalMinionsKilled"] = $totalMinionsKilled;

                $summoner->stats["gameDuration"] = $gameDuration;
                $summoner->stats["minMinion"] = $minMinion;
                $summoner->stats["neutralMinionsKilled"] = $neutralMinionsKilled;
                $summoner->stats["neutralMinionsKilledTeamJungle"] = $neutralMinionsKilledTeamJungle;
                $summoner->stats["neutralMinionsKilledEnemyJungle"] = $neutralMinionsKilledEnemyJungle;
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

    /**
     * @param $string
     * @return false|int|string
     */
    public function server($string)
    {
        foreach ($this->options["servers"] as $key => $value){
            if (strtolower($key) == strtolower($string))    return $key;
            if (strtolower($value) == strtolower($string))  return $key;
        }
        return false;
    }
}