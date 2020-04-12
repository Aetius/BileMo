<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueEmailFromCustomer extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = "This email is already used.";

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }


}
