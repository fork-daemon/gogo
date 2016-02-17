<?php

namespace App\News;

use App\Comment\CommentableInterface;
use App\Like\LikableInterface;
use App\User\UserInterface;

class News implements NewsInterface, CommentableInterface, LikableInterface
{
    public function getId()
    {
        // TODO: Implement getUser() method.
        return uniqid();
    }

    public function getType()
    {
        // TODO: Implement getUser() method.
        return 'news';
    }


    public function getUser()
    {
        // TODO: Implement getUser() method.
    }

    public function getTitle()
    {
        // TODO: Implement getTitle() method.
    }

    public function getText()
    {
        // TODO: Implement getText() method.
    }

    public function getTimestamp()
    {
        // TODO: Implement getTimestamp() method.
    }

    public function setUser(UserInterface $user)
    {
        // TODO: Implement setUser() method.
    }

    public function setTitle($title)
    {
        // TODO: Implement setTitle() method.
    }

    public function setText($text)
    {
        // TODO: Implement setText() method.
    }

    public function setTimestamp($timestamp = null)
    {
        // TODO: Implement setTimestamp() method.
    }

    public function save()
    {
        // TODO: Implement save() method.
    }

    public function destroy()
    {
        // TODO: Implement destroy() method.
    }

    public static function add(UserInterface $user, $title, $text, $timestamp)
    {
        // TODO: Implement add() method.
    }

    public static function findByMember(UserInterface $user)
    {
        // TODO: Implement findByMember() method.
    }


}

