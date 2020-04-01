<?php


namespace App\Listener\Request;


use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class CacheRequestListener
{
    public function onKernelRequest(RequestEvent $event)
    {

        if ($event->getRequest()->getMethod() !== "GET") {
            return;
        }

        $cache = new FilesystemAdapter();
        //$cache->deleteItem(md5($event->getRequest()->getUri()));
        $dataCache = $cache->getItem(md5($event->getRequest()->getUri()));
        if (!$dataCache->isHit()){
            return;
        }

        $response = new Response();
        $response->setContent($dataCache->get());
        $event->setResponse($response);
    }

}