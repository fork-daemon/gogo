<?php

namespace App\Comment;


interface CommentableInterface
{
    public function getId();

    public function getType();
}
