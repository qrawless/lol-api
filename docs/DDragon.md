## Data Dragon API

Get Champion data:
```php
// Get all champions data.
$lol->dDragon->getChampions();

// Get champion data by id.
$lol->dDragon->getChampionById(17);         // Teemo <3
$lol->dDragon->getChampionByKey("teemo");   // 17 <3
```

Get Summoners Profile icon URL:
```php
$lol->dDragon->profileIcon(787);            // Teemo <3
```
<img src="http://ddragon.leagueoflegends.com/cdn/10.25.1/img/profileicon/787.png" width="65">


Get Summoner Profile icon URL:
```php
$lol->dDragon->summonerIconByKey(4);        // Flash
```
![SummonerBarrier](http://ddragon.leagueoflegends.com/cdn/10.25.1/img/spell/SummonerBarrier.png)
![SummonerBoost](http://ddragon.leagueoflegends.com/cdn/10.25.1/img/spell/SummonerBoost.png)
![SummonerDot](http://ddragon.leagueoflegends.com/cdn/10.25.1/img/spell/SummonerDot.png)
![SummonerExhaust](http://ddragon.leagueoflegends.com/cdn/10.25.1/img/spell/SummonerExhaust.png)
![SummonerFlash](http://ddragon.leagueoflegends.com/cdn/10.25.1/img/spell/SummonerFlash.png)
![SummonerHaste](http://ddragon.leagueoflegends.com/cdn/10.25.1/img/spell/SummonerHaste.png)
![SummonerHeal](http://ddragon.leagueoflegends.com/cdn/10.25.1/img/spell/SummonerHeal.png)
![SummonerMana](http://ddragon.leagueoflegends.com/cdn/10.25.1/img/spell/SummonerMana.png)
![SummonerSmite](http://ddragon.leagueoflegends.com/cdn/10.25.1/img/spell/SummonerSmite.png)
![SummonerSnowball](http://ddragon.leagueoflegends.com/cdn/10.25.1/img/spell/SummonerSnowball.png)
![SummonerTeleport](http://ddragon.leagueoflegends.com/cdn/10.25.1/img/spell/SummonerTeleport.png)