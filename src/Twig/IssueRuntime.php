<?php
namespace App\Twig;

use Twig\Extension\RuntimeExtensionInterface;

class IssueRuntime implements RuntimeExtensionInterface
{
    public function hasPriority($issue)
    {
        $now = new \DateTime();
        $interval = new \DateInterval('P5D');
        $compareDate = $now->sub($interval);

        if ($compareDate > $issue->getCreatedAt() && $issue->getSolved() !== true) {
            return true;
        }

        return false;
    }
}
