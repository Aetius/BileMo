<?php


namespace App\Controller;


use App\DTO\User\UserDTO;
use App\Entity\Customer;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Representation\DataRepresentation;
use App\Representation\ErrorRepresentation;
use App\Services\ErrorsService;
use App\Services\ResponseJson;
use App\Services\UserService;
use JMS\Serializer\SerializerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    const LIMIT_USER_PER_PAGE = 2;

    /**
     * @var ResponseJson
     */
    private $responseJson;


    public function __construct(ResponseJson $responseJson)
    {
        $this->responseJson = $responseJson;
    }

    /**
     * @Route("/users/{id}", name="user_show_one", methods={"GET"})
     * @IsGranted("ROLE_USER")
     * @IsGranted( "CUSTOMER_ACCESS", subject="user")
     */
    public function showOne(User $user, UserService $service)
    {
        return $this->responseJson->show($user, ResponseJson::ONE);
    }

    /**
     * @Route("/users/{id}", name="user_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     * @IsGranted( "CUSTOMER_ACCESS", subject="user")
     */
    public function delete(User $user, UserService $service)
    {
        $service->delete($user);
        return $this->responseJson->delete();
    }

    /**
     * @Route("/users/{id}", name="user_update", methods={"PUT"})
     * @IsGranted("ROLE_USER")
     * @IsGranted( "CUSTOMER_ACCESS", subject="user")
     */
    public function update(User $user, SerializerInterface $serializer, Request $request, UserService $service,
                           ErrorsService $errorsService)
    {
        $dto = $serializer->deserialize($request->getContent(), UserDTO::class, 'json');
        $errors = $errorsService->validate($dto, "Create");

        if ($errors == null) {
            $user = $service->update($dto, $user);
            $service->save($user);
            return $this->responseJson->show($user, ResponseJson::ONE);
        }
        return $this->responseJson->failed($errors, ResponseJson::ONE);

    }

    /**
     * @Route("/users/create", name="user_create", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request, SerializerInterface $serializer, UserService $service, ErrorsService $errorsService,
        DataRepresentation $representation)
    {
        $dto = $serializer->deserialize($request->getContent(), UserDTO::class, 'json');
        $errors = $errorsService->validate($dto, "Create");

        if ($errors == null) {
            $customer = $this->getUser();
            /**@var Customer $customer */
            $user = $service->create($dto, $customer);
            $service->save($user); //todo : lors de la création, renvoie beaucoup trop d'info (customer complet + autres users associés).
            return $this->responseJson->created($user, ResponseJson::ONE);
        }
        return $this->responseJson->failed($errors, ResponseJson::ONE);
    }

    /**
     * @Route("/users", name="user_show_all", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function showAll(UserRepository $repository, PaginatorInterface $paginator, Request $request, DataRepresentation $representation)
    {
        $usersQuery = $paginator->paginate(
            $repository->findAllQuery($this->getUser()->getId()),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', self::LIMIT_USER_PER_PAGE)
        );
        $users = $representation->showAll($usersQuery, $request->get("_route"));
        return $this->responseJson->show($users, ResponseJson::ALL);
    }



}