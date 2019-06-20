<?php

namespace App\Managers;

use App\Entity\Tag;
use App\Event\TagEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TagManager
{
    protected $em;
    protected $dispatcher;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    public function newObject(): Tag
    {
        $tag = new Tag();

        return $tag;
    }

    public function create($tag): Tag
    {
        $event = new TagEvent($tag);
        $this->dispatcher->dispatch(TagEvent::PRE_SAVED, $event);

        $this->em->persist($tag);
        $this->em->flush();

        $event = new TagEvent($tag);
        $this->dispatcher->dispatch(TagEvent::POST_SAVED, $event);

        return $tag;
    }

    public function update($tag): Tag
    {
        $event = new TagEvent($tag);
        $this->dispatcher->dispatch(TagEvent::PRE_UPDATE, $event);

        $this->em->flush();

        $event = new TagEvent($tag);
        $this->dispatcher->dispatch(TagEvent::POST_UPDATE, $event);

        return $tag;
    }

    public function delete($tag): void
    {
        $event = new TagEvent($tag);
        $this->dispatcher->dispatch(TagEvent::PRE_DELETE, $event);

        $this->em->remove($tag);
        $this->em->flush();
    }
}