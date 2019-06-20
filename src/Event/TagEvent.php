<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;
use App\Entity\Tag;

class TagEvent extends Event
{
    const PRE_SAVED = 'tag.pre_save';
    const POST_SAVED = 'tag.post_save';
    const PRE_UPDATE = 'tag.pre_update';
    const POST_UPDATE = 'tag.post_update';
    const PRE_DELETE = 'tag.pre_delete';

    private $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    public function getTag()
    {
        return $this->tag;
    }
}
