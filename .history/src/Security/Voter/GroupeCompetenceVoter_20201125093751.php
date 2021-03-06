<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class GroupeCompetenceVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['GROUPE_VIEW', 'GROUPE_VIEW_ADMIN', 'GROUPE_EDIT'])
            && $subject instanceof \App\Entity\GroupeCompetence;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'GROUPE_EDIT':
                if ($this->security->isGranted('ROLE_FORMATEUR') || ($this->security->isGranted('ROLE_CM') && $subject === $user)) {
                    return true;
                }
            break;

            case 'GROUPE_VIEW':
                return $user->getRoles()[0] === "ROLE_ADMIN" || $user->getRoles()[0] === "ROLE_FORMATEUR" || $user->getRoles()[0] === "ROLE_CM";
                break;
        }


        return false;
    }
}
