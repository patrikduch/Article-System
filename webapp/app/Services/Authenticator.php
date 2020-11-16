<?php

namespace App\Services;

use Nette;

/**
 * Authentication helper service.
 * @package App\Services
 */
final class Authenticator implements Nette\Security\IAuthenticator
{
    private $database;
    private $passwords;

    /**
     * Authenticator constructor.
     * @param Nette\Database\Context $database
     * @param Nette\Security\Passwords $passwords
     */
    public function __construct(Nette\Database\Context $database, Nette\Security\Passwords $passwords)
    {
        $this->database = $database;
        $this->passwords = $passwords;
    }

    /**
     * Authentication of provided user.
     * @param array $credentials
     * @return Nette\Security\IIdentity
     * @throws Nette\Security\AuthenticationException
     */
    public function authenticate(array $credentials): Nette\Security\IIdentity
    {
        [$username, $password] = $credentials;

        $row = $this->database->table('User')
            ->where('username', $username)
            ->fetch();

        if (!$row) {
            throw new Nette\Security\AuthenticationException('User not found.');
        }

        if (!$this->passwords->verify($password, $row->password)) {
            throw new Nette\Security\AuthenticationException('Invalid password.');
        }

        return new Nette\Security\Identity(
            $row->id,
            ['name' => $row->username]
        );
    }
}