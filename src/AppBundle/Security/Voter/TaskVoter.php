<?php

namespace AppBundle\Security\Voter;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    const TASK_DELETE = 'task_delete';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::TASK_DELETE])
            && $subject instanceof Task;
    }

    /**
     * @param string $attribute
     * @param Task $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }


        switch ($attribute) {
            case self::TASK_DELETE:
                return $this->canDelete($subject, $user);
                break;
        }

        return false;
    }

    /**
     * Fonction permettant de vérifier si l'utilisateur peut supprimer la tâche
     * @param Task $task
     * @param UserInterface $user
     * @return boolean
     */
    private function canDelete(Task $task, UserInterface $user): bool
    {
        return $user === $task->getUser();
    }
}
