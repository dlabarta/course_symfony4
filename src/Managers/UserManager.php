<?php

namespace App\Managers;

use App\Entity\User;
use App\Event\UserEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UserManager
{
    protected $em;
    protected $dispatcher;

    public function __construct(
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher
    )
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    public function newObject(): User
    {
        $user = new User();

        return $user;
    }

    public function create($user): User
    {
        $event = new UserEvent($user);
        $this->dispatcher->dispatch(UserEvent::PRE_SAVED, $event);

        $this->em->persist($user);
        $this->em->flush();

        $event = new UserEvent($user);
        $this->dispatcher->dispatch(UserEvent::POST_SAVED, $event);

        return $user;
    }

    public function update($user): User
    {
        $event = new UserEvent($user);
        $this->dispatcher->dispatch(UserEvent::PRE_UPDATE, $event);

        $this->em->flush();

        $event = new UserEvent($user);
        $this->dispatcher->dispatch(UserEvent::POST_UPDATE, $event);

        return $user;
    }

    public function delete($user): void
    {
        $event = new UserEvent($user);
        $this->dispatcher->dispatch(UserEvent::PRE_DELETE, $event);

        $this->em->remove($user);
        $this->em->flush();
    }
}