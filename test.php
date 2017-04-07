<?php
/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 7-4-17
 * Time: 22:06
 */

require 'vendor/autoload.php';

$generator = new \Npluis\PasswordGenerator\PasswordGenerator();
echo $generator->create() ."\n";

$generator->setAllowSpecial(false);

$password = $generator->setAllowBrackets(false)->setAllowSpecial(false)->create();

echo $password."\n";

$checker = new \Npluis\PasswordGenerator\PasswordStrengthChecker();
$checker->setMinLength(10)->checkPassword('tooshort'); //LENGTH
$checker->setMinLength(3)->checkPassword('tooshort'); //PASSWORD_OK


$checker->addToBlacklist('blacklist');
$checker->checkPassword('123blacklist'); //BLACKLIST_WORD
$checker->checkPassword('BlackList'); //BLACKLIST_WORD

$checker->setMinUniqueChars(3);
$checker->checkPassword('111Aa'); //UNIQUE_CHARS only two unique lowercase chars a and 1







