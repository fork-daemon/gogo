<?php

namespace App\Comment;

interface CommentInterface {

    public function getMember();
    public function getItem();
    public function getText();

    public function setMember(MemberInterface $member);
    public function setItem(Commentable $item);
    public function setText($text);

    public function save();
    public function destroy();

    public static function add( MemberInterface $member, Commentable $item);
    public static function remove( MemberInterface $member, Commentable $item);
    public static function findByMember( MemberInterface $member);
    public static function findByItem( Commentable $item);

}
