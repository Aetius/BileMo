<?php


namespace App\Controller;


use App\Entity\Phone;
use App\Repository\PhoneRepository;
use App\Representation\DataRepresentation;
use App\Services\Paginator;
use App\Services\ResponseJson;
use Knp\Component\Pager\PaginatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\ItemInterface;

class PhoneController extends AbstractController
{
    const LIMIT_PHONE_PER_PAGE = 2;

    /**
     * @var ResponseJson
     */
    private $responseJson;

    public function __construct(ResponseJson $responseJson)
    {
        $this->responseJson = $responseJson;
    }

    /**
     * @Route("/phones/{id}", methods={"GET"}, name="phone_show_one")
     * @IsGranted("ROLE_USER")
     *
     * @SWG\Get(
     *    summary= "Show one phone.",
     *    description = "This url allows you to view one phone. You will have to define the parameter' Id",
     *    @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          type="integer",
     *          description="Define the id of the phone in display.",
     *          required=true,
     *      ),
     *    produces={
     *        "application/json"
     *    },
     *
     *    @SWG\Response(
     *        response="200",
     *        description="Show one article.",
     *        @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref=@Model(type=Phone::class, groups={"detail"}))
     *         )
     *    )
     *)
     * @Security(name="Bearer")
     * @param Phone $phone
     * @param AdapterInterface $adapter
     * @return JsonResponse
     */
    public function showOne(Phone $phone)
    {
        return $this->responseJson->show($phone, ResponseJson::ONE);
    }


    /**
     * @Route("/phones", methods={"GET"}, name="phone_show_all")
     * @IsGranted("ROLE_USER")
     *
     * @SWG\Get(
     *    summary= "Show all the phones",
     *    description = "This url allows you to view all the phones, with a custom pagination. You will have to define the parameters",
     *    produces={
     *        "application/json"
     *    },
     *
     *     @SWG\Parameter(
     *            name="brand",
     *            in="query",
     *            type="integer",
     *            description="Allow you to show all the phones by brand"
     *        ),
     *      @SWG\Parameter(
     *            name="keyword",
     *            in="query",
     *            type="string",
     *            description="Allow you to show all the phones by keyword. You can mix this parameter and brand parameter."
     *        ),
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
     *            description="Indicate the number of phones by page."
     *        ),
     *    @SWG\Response(
     *        response="200",
     *        description="Articles' display. ",
     *        @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref=@Model(type=Phone::class, groups={"list"}))
     *         )
     *    )
     *)
     *
     * @Security(name="Bearer")
     * @param PhoneRepository $repository
     * @param Paginator $paginator
     * @param Request $request
     * @param DataRepresentation $representation
     * @return JsonResponse
     */
    public function showAll(PhoneRepository $repository, Paginator $paginator, Request $request,
                            DataRepresentation $representation)
    {
        $phonesQuery = $paginator->paginatePhones($request->query, $repository);
        $phones = $representation->showAll($phonesQuery, $request->get("_route"));
        return $this->responseJson->show($phones, ResponseJson::ALL);
    }

}