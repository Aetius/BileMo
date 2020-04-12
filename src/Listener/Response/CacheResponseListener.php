<?php


namespace App\Listener\Response;


use Symfony\Component\HttpKernel\Event\ResponseEvent;

class CacheResponseListener
{

    const MAX_DURATION_CACHE = 2160000;  //last during 25 days

    public function onKernelResponse(ResponseEvent $event)
    {
        if ($event->getRequest()->getMethod() !== "GET") {
            return;
        }

        $event->getResponse()
            ->setSharedMaxAge(self::MAX_DURATION_CACHE)
            ->setTtl(self::MAX_DURATION_CACHE)
            ->setVary("Authorization")
            ->setEtag(md5($event->getResponse()->getContent()))
            ->setPublic()
            ->headers->addCacheControlDirective('must-revalidate', true);

        return;
    }

}