<?php


namespace App\Services;


use Symfony\Component\Validator\Validator\ValidatorInterface;

class ErrorsService
{

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param mixed $formDatas
     * @return array
     */
    public function validate($dto, $group=null)
    {
        $errors = [];
        $allErrors = $this->validator->validate($dto, null, $group);
        foreach ($allErrors as $error){
            $errors[]= [$error->getPropertyPath() => $error->getMessage()];
        }
        return $errors;

    }


}