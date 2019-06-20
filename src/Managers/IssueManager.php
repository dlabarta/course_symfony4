<?php

namespace App\Managers;

use App\Entity\Issue;
use App\Event\IssueEvent;
use App\Service\CodeGenerator;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class IssueManager
{
    protected $em;
    protected $dispatcher;
    protected $generateCode;
    protected $codeGenerator;
    protected $fileUploader;
    protected $oldVersion;

    public function __construct(
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        CodeGenerator $codeGenerator,
        FileUploader $fileUploader)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
        $this->codeGenerator = $codeGenerator;
        $this->fileUploader = $fileUploader;
        $this->oldVersion = null;
    }

    public function newObject(): Issue
    {
        $issue = new Issue();
        $issue->setSolved(false);

        return $issue;
    }

    public function create($issue, $user): Issue
    {
        $issue->setUser($user);
        $issue->setCreatedAt(new \DateTime());
        $this->codeGenerator->geCode(Issue::class, $issue);
        $this->setAttachment($issue);

        $event = new IssueEvent($issue);
        $this->dispatcher->dispatch(IssueEvent::PRE_SAVED, $event);

        $this->em->persist($issue);
        $this->em->flush();

        $event = new IssueEvent($issue);
        $this->dispatcher->dispatch(IssueEvent::POST_SAVED, $event);

        return $issue;
    }

    public function update($issue): Issue
    {
        $this->setAttachment($issue);

        $event = new IssueEvent($issue);
        $this->dispatcher->dispatch(IssueEvent::PRE_UPDATE, $event);

        $this->em->flush();

        $event = new IssueEvent($issue);
        $this->dispatcher->dispatch(IssueEvent::POST_UPDATE, $event);

        return $issue;
    }

    public function delete($issue): void
    {
        $event = new IssueEvent($issue);
        $this->dispatcher->dispatch(IssueEvent::PRE_DELETE, $event);

        $this->em->remove($issue);
        $this->em->flush();
    }

    protected function setAttachment($issue)
    {
        $file = $issue->getAttachment();
        if ($file) {
            $fileName = $this->fileUploader->upload($file);
            $issue->setAttachmentName($fileName);
            if ($this->oldVersion) {
                if (!empty($this->oldVersion->getAttachmentName())) {
                    $this->fileUploader->delete($this->oldVersion->getAttachmentName());
                }
            }
        }
    }

    public function setOldVersion($issue)
    {
        $this->oldVersion = clone $issue;
    }
}