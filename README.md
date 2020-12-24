# lol
a simple Lol API.

install
------------------------
```
composer require qrawless/lol
```
Initializing the library
------------------------
```php
require_once __DIR__.'/vendor/autoload.php';

$lol = new \Qrawless\Lol\Lol([
    "api_key"   => "API_KEY",
    "region"    => "REGION_KEY", // tr, euw, kr.. and so on
    "curl"      => [ "verify" => false /* Disable SSL verify (optional) */ ]
]);
```
Usage example
-------------

```php
//  ...initialization...

//  this fetches the summoner data.
$summoner = $lol->summoner->byName("TT ØRÁWLÈSS");

echo $summoner->id;             //  6y5LHn5zTYj5XcMt...
echo $summoner->puuid;          //  rEakbi8xJ7sQ92v8...
echo $summoner->name;           //  TT ØRÁWLÈSS
echo $summoner->summonerLevel;  //  166

print_r($summoner);  //  Or all data.

/* stdClass Object
(
    [id] => 6y5LHn5zTYj5XcMtZ3g4UqnE1XXHcOTi_Gy3Vxl4vfZPoN4
    [accountId] => fDO7GzXa-BWWJLlQFk39PdC6fevQnIgwnZMvfVNlJ-TnUNU
    [puuid] => rEakbi8xJ7sQ92v8drW2L5Oyi94fy9Mxnd3QHlPbkcQsOsGrb7h_zObRybzYx0TwdaVoIt9wmKG6Zg
    [name] => TT ØRÁWLÈSS
    [profileIconId] => 787
    [revisionDate] => 1608321937000
    [summonerLevel] => 166
)
*/
```
###League:
```php
//  this fetches the summoner league data.
$league = $lol->league->bySummoner("6y5LHn5zTYj5XcMtZ3g4UqnE1XXHcOTi_Gy3Vxl4vfZPoN4");

// Get Ranked Flex data
echo $league->RANKED_FLEX_SR->tier;             //  BRONZE
echo $league->RANKED_FLEX_SR->rank;             //  II
echo $league->RANKED_FLEX_SR->leaguePoints;     //  64
echo $league->RANKED_FLEX_SR->wins;             //  20
echo $league->RANKED_FLEX_SR->losses;           //  18

// Get Ranked Solo data
echo $league->RANKED_SOLO_5x5->tier;            //  SILVER
echo $league->RANKED_SOLO_5x5->rank;            //  II
echo $league->RANKED_SOLO_5x5->leaguePoints;    //  15
echo $league->RANKED_SOLO_5x5->wins;            //  102
echo $league->RANKED_SOLO_5x5->losses;          //  99

print_r($league);  //  Or all data.

/* stdClass Object
(
    [RANKED_SOLO_5x5] => stdClass Object
        (
            [leagueId] => 6de0cdfc-3acc-41bd-a692-d15ed22aa502
            [queueType] => RANKED_SOLO_5x5
            [tier] => SILVER
            [rank] => II
            [summonerId] => 6y5LHn5zTYj5XcMtZ3g4UqnE1XXHcOTi_Gy3Vxl4vfZPoN4
            [summonerName] => TT ØRÁWLÈSS
            [leaguePoints] => 15
            [wins] => 102
            [losses] => 99
            [veteran] => 
            [inactive] => 
            [freshBlood] => 
            [hotStreak] => 
        )
    [RANKED_FLEX_SR] => stdClass Object
        (
            [leagueId] => e131e04d-0c33-4d66-8184-437cb14b3273
            [queueType] => RANKED_FLEX_SR
            [tier] => BRONZE
            [rank] => II
            [summonerId] => 6y5LHn5zTYj5XcMtZ3g4UqnE1XXHcOTi_Gy3Vxl4vfZPoN4
            [summonerName] => TT ØRÁWLÈSS
            [leaguePoints] => 64
            [wins] => 20
            [losses] => 18
            [veteran] => 
            [inactive] => 
            [freshBlood] => 
            [hotStreak] => 
        )
)
*/
```
