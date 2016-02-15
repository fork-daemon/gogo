<?php

namespace App\Like;

use App\User\UserInterface;

class Like implements LikeInterface
{
    public function getMember()
    {
        // TODO: Implement getMember() method.
    }

    public function getItem()
    {
        // TODO: Implement getItem() method.
    }

    public function setMember(UserInterface $user)
    {
        // TODO: Implement setMember() method.
    }

    public function setItem(LikableInterface $item)
    {
        // TODO: Implement setItem() method.
    }

    public function save()
    {
        // TODO: Implement save() method.
    }

    public function destroy()
    {
        // TODO: Implement destroy() method.
    }

    public static function add(UserInterface $user, LikableInterface $item)
    {
        // TODO: Implement add() method.
    }

    public static function remove(UserInterface $user, LikableInterface $item)
    {
        // TODO: Implement remove() method.
    }

    public static function findByMember(UserInterface $user)
    {
        // TODO: Implement findByMember() method.
    }

    public static function findByItem(LikableInterface $item)
    {
        // TODO: Implement findByItem() method.
    }

}