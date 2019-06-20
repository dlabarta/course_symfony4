<?php
namespace App\EventSubscribers;

use App\Event\IssueEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;

class Issue implements EventSubscriberInterface
{
    protected $mailer;
    protected $twig;

    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
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
            $message = (new \Swift_Message('Incidencia registrada.'))
                ->setFrom('labarta.david@gmail.com')
                ->setTo($issue->getEmail())
                ->setBody(
                    $this->twig->render(
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
