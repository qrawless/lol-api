## Mastery API Example:

```php
//  this fetches the summoner league data.
$mastery = $lol->mastery->bySummoner("6y5LHn5zTYj5XcMtZ3g4UqnE1XXHcOTi_Gy3Vxl4vfZPoN4");

$mastery->{0}->championId;         //  17
$mastery->{0}->championLevel;      //  7
$mastery->{0}->championPoints;     //  596085
$mastery->{0}->chestGranted;       //  1

// basic usage DataDragon API.
$lol->DDragon->getChampionById($mastery->{0}->championId); // Get champion data.

foreach ($mastery as $data) {
    $data->championId;         //  17
    $data->championLevel;      //  7
    $data->championPoints;     //  596085
    $data->chestGranted;       //  1
    
    // $lol->DDragon->getChampionById($data->championId);  // Get champion data.
}

print_r($mastery);  //  Or all data.
/* stdClass Object
(
    [0] => stdClass Object
        (
            [championId] => 17
            [championLevel] => 7
            [championPoints] => 596085
            [lastPlayTime] => 1608752530000
            [championPointsSinceLastLevel] => 574485
            [championPointsUntilNextLevel] => 0
            [chestGranted] => 1
            [tokensEarned] => 0
            [summonerId] => 6y5LHn5zTYj5XcMtZ3g4UqnE1XXHcOTi_Gy3Vxl4vfZPoN4
        )
    [...]
)
*/
```