<?php


namespace App\Controller;


use App\Entity\Phone;
use App\Repository\PhoneRepository;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhoneController extends AbstractController
{

    /**
     *@Route("/phones/{id}", name="phone_show_one", methods={"GET"})
     */
    public function showOne(Phone $phone, SerializerInterface $serializer)
    {
        $datas = $serializer->serialize($phone, 'json', SerializationContext::create()->setGroups('detail'));
        return new JsonResponse($datas, Response::HTTP_OK, [], true);
    }

    /**
     *@Route("/phones", name="phone_show", methods={"GET"})
     */
    public function showAll(PhoneRepository $repository, SerializerInterface $serializer)
    {
        $phone = $repository->findAll();
        $datas = $serializer->serialize($phone, 'json', SerializationContext::create()->setGroups('list'));
        return new JsonResponse($datas, Response::HTTP_OK, [], true);
    }
}