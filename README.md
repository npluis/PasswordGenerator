# PasswordGenerator
Simple Password Generator for PHP

Requirements
---------------
* PHP 5.4

Installation
---------------
`composer require npluis/passwordgenerator`

Usage
---------------

####basic usage
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


