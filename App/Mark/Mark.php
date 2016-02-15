<?php

namespace App\Mark;

use App\User\UserInterface;

class Mark implements MarkInterface
{
    public function getUser()
    {
        // TODO: Implement getUser() method.
    }

    public function getItem()
    {
        // TODO: Implement getItem() method.
    }

    public function getMark()
    {
        // TODO: Implement getMark() method.
    }

    public function setUser(UserInterface $user)
    {
        // TODO: Implement setUser() method.
    }

    public function setItem(MarkableInterface $item)
    {
        // TODO: Implement setItem() method.
    }

    public function setMark($mark)
    {
        // TODO: Implement setMark() method.
    }

    public function save()
    {
        // TODO: Implement save() method.
    }

    public function destroy()
    {
        // TODO: Implement destroy() method.
    }

    public static function add(UserInterface $user, MarkableInterface $item, $mark)
    {
        // TODO: Implement add() method.
    }

    public static function remove(UserInterface $user, MarkableInterface $item)
    {
        // TODO: Implement remove() method.
    }

    public static function findByMember(UserInterface $user)
    {
        // TODO: Implement findByMember() method.
    }

    public static function findByItem(MarkableInterface $item)
    {
        // TODO: Implement findByItem() method.
    }


}

