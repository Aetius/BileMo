<?php


namespace App\Controller;


use App\Entity\User;
use App\DTO\User\UserDTO;
use App\Repository\UserRepository;
use App\Services\ErrorsService;
use App\Services\ResponseJson;
use App\Services\UserService;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    /**
     * @var ResponseJson
     */
    private $responseJson;

    public function __construct(ResponseJson $responseJson)
    {
        $this->responseJson = $responseJson;
    }

    /**
     *@Route("/users/{id}", name="user_show_one", methods={"GET"})
     */
    public function showOne(User $user)
    {
        return $this->responseJson->show($user, ResponseJson::ONE);
    }

    /**
     *@Route("/users/{id}", name="user_delete_one", methods={"DELETE"})
     */
    public function delete(User $user, UserService $service)
    {
        $service->delete($user);
        return $this->responseJson->delete();
    }

    /**
     *@Route("/users/{id}", name="user_update_one", methods={"PUT"})
     */
    public function update(User $user, SerializerInterface $serializer, Request $request, UserService $service,
                           ErrorsService $errorsService)
    {
        $dto = $serializer->deserialize($request->getContent(), UserDTO::class, 'json');
        $errors = $errorsService->validate($dto, "Create");

        if ($errors == null){
            $user = $service->update($dto, $user);
            $service->save($user);
            return $this->responseJson->updated($user);
        }
        return $this->responseJson->failed($errors);

    }

    /**
     *@Route("/users/create", name="user_create", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, UserService $service, ErrorsService $errorsService)
    {
        $dto = $serializer->deserialize($request->getContent(), UserDTO::class, 'json');
        $errors = $errorsService->validate($dto, "Create");

        if ($errors == null){
            $user = $service->create($dto);
            $service->save($user);
            return $this->responseJson->created($user);
        }
        return $this->responseJson->failed($errors);
    }

    /**
     *@Route("/users", name="user_show_all", methods={"GET"})
     */
    public function showAll(UserRepository $repository)
    {
        $users = $repository->findAll();
        return $this->responseJson->show($users, ResponseJson::ALL);
    }

}