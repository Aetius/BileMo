<?php


namespace App\Tests\Services;


use App\Controller\UserController;

trait UserService
{
    public function defineNumberOfUsersByCustomer(Array $users)
    {
        if (UserController::LIMIT_USER_PER_PAGE < count($users)){
            return UserController::LIMIT_USER_PER_PAGE;
        }
        return count($users);
    }

}