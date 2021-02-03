# QRAWLESS / LOL-API

## Documentation
* [Summoner API](./docs/Summoner.md)
* [League API](./docs/League.md)
* [Masterys API](./docs/Masterys.md)
* [Masterys API](./docs/MatchList.md)
  

* [DataDragon API](./docs/DDragon.md)


install
------------------------
```
composer require qrawless/lol-api:dev-master
```
Initializing the library
------------------------
```php
require_once __DIR__.'/vendor/autoload.php';

$lol = new \Qrawless\Lol\Lol([
    "api_key"   => "API_KEY",    // Riot api key (required*)
    "region"    => "REGION_KEY", // TURKEY, EUROPE_WEST, EUROPE_EAST..  (default: EUROPE_WEST)
    "language"  => "tr_TR",      // tr_TR, en_US, ...                   (default: en_US)
    "curl"      => [ "verify" => false ] /* Disable SSL verify            (default: true) */
]);
```