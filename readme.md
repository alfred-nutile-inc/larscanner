# Misc Tools to Help Scan Laravel for Security Issues


[![Latest Version on Packagist][ico-version]][link-packagist]
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]
[![Software License][ico-license]](LICENSE.md)

Tons more to do this just one tool


## Installation

`composer require alfred-nutile-inc/larscanner:dev-master`

Add to `config/app.php`

~~~
 AlfredNutileInc\LarScanner\Providers\LarScannerProvider::class
~~~

## SensioLabs Composer Checker

by [https://github.com/sensiolabs/security-checker](https://github.com/sensiolabs/security-checker)

Make sure to add to your env

```
SECURITY_NOTICE_SLACK_URL=https://room_to_slack
```

Then add to `app/Console/Kernel.php`

```
        $schedule->command('larscanner:sensio')->daily()
        ->appendOutputTo('/tmp/security_issues.log')
        ->emailOutputTo('some@email.com');
```

The output is optional. By default it will send it to slack.

You can turn slack off if needed by (todo)

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

### TODO

* Allow slack to be turned off

## Roadmap

* Can we scan our code? Something like http://brakemanscanner.org/

* What other well known libraries are there?

* Some good links [phparch nov 2016](https://www.phparch.com/2016/11/november-2016-moving-forward/) good article with links to a number of services and php tools

* can we find laravel vulnerabilities and scan our site nightly

* use behat to try and break into our sites?


[ico-version]: https://img.shields.io/packagist/v/alfred-nutile-inc/larscanner.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/alfred-nutile-inc/larscanner/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/alfred-nutile-inc/larscanner.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/alfred-nutile-inc/larscanner.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/alfred-nutile-inc/larscanner.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/alfred-nutile-inc/larscanner
[link-travis]: https://travis-ci.org/alfred-nutile-inc/larscanner
[link-scrutinizer]: https://scrutinizer-ci.com/g/alfred-nutile-inc/larscanner/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/alfred-nutile-inc/larscanner
[link-downloads]: https://packagist.org/packages/alfred-nutile-inc/larscanner
[link-author]: https://github.com/alnutile
[link-contributors]: ../../contributors
