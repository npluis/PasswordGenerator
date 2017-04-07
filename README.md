[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.0-brightgreen.svg)](https://php.net/)
[![Build Status](https://travis-ci.org/npluis/PasswordGenerator.svg?branch=master)](https://travis-ci.org/npluis/PasswordGenerator)


# PasswordGenerator
Simple Password Generator for PHP

Requirements
---------------
* PHP 7.0

Installation
---------------
`composer require npluis/passwordgenerator`

Usage Password Generator
---------------

### basic usage

```php
<?php
require 'vendor/autoload.php';


$generator = new \Npluis\PasswordGenerator\PasswordGenerator();
$password = $generator->create();
```

* length between 8 and 10: 
`$generator->create(8,10);`
* fixed length: 
`$generator->create(12,12);`
* only a-zA-Z0-0:
`$generator->setAllowBrackets(false)->setAllowSpecial(false)->create();`


### advanced usage
use your own character set

`$generator->setChars('AbCdEfG@#$%%<>;')->setAllowSpecial(false)->setAllowBrackets(false)`

Usage Password Checker
---------------
To prevent weak passwords from being generated or entered by a user when creating a new one you can use some checks. 

check for blacklist words (like password, admin, qwerty, etc)
```php
$checker->addToBlacklist('blacklist');
$checker->checkPassword('123blacklist'); //BLACKLIST_WORD
$checker->checkPassword('BlackList'); //BLACKLIST_WORD
```

check for weak passwords like 1111 or aaa by specifying the minimum number of unique lower case characters
```php
$checker->setMinUniqueChars(3);
$checker->checkPassword('111Aa'); //UNIQUE_CHARS only two unique lowercase chars a and 1 