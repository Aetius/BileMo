<?php


namespace App\DataFixtures\Faker\Processor;

use App\Entity\Customer;
use Fidry\AliceDataFixtures\ProcessorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class CustomerProcessor implements ProcessorInterface
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @inheritDoc
     */
    public function preProcess(string $id, $object): void
    {
        if (false === $object instanceof Customer){
            return;
        }
        /** @var Customer $object */
        $object->setPassword($this->encoder->encodePassword($object, $object->getPassword()));
    }

    /**
     * @inheritDoc
     */
    public function postProcess(string $id, $object): void
    {
        //do nothing
    }
}