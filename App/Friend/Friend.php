<?php

namespace App\Friend;

use App\User\UserInterface;

class Friend implements FriendInterface
{
    public function getUser()
    {
        // TODO: Implement getUser() method.
    }

    public function getItem()
    {
        // TODO: Implement getItem() method.
    }

    public function setUser(UserInterface $user)
    {
        // TODO: Implement setUser() method.
    }

    public function setItem(UserInterface $user)
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

    public static function add(UserInterface $user, FriendableInterface $item)
    {
        // TODO: Implement add() method.
    }

    public static function remove(UserInterface $user, FriendableInterface $item)
    {
        // TODO: Implement remove() method.
    }

    public static function findByMember(UserInterface $user)
    {
        // TODO: Implement findByMember() method.
    }

    public static function findByItem(FriendableInterface $item)
    {
        // TODO: Implement findByItem() method.
    }

}

