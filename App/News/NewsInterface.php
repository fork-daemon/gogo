<?php

namespace App\News;

use App\User\UserInterface;

interface NewsInterface
{

    public function getUser();

    public function getTitle();

    public function getText();

    public function getTimestamp();

    public function setUser(UserInterface $user);

    public function setTitle($title);

    public function setText($text);

    public function setTimestamp($timestamp = null);

    // record

    public function save();

    public function destroy();

    // static

    public static function add(UserInterface $user, $title, $text, $timestamp);

    public static function findByMember(UserInterface $user);

}
