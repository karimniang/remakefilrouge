<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class FormateurVoter extends Voter
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
        return in_array($attribute, ['FORMATEUR_EDIT', 'FORMATEUR_VIEW'])
            && $subject instanceof \App\Entity\Formateur;
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
            case 'FORMATEUR_EDIT':
               
            case 'FORMATEUR_VIEW':
                if ($this->security->isGranted('ROLE_CM') || ($this->security->isGranted('ROLE_FORMATEUR') && $subject === $user)) {
                    return true;
                }
                return false;
        }

        return false;
    }
}
