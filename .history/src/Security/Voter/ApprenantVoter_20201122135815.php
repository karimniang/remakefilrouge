<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ApprenantVoter extends Voter
{

    private $security = null;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['APPRENANT_EDIT', 'APPRENANT_VIEW', 'APPRENANT_ALL_VIEW'])
            && $subject instanceof \App\Entity\Apprenant;
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
            case 'APPRENANT_EDIT':
                if ($this->security->isGranted('ROLE_FORMATEUR') || ($this->security->isGranted('ROLE_APPRENANT') && $subject === $user)) {
                    return true;
                }
                return false;
            case 'APPRENANT_VIEW':
                if ($this->security->isGranted('ROLE_CM') || ($this->security->isGranted('ROLE_APPRENANT') && $subject === $user)) {
                    return true;
                }
                return false;
            case 'APPRENANT_ALL_VIEW':
                if ($this->security->isGranted('ROLE_CM') || $this->security->isGranted('ROLE_APPRENANT')) {
                    return true;
                }
                return false;
        }

        return false;
    }
}
