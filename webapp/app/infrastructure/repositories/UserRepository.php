<?php

namespace App\Infrastructure\Repositories;

use App\Core\Interfaces\Repositories\IUserRepository;
use App\Services\PasswordEncrypter;
use Nette;

/**
 * Class UserRepository
 * @package App\Infrastructure\Repositories
 */
final class UserRepository implements IUserRepository {

    private $database;
    private $passwordEncrypter;

    /**
     * UserRepository constructor.
     * @param Nette\Database\Context $database
     * @param PasswordEncrypter $passwordEncrypter
     */
    public function __construct(Nette\Database\Context $database, PasswordEncrypter $passwordEncrypter)
    {
        $this->database = $database;
        $this->passwordEncrypter = $passwordEncrypter;
    }

    /**
     * Registration of new user.
     * @param $username
     * @param $password
     */
    public function signUpUser($username, $password) {

        $passwordHash = $this->passwordEncrypter->encryptPassword($password);

        if ($passwordHash) {
            $this->database->query(
                "INSERT INTO User (username, password)
                    VALUES ('$username', '$passwordHash');");
        }
    }

    /**
     * Change user password based on its id and passed new password.
     * @param $userId $userId User identifier (Primary key from DBMS)
     * @param $newPassword $newPassword New password that will be assigned to specific user.
     */
    public function changeUserPassword($userId, $newPassword) {

        if (strlen($newPassword) == 0) return;

        $entity = $this->getUser($userId);

        if(isset($entity)) {
            $this->database->query("UPDATE User SET password = '$newPassword' WHERE ID = $userId");
        }
    }

    /**
     * Get single user by its identifier.
     * @param $userId $userId Identifier of target user.
     */
    public function getUser($userId)
    {
        $entity = $this->database->table('User')->get($userId);

        if (isset($entity)) {
            return $entity;
        }
    }
}