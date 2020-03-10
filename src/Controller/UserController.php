<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\User\CreateUserType;
use App\Form\User\UpdateUserType;
use App\Repository\UserRepository;
use App\Services\ErrorsService;
use App\Services\UserService;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{

    /**
     *@Route("/users/{id}", name="user_show_one", methods={"GET"})
     */
    public function showOne(User $user, SerializerInterface $serializer)
    {
        $datas = $serializer->serialize($user, 'json', SerializationContext::create()->setGroups('detail'));
        return new JsonResponse($datas, Response::HTTP_OK, [], true);
    }

    /**
     *@Route("/user/{id}", name="user_delete_one", methods={"DELETE"})
     */
    public function delete(User $user, UserService $service)
    {
        $service->delete($user);
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     *@Route("/users/{id}", name="user_update_one", methods={"PUT"})
     */
    public function update(User $user, SerializerInterface $serializer, Request $request, UserService $service,
                           ErrorsService $errorsService)
    {
        $form = $this->createForm(UpdateUserType::class);
        $post = $serializer->deserialize($request->getContent(), "array", 'json');
        $form->submit($post);

        if ($form->isSubmitted() && $form->isValid()){
            //$form->getErrors(true, true);

            $user = $service->update($form->getData(), $user);
            $service->save($user);
            $datas = $serializer->serialize($user, 'json');
            return new JsonResponse($datas, Response::HTTP_OK, [], true);
        }

        $errors = $errorsService->define($form->getData());
        $datas = $serializer->serialize($errors, 'json');
        return new JsonResponse($datas, Response::HTTP_BAD_REQUEST, [], true);
    }

    /**
     *@Route("/users/create", name="user_create", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, UserService $service, ErrorsService $errorsService)
    {
        $form = $this->createForm(CreateUserType::class);
        $post = $serializer->deserialize($request->getContent(), "array", 'json');
        $form->submit($post);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $service->create($form->getData());
            $service->save($user);
            $datas = $serializer->serialize($user, 'json');
            return new JsonResponse($datas, Response::HTTP_CREATED, [], true);
        }

        $errors = $errorsService->define($form->getData());
        $datas = $serializer->serialize($errors, 'json');
        return new JsonResponse($datas, Response::HTTP_BAD_REQUEST, [], true);
    }


    /**
     *@Route("/users", name="user_show_all", methods={"GET"})
     */
    public function showAll(SerializerInterface $serializer, UserRepository $repository)
    {
        $users = $repository->findAll();
        $datas = $serializer->serialize($users, 'json', SerializationContext::create()->setGroups('list'));
        return new JsonResponse($datas, Response::HTTP_OK, [], true);
    }


}