<?php


namespace App\Controller;


use App\DTO\User\UserDTO;
use App\Entity\Customer;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Representation\DataRepresentation;
use App\Services\ErrorsService;
use App\Services\ResponseJson;
use App\Services\UserService;
use JMS\Serializer\SerializerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
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
     *
     *@SWG\Get(
     *    summary= "Show one user.",
     *    description = "This url allows you to view one of your user. You will have to define the parameter' Id",
     *    @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          type="integer",
     *          description="Define the id of the user in display.",
     *          required=true,
     *      ),
     *    produces={
     *        "application/json"
     *    },
     *
     *    @SWG\Response(
     *        response="200",
     *        description="Show one user.",
     *        @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref=@Model(type=User::class, groups={"detail"}))
     *         )
     *    )
     *)
     * @Security(name="Bearer")
     *
     */
    public function showOne(User $user, UserService $service)
    {
        return $this->responseJson->show($user, ResponseJson::ONE);
    }

    /**
     * @Route("/users/{id}", name="user_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     * @IsGranted( "CUSTOMER_ACCESS", subject="user")
     *
     * @SWG\Delete(
     *    summary= "Delete one user.",
     *    description = "This url allows you to delete one of your user. You will have to define the parameter' Id",
     *    @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          type="integer",
     *          description="Define the id of the user in display.",
     *          required=true,
     *      ),
     *
     *    @SWG\Response(
     *        response="204",
     *        description="Delete the user. Return a page 204 with no content."
     *    )
     *)
     * @Security(name="Bearer")
     *
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
     *
     *
     * @SWG\Put(
     *    summary= "Update one user.",
     *    description = "This url allows you to update one of your user. You will have to define the parameter' Id and all the user's attributes",
     *    @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          type="integer",
     *          description="Define the id of the user in display.",
     *          required=true,
     *      ),
     *
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         type="string",
     *         description="Replace all the property of the user in display. You must fill all the fields.",
     *         required=true,
     *         @SWG\Schema(
     *             ref=@Model(type=User::class, groups={"detail"}),
     *             @SWG\Items(
     *                  type="json"
     *             )
     *         ),
     *     ),
     *
     *    produces={
     *        "application/json"
     *    },
     *
     *    @SWG\Response(
     *        response="200",
     *        description="Update one user and return the modifications.",
     *        @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref=@Model(type=User::class, groups={"detail"})),
     *        )
     *    ),
     *
     *     @SWG\Response(
     *        response="400",
     *        description="Show fields errors, like field shoudl not be empty."
     *    )
     *)
     *
     * @Security(name="Bearer")
     *
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
     *
     *  * @SWG\Post(
     *    summary= "Create a user.",
     *    description = "This url allows you to create a new user for your account. You will have to fill all the user's field. ",
     *
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         type="string",
     *         description="Create a new user. You must fill all the fields.",
     *         required=true,
     *         @SWG\Schema(
     *             ref=@Model(type=User::class, groups={"detail"}),
     *             @SWG\Items(
     *                  type="json"
     *             )
     *         ),
     *     ),
     *
     *    produces={
     *        "application/json"
     *    },
     *
     *    @SWG\Response(
     *        response="201",
     *        description="Create the new user and display this new creation.",
     *        @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref=@Model(type=User::class, groups={"detail"})),
     *        )
     *    ),
     *
     *     @SWG\Response(
     *        response="400",
     *        description="Show fields errors, like field shoudl not be empty."
     *    )
     *)
     *
     * @Security(name="Bearer")
     *
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
            $service->save($user);
            return $this->responseJson->created($user, ResponseJson::ONE);
        }
        return $this->responseJson->failed($errors, ResponseJson::ONE);
    }

    /**
     * @Route("/users", name="user_show_all", methods={"GET"})
     * @IsGranted("ROLE_USER")
     *
     *  *@SWG\Get(
     *    summary= "Show all users linked to your account.",
     *    description = "This url allows you to view all the users, with a custom pagination. You will have to define the parameters",
     *    produces={
     *        "application/json"
     *    },
     *
     *     @SWG\Parameter(
     *            name="page",
     *            in="query",
     *            type="integer",
     *            description="Paginate the response"
     *        ),
     *     @SWG\Parameter(
     *            name="limit",
     *            in="query",
     *            type="integer",
     *            description="Indicate the number of users by page."
     *        ),
     *    @SWG\Response(
     *        response="200",
     *        description="Users' display. ",
     *        @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref=@Model(type=Phone::class, groups={"list"}))
     *         )
     *    )
     *)
     *
     * @Security(name="Bearer")
     *
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