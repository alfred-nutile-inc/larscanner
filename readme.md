# Misc Tools to Help Scan Laravel for Security Issues

## Installation

Add to `config/app.php`

~~~
 AlfredNutileInc\LarScanner\Providers\LarScannerProvider::class
~~~

## SensioLabs Comoser Checker

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


### TODO
Run nightly
Send as slack and or email
Allow slack to be turned off



## Roadmap

Can we scan our code?

What other well known libraries are there?

Some good links [phparch nov 2016](https://www.phparch.com/2016/11/november-2016-moving-forward/)

can we find laravel vulnerabilities and scan our site nightly

use behat to try and break into our sites?
