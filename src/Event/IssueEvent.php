<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;
use App\Entity\Issue;

class IssueEvent extends Event
{
    const PRE_SAVED = 'issue.pre_save';
    const POST_SAVED = 'issue.post_save';
    const PRE_UPDATE = 'issue.pre_update';
    const POST_UPDATE = 'issue.post_update';
    const PRE_DELETE = 'issue.pre_delete';

    private $issue;

    public function __construct(Issue $issue)
    {
        $this->issue = $issue;
    }

    public function getIssue()
    {
        return $this->issue;
    }
}
