<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;
use App\Entity\Category;

class CategoryEvent extends Event
{
    const PRE_SAVED = 'category.pre_save';
    const POST_SAVED = 'category.post_save';
    const PRE_UPDATE = 'category.pre_update';
    const POST_UPDATE = 'category.post_update';
    const PRE_DELETE = 'category.pre_delete';

    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getCategory()
    {
        return $this->category;
    }
}
