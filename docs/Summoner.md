## Summoner API Example:
`byId`
`byName`
`byAccountId`
`byPuuid`
```php
//  this fetches the summoner data.
$summoner = $lol->summoner->byName("QRÁWLÈSS");

$summoner->id;             //  6y5LHn5zTYj5XcMt...
$summoner->puuid;          //  rEakbi8xJ7sQ92v8...
$summoner->name;           //  QRÁWLÈSS
$summoner->summonerLevel;  //  166

print_r($summoner);  //  Or all data.

/* stdClass Object
(
    [id] => 6y5LHn5zTYj5XcMtZ3g4UqnE1XXHcOTi_Gy3Vxl4vfZPoN4
    [accountId] => fDO7GzXa-BWWJLlQFk39PdC6fevQnIgwnZMvfVNlJ-TnUNU
    [puuid] => rEakbi8xJ7sQ92v8drW2L5Oyi94fy9Mxnd3QHlPbkcQsOsGrb7h_zObRybzYx0TwdaVoIt9wmKG6Zg
    [name] => QRÁWLÈSS
    [profileIconId] => 787
    [revisionDate] => 1608321937000
    [summonerLevel] => 166
)
*/
```
### Search for username on all servers.
```php
//  this fetches the summoner data.
$summoner = $lol->summoner->checkAllServers("QRÁWLÈSS");

print_r($summoner);  //  Or all data.

/*
Array
(
    [na1] => {...}
    [euw1] => {...}
    [eun1] => {...}
    [la2] => {...}
    [la1] => {...}
    [br1] => {...}
    [ru] => {...}
    [tr1] => {"id":"...","accountId":"...","name":"QRÁWLÈSS","profileIconId":787,"summonerLevel":173}
    [oc1] => {...}
    [kr] => {...}
    [jp1] => {...}
)
*/
```