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

        $event->getResponse()
            ->setSharedMaxAge(3600)
            ->setTtl("40")
            ->setVary("Authorization")
            ->headers->addCacheControlDirective('must-revalidate', true);

        //$event->getResponse()->headers->addCacheControlDirective('AUTHORIZATION');
        /*$cache = new FilesystemAdapter();
        $dataCache = $cache->getItem(md5($event->getRequest()->getUri()));
        if ($dataCache->isHit()){
            return;
        }

        $dataCache->set($event->getResponse()->getContent());
        $cache->save($dataCache);*/
        return;
    }

}