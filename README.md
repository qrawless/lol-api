<h1 style="display: inline-flex;line-height: 4rem">
<img style="display: inline-flex;border-radius: 500px; margin-right: 10px;" src="http://ddragon.leagueoflegends.com/cdn/10.25.1/img/profileicon/787.png" width="65">
MESELA..
</h1>

## Documentation
* [Summoner API](./docs/Summoner.md)
* [League API](./docs/League.md)
* [Masterys API](./docs/Masterys.md)
  

* [DataDragon API](./docs/DDragon.md)


install
------------------------
```
composer require qrawless/lol-api (coming soon)
```
Initializing the library
------------------------
```php
require_once __DIR__.'/vendor/autoload.php';

$lol = new \Qrawless\Lol\Lol([
    "api_key"   => "API_KEY",    // Riot api key (required*)
    "region"    => "REGION_KEY", // TURKEY, EUROPE_WEST, EUROPE_EAST..  (default: EUROPE_WEST)
    "language"  => "tr_TR",      // tr_TR, en_US, ...                   (default: en_US)
    "curl"      => [ "verify" => false /* Disable SSL verify            (default: true) */ ]
]);
```