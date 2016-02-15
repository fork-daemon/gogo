<?php

namespace App\Mark;

use \App\User\UserInterface;

interface MarkInterface
{

    public function getUser();

    public function getItem();

    public function getMark();

    public function setUser(UserInterface $user);

    public function setItem(MarkableInterface $item);

    public function setMark($mark);

    // record

    public function save();

    public function destroy();

    // static

    public static function add(UserInterface $user, MarkableInterface $item, $mark);

    public static function remove(UserInterface $user, MarkableInterface $item);

    public static function findByMember(UserInterface $user);

    public static function findByItem(MarkableInterface $item);
}

