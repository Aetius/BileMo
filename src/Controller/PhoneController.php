<?php


namespace App\Controller;


use App\Entity\Phone;
use App\Repository\PhoneRepository;
use App\Representation\DataRepresentation;
use App\Services\ResponseJson;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PhoneController extends AbstractController
{
    const LIMIT_PHONE_PER_PAGE = 2;

    /**
     * @var ResponseJson
     */
    private $responseJson;
    /**
     * @var DataRepresentation
     */
    private $representation;

    public function __construct(ResponseJson $responseJson, DataRepresentation $representation)
    {
        $this->responseJson = $responseJson;
        $this->representation = $representation;
    }

    /**
     * @Route("/phones/{id}", name="phone_show_one", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function showOne(Phone $phone)
    {
        return $this->responseJson->show($phone, ResponseJson::ONE);
    }

    /**
     * @Route("/phones", name="phone_show_all", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function showAll(PhoneRepository $repository, PaginatorInterface $paginator, Request $request, DataRepresentation $representation)
    {
        $phonesQuery = $paginator->paginate(
            $repository->findAllQuery($request->query->get('brand')),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', self::LIMIT_PHONE_PER_PAGE)
        );
        $phones = $representation->showAll($phonesQuery, $request->get("_route"));
        return $this->responseJson->show($phones, ResponseJson::ALL);
    }

}