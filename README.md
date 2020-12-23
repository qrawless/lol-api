# lol
a simple Lol API.

usage
---
```php
$lol = new \Qrawless\Lol\Lol([
    "api_key"   => "API_KEY",
    "region"    => "REGION_KEY",
    "curl"      => [ "verify" => false /* Disable SSL verify (optional) */ ]
]);
```

Get summoner data `byName`, `byId`, `byAccountId`, `byPuuid`
```php
$lol->summoner->byName("TT ØRÁWLÈSS");
```
output:
```json
stdClass Object
(
    [id] => 6y5LHn5zTYj5XcMtZ3g4UqnE1XXHcOTi_Gy3Vxl4vfZPoN4
    [accountId] => fDO7GzXa-BWWJLlQFk39PdC6fevQnIgwnZMvfVNlJ-TnUNU
    [puuid] => rEakbi8xJ7sQ92v8drW2L5Oyi94fy9Mxnd3QHlPbkcQsOsGrb7h_zObRybzYx0TwdaVoIt9wmKG6Zg
    [name] => TT ØRÁWLÈSS
    [profileIconId] => 787
    [revisionDate] => 1608321937000
    [summonerLevel] => 165
)
```

Get Summoner League.

```php
$data   = $lol->summoner->byName("TT ØRÁWLÈSS");
$league = $lol->league->getId($data->id);
```
output:
```json
stdClass Object
(
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

)
```



Get Challanger League.

`RANKED_FLEX_SR`, `RANKED_SOLO_5x5`

```php
$lol->league->challangerLeague("RANKED_SOLO_5x5");
```
