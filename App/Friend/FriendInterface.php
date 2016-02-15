<?php

namespace App\Friend;

use App\User\UserInterface;

interface FriendInterface
{

    public function getUser();

    public function getItem();

    public function setUser(UserInterface $user);

    public function setItem(UserInterface $user);

    // record

    public function save();

    public function destroy();

    // static

    public static function add(UserInterface $user, FriendableInterface $item);

    public static function remove(UserInterface $user, FriendableInterface $item);

    public static function findByMember(UserInterface $user);

    public static function findByItem(FriendableInterface $item);

}
