<?php

namespace Npluis\PasswordGenerator;

use Npluis\PasswordGenerator\Exception\WeakPasswordException;
use npluis\PasswordStrengthChecker;

/**
 * Class PasswordGenerator
 * @package npluis\PasswordGenerator
 *
 */
class PasswordGenerator
{

    /**
     * @var PasswordStrengthChecker
     */
    private $passwordChecker;


    /**
     * @var string chars for the password
     */
    private $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    /**
     * @var string special chars for the password
     */
    private $special = '_-~!@#$%^&*?:;';

    /**
     * @var string brackets for the password
     */
    private $brackets = '[]{}()<>';
    /**
     * @var int min required length
     */
    private $minLength = 10;

    private $maxLength = 32;


    /**
     * allow or disallow normal characters in the password
     * @var bool
     */
    private $allowChars = true;

    /**
     * allow or disallow special characters in the password
     * @var bool
     */
    private $allowSpecial = true;

    /**
     * allow or disallow brackets in the password
     * @var bool
     */
    private $allowBrackets = true;

    /**
     * PasswordGenerator constructor.
     *
     * @param PasswordStrengthChecker $passwordChecker
     */
    public function __construct(PasswordStrengthChecker $passwordChecker = null)
    {
        $this->passwordChecker = $passwordChecker;
    }

    /**
     * @param int $maxLength
     */
    public function setMaxLength(int $maxLength)
    {
        $this->maxLength = $maxLength;
    }

    /**
     * @param int|null $minLength
     * @param int|null $maxLength
     *
     * @return string
     * @throws WeakPasswordException
     * @throws \Exception
     */
    public function create(int $minLength = null, int $maxLength = null)
    {

        $minLength = $minLength ?? $this->minLength;
        $maxLength = $maxLength ?? $this->maxLength;

        if ($maxLength < $minLength) {
            throw new WeakPasswordException("Max can't be less than min");
        } elseif ($maxLength > 64) {
            throw new WeakPasswordException("Can't generate password that long");
        } elseif ($minLength < 2) {
            throw new WeakPasswordException("Can't generate password that short, need 2 minimal");
        }

        $checks = 0;
        do {
            $checks++;
            $password = self::randomString($minLength, $maxLength);
            if ($this->passwordChecker) {
                if ($this->passwordChecker->checkPassword($password)) {
                    return $password;
                }
            } else {
                return $password;
            }
        } while ($checks < 100);

        throw new \Exception("Could not generate password");
    }

    /**
     * create randomString
     *
     * @param int $minLength
     * @param int $maxLength
     *
     * @return string
     *
     * @throws \Exception
     */
    private function randomString($minLength = 6, $maxLength = 10)
    {

        $password = '';

        $allChars = $this->getChars().$this->getBrackets().$this->getSpecial();

        if (strlen($allChars) < 15) {
            throw new \Exception(sprintf("Cannot make a secure password with only %d characters", strlen($allChars)));
        }

        if (function_exists('mb_strlen')) {
            $max = mb_strlen($allChars, '8bit') - 1;
        } else {
            $max = strlen($allChars) - 1;
        }

        if (function_exists('random_int')) {
            //PHP 7 has secure random int generator
            $length = random_int($minLength, $maxLength);
            for ($i = 0; $i < $length; ++$i) {
                $password .= $allChars[random_int(0, $max)];
            }
        } else {
            //PHP < 7 so use different method
            $length = mt_rand($minLength, $maxLength);
            for ($i = 0; $i < $length; ++$i) {
                $randomNum = hexdec(bin2hex(openssl_random_pseudo_bytes(4)));
                if (strlen($randomNum) === 0) {
                    $randomNum = mt_rand(1, 1000000);
                }
                $randomNum = ($randomNum % $max);
                $password .= $this->chars[$randomNum];
            }
        }

        return $password;
    }

    /**
     * @return string
     */
    public function getChars(): string
    {
        if ($this->isAllowChars()) {
            return $this->chars;
        } else {
            return '';
        }
    }

    /**
     * @param string $chars
     *
     * @return $this
     */
    public function setChars(string $chars)
    {
        $this->chars = $chars;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isAllowChars(): bool
    {
        return $this->allowChars;
    }

    /**
     * @param boolean $allowChars
     */
    public function setAllowChars(bool $allowChars)
    {
        $this->allowChars = $allowChars;
    }

    /**
     * @return mixed
     */
    public function getBrackets()
    {
        if ($this->isAllowBrackets()) {
            return $this->brackets;
        } else {
            return '';
        }
    }

    /**
     * @param string $brackets
     *
     * @return $this
     */
    public function setBrackets(string $brackets)
    {
        $this->brackets = $brackets;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isAllowBrackets(): bool
    {
        return $this->allowBrackets;
    }

    /**
     * @param boolean $allowBrackets
     *
     * @return $this
     */
    public function setAllowBrackets(bool $allowBrackets)
    {
        $this->allowBrackets = $allowBrackets;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSpecial()
    {
        if ($this->isAllowSpecial()) {
            return $this->special;
        } else {
            return '';
        }
    }

    /**
     * @param string $special
     *
     * @return $this
     */
    public function setSpecial(string $special)
    {
        $this->special = $special;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isAllowSpecial(): bool
    {
        return $this->allowSpecial;
    }

    /**
     * @param boolean $allowSpecial
     *
     * @return $this
     */
    public function setAllowSpecial(bool $allowSpecial)
    {
        $this->allowSpecial = $allowSpecial;

        return $this;
    }

    /**
     * @param int $length
     *
     * @return $this
     */
    public function setMinLength(int $length)
    {
        $this->minLength = $length;

        return $this;
    }
}
