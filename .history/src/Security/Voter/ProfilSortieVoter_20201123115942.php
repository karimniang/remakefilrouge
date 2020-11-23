<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfilSortieVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['PROFIL_EDIT', 'PROFIL_VIEW', 'PROFIL_ALL_VIEW', 'PROFIL_POST'])
            && $subject instanceof \App\Entity\ProfilSortie;
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
            case 'PROFIL_EDIT':
                return $user->getRoles()[0] === "ROLE_ADMIN" || $user->getRoles()[0] === "ROLE_FORMATEUR";
            break;
            case 'PROFIL_VIEW':
                if ($this->security->isGranted('ROLE_CM') || $this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }
            break;
            case 'PROFIL_ALL_VIEW':
                if ($this->security->isGranted(ADMIN) || $this->security->isGranted('ROLE_CM') || $this->security->isGranted('ROLE_FORMATEUR')) {
                    return true;
                }
            break;
            case 'PROFIL_POST':
                return $user->getRoles()[0] === "ROLE_ADMIN" || $user->getRoles()[0] === "ROLE_FORMATEUR";
            break;
        }

        return false;
    }
}
