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
     * @param $dto
     * @param null $group
     * @return array
     */
    public function validate($dto, $group=null)
    {
        $errors = [];
        $allErrors = $this->validator->validate($dto, null, $group);
        foreach ($allErrors as $error){
            $errors[]= $error->getPropertyPath()." : ".$error->getMessage()." Error code : 400";
        }
        return $errors;
    }
}