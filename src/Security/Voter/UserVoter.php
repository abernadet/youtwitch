<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';

    protected function supports($attribute, $subject)
    {
        # Si l'attribut n'est pas supporté, on renvoie false
        if (!in_array($attribute, array(self::VIEW, self::EDIT, self::DELETE))){
            return false;
        }

        # Si $subject n'est pas un objet de classe User
        if (!$subject instanceof User){
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $userConnect = $token->getUser(); # Je récupère l'utilisateur connecté
        // if the user is anonymous, do not grant access
        if (!$userConnect instanceof UserInterface) {
            return false;
        }

        # Grâce à la méthode supports(), on sait que $subject (passé en paramètre) est un objet de classe User
        $user = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($user, $userConnect);
                break;
            case self::VIEW:
                return $this->canView($user, $userConnect);
                break;
            case self::DELETE:
                return $this->canDelete($user, $userConnect);
                break;
        }

        return false;
    }

    # Je crée une méthode qui va déterminer si l'utilisateur peut modifier l'utilisateur
    private function canEdit(User $user, User $userConnect){
        if ($userConnect == $user->getUsername()){ # L'utilisateur peut modifier l'utilisateur s'il en est l'auteur
            return true;
        }else{
            return false;
        }
    }

    # Méthode qui va déterminer si l'utilisateur peut voir l'utilisateur
    private function canView(User $user, User $userConnect){
        return true;
    }

    # Méthode qui va déterminer si l'utilisateur peut supprimer l'utilisateur
    private function canDelete(User $user, User $userConnect){
        if ($userConnect == $user->getUsername()){
            return true;
        }else{
            return false;
        }
    }
}
