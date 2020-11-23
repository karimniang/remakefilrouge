<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AdminVoter extends Voter
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
        return in_array($attribute, ['ADMIN_EDIT', 'ADMIN_VIEW'])
            && $subject instanceof \App\Entity\Admin;
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
            case 'ADMIN_EDIT':
                if ($this->security->isGranted('ROLE_ADMIN') && $subject === $user) {
                    return true;
                }
                return false;
            case 'ADMIN_VIEW':
                if ($this->security->isGranted('ROLE_ADMIN') $subject === $user) {
                    return true;
                }
                return false;
        }

        return false;
    }
}
