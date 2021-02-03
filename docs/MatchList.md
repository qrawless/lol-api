## MatchList API Example:

```php
$summoner   = $lol->summoner->byName("QRÁWLÈSS");

$matchList  = $lol->matchList->accountId($summoner->accountId);

print_r($summoner);
/* stdClass Object
(
    [matches] => Array
        (
            [0] => stdClass Object
                (
                    [platformId] => TR1
                    [gameId] => 1135046497
                    [champion] => 110
                    [queue] => 450
                    [season] => 13
                    [timestamp] => 1612294907710
                    [role] => DUO_SUPPORT
                    [lane] => MID
                )
            [...]
        )

    [startIndex] => 0
    [endIndex] => 100
    [totalGames] => 131
)
*/
```
### Average Lane information from the last 100 matches.
```php
$lanes = $lol->matchList->Lanes($matchList->matches);

/*
Array
(
    [TOP] => 23
    [JUNGLE] => 16
    [MID] => 15
    [BOTTOM] => 5
    [SUPPORT] => 0
)
*/
```