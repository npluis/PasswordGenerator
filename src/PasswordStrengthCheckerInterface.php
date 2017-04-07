<?php
/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 7-4-17
 * Time: 23:06
 */
namespace Npluis\PasswordGenerator;

interface PasswordStrengthCheckerInterface
{
    /**
     * @param int $minLength
     */
    public function setMinLength(int $minLength);

    /**
     * @param int $num
     *
     * @return $this
     */
    public function setNeedNumbers(int $num);

    /**
     * @param string $name
     * @param string $regex
     * @param int    $num
     *
     * @return $this
     */
    public function setFilter(string $name, string $regex, int $num);

    /**
     * @param int $num
     *
     * @return $this
     */
    public function setNeedUpperCase(int $num);

    /**
     * @param int $num
     *
     * @return $this
     */
    public function setNeedLowerCase(int $num);

    /**
     * set minimum for unique characters in password
     *
     * @param int $length
     *
     * @return $this
     */
    public function setMinUniqueChars(int $length);

    /**
     * check if password contains the right chars
     *
     * @param $password
     *
     * @return bool
     */
    public function checkPassword($password);

    /**
     * add a password to the blacklist
     *
     * @param $password
     */
    public function addToBlacklist(string $password);

    public function addArrayToBlacklist(array $wordList);
}
