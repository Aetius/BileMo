<?php


namespace App\Services;


use App\Entity\User;
use App\Form\User\DTO\UserDTO;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(UserDTO $userDTO)
    {
        $user = new User();
        $user
            ->setFirstname($userDTO->firstname)
            ->setLastname($userDTO->lastname)
            ->setEmail($userDTO->email);
        return $user;
    }

    public function save(User $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function delete(User $user)
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function update(UserDTO $dto, User $user)
    {
        if ($dto->lastname){
            $user->setLastname($dto->lastname);
        }
        if($dto->firstname){
            $user->setFirstname($dto->firstname);
        }
        if ($dto->email){
            $user->setEmail($dto->email);
        }
        return $user;

    }
}