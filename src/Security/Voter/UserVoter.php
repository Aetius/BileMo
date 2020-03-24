<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['CUSTOMER_ACCESS'])
            && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $subject */

        $customer = $token->getUser();

        if (!$customer instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'CUSTOMER_ACCESS':
                if ($subject->getCustomer()->getId() === $customer->getId()){
                    return true;
                }
                break;
        }
        return false;
    }
}
