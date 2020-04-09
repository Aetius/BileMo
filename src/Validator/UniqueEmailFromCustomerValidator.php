<?php

namespace App\Validator;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueEmailFromCustomerValidator extends ConstraintValidator
{

    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function validate($object, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\UniqueEmailFromCustomer */

        if (null === $object || '' === $object) {
            return;
        }
        if (!empty($this->repository->findAllByEmail($object) ))
        {
            $this->context->buildViolation($constraint->message)
                ->setParameter('value', 'email')
                ->addViolation();
        }
    }
}
