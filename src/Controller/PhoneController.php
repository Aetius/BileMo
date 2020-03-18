<?php


namespace App\Controller;


use App\Entity\Phone;
use App\Repository\PhoneRepository;
use App\Services\ResponseJson;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PhoneController extends AbstractController
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
     *@Route("/phones/{id}", name="phone_show_one", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function showOne(Phone $phone)
    {
        return $this->responseJson->show($phone, ResponseJson::ONE);
    }

    /**
     *@Route("/phones", name="phone_show", methods={"GET"})
     */
    public function showAll(PhoneRepository $repository)
    {
        $phone = $repository->findAll();
        return $this->responseJson->show($phone, ResponseJson::ALL);
    }
}