<?php


namespace App\Services;


use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseJson
{

    const ONE = "detail";
    const ALL = "list";
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param mixed $user
     * @param string $group
     * @return JsonResponse
     */
    public function show($data, $group)
    {
        $dataSerialized = $this->serializer->serialize($data, 'json', SerializationContext::create()->setGroups([$group, "Default"]));
        return new JsonResponse($dataSerialized, Response::HTTP_OK, [], true);
    }

    /**
     * @return Response
     */
    public function delete()
    {
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param mixed $user
     * @param string $group
     * @return JsonResponse
     */
    public function created($data, $group)
    {
        $dataSerialized = $this->serializer->serialize($data, 'json', SerializationContext::create()->setGroups([$group, "Default"]));
        return new JsonResponse($dataSerialized, Response::HTTP_CREATED, [], true);
    }


    /**
     * @param mixed $user
     * @param string $group
     * @return JsonResponse
     */
    public function failed(array $errors, $group)
    {
        $dataSerialized = $this->serializer->serialize($errors, 'json', SerializationContext::create()->setGroups([$group, "Default"]));
        return new JsonResponse($dataSerialized, Response::HTTP_BAD_REQUEST, [], true);
    }

}