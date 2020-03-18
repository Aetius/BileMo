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
        $dataSerialized = $this->serializer->serialize($data, 'json', SerializationContext::create()->setGroups($group));
        return new JsonResponse($dataSerialized, Response::HTTP_OK, [], true);
    }

    public function delete()
    {
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param mixed $data
     * @return JsonResponse
     */
    public function updated($data)
    {
        $data = $this->serializer->serialize($data, 'json');
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    /**
     * @param mixed $data
     * @return JsonResponse
     */
    public function created($data)
    {
        $datas = $this->serializer->serialize($data, 'json');
        return new JsonResponse($datas, Response::HTTP_CREATED, [], true);
    }


    /**
     * @param array $errors
     * @return JsonResponse
     */
    public function failed(array $errors)
    {
        $data = $this->serializer->serialize($errors, 'json');
        return new JsonResponse($data, Response::HTTP_BAD_REQUEST, [], true);
    }

    //todo : ajouter le header d'autorisation.
    /*
    $token = jwt_authentication.encoder -> encode(['name' => $name);
    $headers['Authorization']='Bearer '.$token

     * */
}