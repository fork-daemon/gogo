<?php

namespace App\Comment;

use App\User\UserInterface;

interface CommentInterface
{

    public function getMember();

    public function getItem();

    public function getText();

    public function getTimestamp();


    public function setMember(UserInterface $user);

    public function setItem(UserInterface $item);

    public function setText($text);

    public function setTimestamp($timestamp = null);

    // record

    public function save();

    public function destroy();

    // static

    public static function add(UserInterface $user, CommentableInterface $item, $text, $timestamp);

    public static function findByMember(UserInterface $user);

    public static function findByItem(CommentableInterface $item);

}
