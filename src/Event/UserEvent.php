<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;
use App\Entity\User;

class UserEvent extends Event
{
    const PRE_SAVED = 'user.pre_save';
    const POST_SAVED = 'user.post_save';
    const PRE_UPDATE = 'user.pre_update';
    const POST_UPDATE = 'user.post_update';
    const PRE_DELETE = 'user.pre_delete';

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }
}
