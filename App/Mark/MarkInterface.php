<?php

namespace App\Mark;

interface MarkInterface {

    public function getMember();
    public function getItem();
    public function getMark();

    public function setMember(\App\User\UserInterface $member);
    public function setItem(MarkableInterface $item);
    public function setMark($mark);



    public function save();
    public function destroy();

    public static function add( \App\User\UserInterface $member, MarkableInterface $item);
    public static function remove( \App\User\UserInterface $member, MarkableInterface $item);
    public static function findByMember(  \App\User\UserInterface $member);
    public static function findByItem( MarkableInterface $item);
}

