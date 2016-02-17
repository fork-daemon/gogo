<?php

namespace App\Like;

use App\User\UserInterface;

interface LikeInterface
{

    public function getUser();

    public function getItem();

    public function setUser(UserInterface $user);

    public function setItem(LikableInterface $item);

    // record

    public function save();

    public function destroy();

    // static

    public static function add(UserInterface $user, LikableInterface $item);

    public static function remove(UserInterface $user, LikableInterface $item);

    public static function findByMember(UserInterface $user);

    public static function findByItem(LikableInterface $item);

}
