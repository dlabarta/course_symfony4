<?php
namespace App\EventSubscribers;

use App\Event\IssueEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Issue implements EventSubscriberInterface
{
    protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
		    IssueEvent::POST_SAVED => ['sendMail', 2],
        ];
    }

    public function sendMail(IssueEvent $event)
    {
        /*
        $issue = $event->getIssue();

        if ($issue->getEmail()) {
            $message = (new \Swift_Message(''))
                ->setFrom('send@example.com')
                ->setTo($issue->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/issue.html.twig',
                        ['issue' => $issue]
                    ),
                    'text/html'
                );

            $this->mailer->send($message);
        }
        */
    }
}
