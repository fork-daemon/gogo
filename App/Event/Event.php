<?php

namespace App\Event;

use App\Comment\CommentableInterface;
use App\Like\LikableInterface;
use App\User\UserInterface;

class Event implements EventInterface, CommentableInterface, LikableInterface
{
    public function getId()
    {
        // TODO: Implement getUser() method.
        return uniqid();
    }

    public function getType()
    {
        // TODO: Implement getUser() method.
        return 'event';
    }

}

