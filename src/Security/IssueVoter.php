<?php

namespace App\Security;

use App\Entity\Issue;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class IssueVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';

    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof Issue) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {

            return false;
        }

        $issue = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($issue, $user, $token);
            case self::EDIT:
                return $this->canEdit($issue, $user, $token);
            case self::DELETE:
                return $this->canDelete($issue, $user, $token);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(Issue $issue, User $user, $token)
    {
        if ($this->decisionManager->decide($token, ['ROLE_ADMIN'])) {
            return true;
        }

        return $user === $issue->getUser();
    }

    private function canView(Issue $issue, User $user, $token)
    {
        // if they can edit, they can view
        if ($this->canEdit($issue, $user, $token)) {
            return true;
        }

        return false;
    }

    private function canDelete(Issue $issue, User $user, $token)
    {
        // if they can edit, they can delete
        if ($this->canEdit($issue, $user, $token)) {
            return true;
        }

        return false;
    }
}
