<?php

namespace App\Like;

use App\User\UserInterface;

interface LikeInterface
{

    public function getMember();

    public function getItem();

    public function setMember(UserInterface $user);

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
