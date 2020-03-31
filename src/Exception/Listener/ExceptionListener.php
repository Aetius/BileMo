<?php


namespace App\Exceptions\Listener;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $statusCode = ($exception instanceof HttpExceptionInterface) ?
            $exception->getStatusCode() :
            Response::HTTP_INTERNAL_SERVER_ERROR;

        $message = [
            "There was an error during the request. Here is the message error : " => $exception->getMessage(),
            "Error code" => $statusCode
        ];
        $data = $this->serializer->serialize($message, 'json');

        $response = new JsonResponse($data, $statusCode, [], true);

        ($event->setResponse($response));
    }

}