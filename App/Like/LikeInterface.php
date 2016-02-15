<?php

namespace App\Like;

interface LikeInterface {

    public function getMember();
    public function getItem();

    public function setMember(MemberInterface $member);
    public function setItem(Likable $item);

    public function save();
    public function destroy();

    public static function add( MemberInterface $member, Likable $item);
    public static function remove( MemberInterface $member, Likable $item);
    public static function findByMember( MemberInterface $member);
    public static function findByItem( Likable $item);

}
