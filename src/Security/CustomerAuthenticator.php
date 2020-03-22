<?php


namespace App\Security;


use App\DTO\Customer\CustomerDTO;
use App\Repository\CustomerRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomerAuthenticator
{
    /**
     * @var CustomerRepository
     */
    private $repository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(CustomerRepository $repository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->repository = $repository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function connexion(CustomerDTO $customer)
    {
        if (!$customer->name || !$customer->password ){
            throw new CustomUserMessageAuthenticationException('This field should not be empty!');
        }

        $user = $this->repository->findOneBy(['name'=>$customer->name]);
        if (!$user){
            throw new CustomUserMessageAuthenticationException('Your login and/or password is/are incorrect');
        }
        /** @var UserInterface $user */
        if (! $this->checkCredentials($customer->password, $user)){
            throw new CustomUserMessageAuthenticationException('Your login and/or password is/are incorrect');
        }
        return $user;
    }

    private function checkCredentials(string $credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials);
    }
}