<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController
{

    /**
     * @Route("/login_check", name="app_login", methods={"POST"})
     */
    public function login(Request $request)
    {
    }



}