<?php

namespace App;

interface MemberInterface {
    public function getId();
    public function getName();
}


interface Likable {
    public function getId();
    public function getType();
}

interface Commentable {
    public function getId();
    public function getType();
}

interface Markable {
    public function getId();
    public function getType();
}

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

interface MarkInterface {

    public function getMember();
    public function getItem();
    public function getMark();

    public function setMember(MemberInterface $member);
    public function setItem(Markable $item);
    public function setMark($mark);



    public function save();
    public function destroy();

    public static function add( MemberInterface $member, Markable $item);
    public static function remove( MemberInterface $member, Markable $item);
    public static function findByMember( MemberInterface $member);
    public static function findByItem( Markable $item);
}


interface Notifable {

    public function getSmallText();
    public function getFullText();

}

interface NotifyInterface {
    public function getMember();
    public function getItem();

    public function setMember(MemberInterface $member);
    public function setItem(Notifable $item);

    public static function send(MemberInterface $member, Notifable $item);
}

class NotifyMail implements NotifyInterface
{
    public function getMember()
    {
        // TODO: Implement getMember() method.
    }

    public function getItem()
    {
        // TODO: Implement getItem() method.
    }

    public function setMember(MemberInterface $member)
    {
        // TODO: Implement setMember() method.
    }

    public function setItem(Notifable $item)
    {
        // TODO: Implement setItem() method.
    }

    public static function send(MemberInterface $member, Notifable $item)
    {
        // TODO: Implement send() method.
    }

}


class ItemAaa implements Likable, Markable, Commentable {

    public function getId()
    {
        return 'item-id';
    }

    public function getType()
    {
        return 'item-aaa';
    }
}

class Quantore implements MemberInterface {

    public function getId()
    {
        return 'item-quantore';
    }

    public function getName()
    {
        return 'quantore';
    }

}

$user = new Quantore();
$item = new ItemAaa();
$noty = NotifyMail::send($user , $item);

