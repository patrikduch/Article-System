<?php

namespace App\Services;

use Nette\Security\Passwords;

/**
 * Password encryption service
 * @package App\Services\
 */
final class PasswordEncrypter
{
    /** @var Passwords */
    private $passwords;

    /**
     * PasswordEncrypter constructor.
     * @param Passwords $passwords
     */
    public function __construct(Passwords $passwords)
    {
        $this->passwords = $passwords;
    }

    /***
     * @param $password Password input that will be encrypted.
     * @return string Result of password encryption.
     */
    public function encryptPassword($password) {
        return $this->passwords->hash($password);
    }
}
