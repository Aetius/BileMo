<?php

namespace App\Validator;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueEmailValidator extends ConstraintValidator
{

    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\UniqueEmail */

        if (null === $value || '' === $value) {
            return;
        }

        if ($results = $this->repository->findAllByEmail($value)) {
            foreach ($results as $result){
                if (($result->getCustomer()->getId() == $this->context->getObject()->customer->getId())) {
                    $this->context->buildViolation($constraint->message)
                        ->setParameter('value', $value)
                        ->addViolation();
                }
            }

        }
    }
}
