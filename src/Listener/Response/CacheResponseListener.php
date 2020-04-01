<?php


namespace App\Listener\Response;


use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class CacheResponseListener
{
    public function onKernelResponse(ResponseEvent $event)
    {
        if ($event->getRequest()->getMethod() !== "GET") {
            return;
        }

        $cache = new FilesystemAdapter();
        $dataCache = $cache->getItem(md5($event->getRequest()->getUri()));
        if ($dataCache->isHit()){
            return;
        }

        $dataCache->set($event->getResponse()->getContent());
        $cache->save($dataCache);
        return;
    }

}