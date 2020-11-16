<?php

namespace App\Core\Interfaces\Repositories;
/**
 * Interface IUserRepository that is contract for repository class UserRepository.
 * @package App\Core\Interfaces
 */
interface IUserRepository
{
    public function  signUpUser($username, $password);
    public function changeUserPassword($userId, $newPassword);
}