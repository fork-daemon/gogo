<?php

namespace App\Comment;

use App\User\UserInterface;

class Comment implements CommentInterface
{
    public function getMember()
    {
        // TODO: Implement getMember() method.
    }

    public function getItem()
    {
        // TODO: Implement getItem() method.
    }

    public function getText()
    {
        // TODO: Implement getText() method.
    }

    public function getTimestamp()
    {
        // TODO: Implement getTimestamp() method.
    }

    public function setMember(UserInterface $user)
    {
        // TODO: Implement setMember() method.
    }

    public function setItem(UserInterface $item)
    {
        // TODO: Implement setItem() method.
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

    public static function add(UserInterface $user, CommentableInterface $item, $text, $timestamp)
    {
        // TODO: Implement add() method.
    }

    public static function findByMember(UserInterface $user)
    {
        // TODO: Implement findByMember() method.
    }

    public static function findByItem(CommentableInterface $item)
    {
        // TODO: Implement findByItem() method.
    }

}

