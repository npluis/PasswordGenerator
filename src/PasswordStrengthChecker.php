<?php
/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 7-4-17
 * Time: 23:02
 */

namespace Npluis\PasswordGenerator;

class PasswordStrengthChecker implements PasswordStrengthCheckerInterface
{

    const LENGTH = 2;
    const UPPERCASE = 4;
    const LOWERCASE = 8;
    const NUMBERS = 16;
    const UNIQUE_CHARS = 32;
    const SYMBOLS = 64;
    const BLACKLIST_WORD = 128;

    const PASSWORD_OK = 1;

    /**
     * @var int min unique chars (prevent weak passwords like aA1)
     */
    private $minUniqueChars = 4;

    private $minLength = 6;
    /**
     * @var array blacklist words
     */
    private $blacklist = [
        'password',
        'qwerty',
    ];
    /**
     * @var array for regex checks
     */
    private $checks = [];

    /**
     * @param int $minLength
     *
     * @return $this
     */
    public function setMinLength(int $minLength)
    {
        $this->minLength = $minLength;

        return $this;
    }

    /**
     * @param int $num
     *
     * @return $this
     */
    public function setNeedNumbers(int $num)
    {
        $this->setFilter(static::NUMBERS, '[^0-9]', $num);

        return $this;
    }

    /**
     * @param string $name
     * @param string $regex
     * @param int    $num
     *
     * @return $this
     */
    public function setFilter(string $name, string $regex, int $num)
    {
        if ($num > 0) {
            $this->checks[$name] = [$regex, $num];
        } else {
            unset($this->checks[$name]);
        }

        return $this;
    }

    /**
     * @param int $num
     *
     * @return $this
     */
    public function setNeedUpperCase(int $num)
    {
        $this->setFilter(static::UPPERCASE, '[^A-Z]', $num);

        return $this;
    }

    /**
     * @param int $num
     *
     * @return $this
     */
    public function setNeedLowerCase(int $num)
    {
        $this->setFilter(static::LOWERCASE, '[^a-z]', $num);

        return $this;
    }

    /**
     * set minimum for unique characters in password
     *
     * @param int $length
     *
     * @return $this
     */
    public function setMinUniqueChars(int $length)
    {
        $this->minUniqueChars = $length;

        return $this;
    }

    /**
     * check if password contains the right chars
     *
     * @param $password
     *
     * @return bool
     */
    public function checkPassword($password)
    {

        if (strlen($password) < $this->minLength) {
            return static::LENGTH;
        }
        $passwordLower = strtolower($password);


        if (count(count_chars($passwordLower, 1)) < $this->minUniqueChars) {
            return static::UNIQUE_CHARS;
        }


        foreach ($this->blacklist as $blackword) {
            if (strpos($passwordLower, $blackword) !== false) {
                return static::BLACKLIST_WORD;
            }
        }
        foreach ($this->checks as $key => $check) {
            $tempPW = preg_replace('/'.$check[0].'/', '', $password);
            if (strlen($tempPW) < $check[1]) {
                return $key;
            }
        }

        return static::PASSWORD_OK;
    }

    /**
     * add a password to the blacklist
     *
     * @param $password
     */
    public function addToBlacklist(string $password)
    {
        $this->addToList($password);
        $this->makeBlacklistUnique();
    }

    private function addToList($password)
    {
        $this->blacklist[] = strtolower($password);
    }

    private function makeBlacklistUnique()
    {
        $this->blacklist = array_unique($this->blacklist);
    }

    public function addArrayToBlacklist(array $wordList)
    {
        foreach ($wordList as $password) {
            $this->addToList($password);
        }
        $this->makeBlacklistUnique();
    }
}
