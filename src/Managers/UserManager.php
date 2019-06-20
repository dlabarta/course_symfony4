<?php

namespace App\Managers;

use App\Entity\User;
use App\Event\UserEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    protected $em;
    protected $dispatcher;
    protected $encoder;

    public function __construct(
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        UserPasswordEncoderInterface $encoder
    )
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
        $this->encoder = $encoder;
    }

    public function newObject(): User
    {
        $user = new User();

        return $user;
    }

    public function create(User $user): User
    {
        $event = new UserEvent($user);
        $this->dispatcher->dispatch(UserEvent::PRE_SAVED, $event);

        $password = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);

        $this->em->persist($user);
        $this->em->flush();

        $event = new UserEvent($user);
        $this->dispatcher->dispatch(UserEvent::POST_SAVED, $event);

        return $user;
    }

    public function update(User $user): User
    {
        $event = new UserEvent($user);
        $this->dispatcher->dispatch(UserEvent::PRE_UPDATE, $event);

        $this->em->flush();

        $event = new UserEvent($user);
        $this->dispatcher->dispatch(UserEvent::POST_UPDATE, $event);

        return $user;
    }

    public function delete(User $user): void
    {
        $event = new UserEvent($user);
        $this->dispatcher->dispatch(UserEvent::PRE_DELETE, $event);

        $this->em->remove($user);
        $this->em->flush();
    }
}