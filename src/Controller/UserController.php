<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\DTO\UserDTO;
use App\Form\UserType;
use App\Repository\UserRepository;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    /**
     *@Route("/user/all", name="user_show_all", methods={"GET"})
     */
    public function showAll(SerializerInterface $serializer, UserRepository $repository)
    {
        $users = $repository->findAll();
        $datas = $serializer->serialize($users, 'json', SerializationContext::create()->setGroups('list'));
        return new JsonResponse($datas, 200, [], true);
    }

    /**
     *@Route("/user/{id}", name="user_show_one", methods={"GET"})
     */
    public function showOne(User $user, SerializerInterface $serializer)
    {
        $datas = $serializer->serialize($user, 'json', SerializationContext::create()->setGroups('detail'));
        return new JsonResponse($datas, 200, [], true);
    }

    /**
     *@Route("/user/new", name="user_create", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer)
    {
        $json = $request->getContent();
        $post = $serializer->deserialize($json, UserDTO::class, 'json');
        dump("ajout du user type pour créer l'entité. 
        utilisation du dto pour faire les verif via un form et éviter les enregistrements non désirés");
        dd($post);
    }



}