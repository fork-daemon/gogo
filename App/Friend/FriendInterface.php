<?php

namespace App\Friend;

interface FriendInterface {

    public function getUser();
    public function getItem();

    public function setUser(MemberInterface $member);
    public function setItem(Commentable $item);

    public function save();
    public function destroy();

    public static function add( MemberInterface $member, Commentable $item);
    public static function remove( MemberInterface $member, Commentable $item);
    public static function findByMember( MemberInterface $member);
    public static function findByItem( Commentable $item);

}
