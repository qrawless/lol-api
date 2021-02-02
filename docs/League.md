## League API Example:

### Summoner League:
```php
//  this fetches the summoner league data.
$league = $lol->league->bySummoner("6y5LHn5zTYj5XcMtZ3g4UqnE1XXHcOTi_Gy3Vxl4vfZPoN4");

// Get Ranked Flex data
$league->RANKED_FLEX_SR->tier;             //  BRONZE
$league->RANKED_FLEX_SR->rank;             //  II
$league->RANKED_FLEX_SR->leaguePoints;     //  64
$league->RANKED_FLEX_SR->wins;             //  20
$league->RANKED_FLEX_SR->losses;           //  18

// Get Ranked Solo data
$league->RANKED_SOLO_5x5->tier;            //  SILVER
$league->RANKED_SOLO_5x5->rank;            //  II
$league->RANKED_SOLO_5x5->leaguePoints;    //  15
$league->RANKED_SOLO_5x5->wins;            //  102
$league->RANKED_SOLO_5x5->losses;          //  99

print_r($league);  //  Or all data.

/* stdClass Object
(
    [RANKED_FLEX_SR] => stdClass Object
        (
            [leagueId] => e131e04d-0c33-4d66-8184-437cb14b3273
            [queueType] => RANKED_FLEX_SR
            [tier] => BRONZE
            [rank] => I
            [summonerId] => 6y5LHn5zTYj5XcMtZ3g4UqnE1XXHcOTi_Gy3Vxl4vfZPoN4
            [summonerName] => QRÁWLÈSS
            [leaguePoints] => 100
            [wins] => 30
            [losses] => 23
            [veteran] => 
            [inactive] => 
            [freshBlood] => 
            [hotStreak] => 
            [miniSeries] => stdClass Object
                (
                    [target] => 3
                    [wins] => 1
                    [losses] => 1
                    [progress] => WLNNN
                )

        )

    [RANKED_SOLO_5x5] => stdClass Object
        (
            [leagueId] => 6de0cdfc-3acc-41bd-a692-d15ed22aa502
            [queueType] => RANKED_SOLO_5x5
            [tier] => SILVER
            [rank] => II
            [summonerId] => 6y5LHn5zTYj5XcMtZ3g4UqnE1XXHcOTi_Gy3Vxl4vfZPoN4
            [summonerName] => QRÁWLÈSS
            [leaguePoints] => 15
            [wins] => 102
            [losses] => 99
            [veteran] => 
            [inactive] => 
            [freshBlood] => 
            [hotStreak] => 
        )

)
*/
```
### Challenger League:

`RANKED_FLEX_SR` `RANKED_SOLO_5x5`

```php
$challengerLeague = $lol->league->challengerLeague("RANKED_FLEX_SR");
// OR
$challengerLeague = $lol->league->challengerLeague("RANKED_SOLO_5x5");


$challengerLeague->tier;       //  CHALLENGER
$challengerLeague->leagueId;   //  24ee20c9-9b25...
$challengerLeague->queue;      //  RANKED_FLEX_SR
$challengerLeague->name;       //  Graves's Dragoons

// OR

foreach ($challengerLeague->entries as $summonerData) {
    $summonerData->summonerName;    //  Caste
    $summonerData->leaguePoints;    //  854
    $summonerData->rank;            //  I
    $summonerData->wins;            //  224
    $summonerData->losses;          //  107
    // OR
    print_r($summonerData); // print summoner data.
}

// OR

print_r($challengerLeague);
/* stdClass Object
(
    [tier] => CHALLENGER
    [leagueId] => 24ee20c9-9b25-35be-bd6a-22fcac3d1b0c
    [queue] => RANKED_FLEX_SR
    [name] => Graves's Dragoons
    [entries] => Array
        (
            [0] => stdClass Object
                (
                    [summonerId] => QpHFpbco4pAI3OARJ6UH_ucAXBuxS7jQuBMA9tA1PQUFVQ
                    [summonerName] => Caste
                    [leaguePoints] => 854
                    [rank] => I
                    [wins] => 224
                    [losses] => 107
                    [veteran] => 1
                    [inactive] => 
                    [freshBlood] => 
                    [hotStreak] => 
                )
            [...]    
        )
)
*/
```