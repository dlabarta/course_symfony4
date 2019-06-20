<?php

namespace App\Managers;

use App\Entity\Category;
use App\Event\CategoryEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CategoryManager
{
    protected $em;
    protected $dispatcher;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    public function newObject(): Category
    {
        $category = new Category();

        return $category;
    }

    public function create($category): Category
    {
        $event = new CategoryEvent($category);
        $this->dispatcher->dispatch(CategoryEvent::PRE_SAVED, $event);

        $this->em->persist($category);
        $this->em->flush();

        $event = new CategoryEvent($category);
        $this->dispatcher->dispatch(CategoryEvent::POST_SAVED, $event);

        return $category;
    }

    public function update($category): Category
    {
        $event = new CategoryEvent($category);
        $this->dispatcher->dispatch(CategoryEvent::PRE_UPDATE, $event);

        $this->em->flush();

        $event = new CategoryEvent($category);
        $this->dispatcher->dispatch(CategoryEvent::POST_UPDATE, $event);

        return $category;
    }

    public function delete($category): void
    {
        $event = new CategoryEvent($category);
        $this->dispatcher->dispatch(CategoryEvent::PRE_DELETE, $event);

        $this->em->remove($category);
        $this->em->flush();
    }
}