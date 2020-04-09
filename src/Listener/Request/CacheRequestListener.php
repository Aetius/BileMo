<?php


namespace App\Listener\Request;


use App\Cache\CacheKernel;
use App\Kernel;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class CacheRequestListener
{


    public function onKernelRequest(RequestEvent $event)
    {

        if ($event->getRequest()->getMethod() !== "GET") {
            return;
        }


    }

}