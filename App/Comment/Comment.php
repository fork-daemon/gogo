<?php

namespace App\Comment;

use App\Service;
use App\User\User;
use App\User\UserInterface;

class Comment implements CommentInterface
{
    /**
     * @var UserInterface
     */
    protected $user;
    /**
     * @var CommentableInterface
     */
    protected $item;
    /**
     * @var null|string
     */
    protected $text;
    /**
     * @var null|int
     */
    protected $timestamp;


    const P_COMMENT_ID = 'comment_id';
    const P_USER_ID = 'user_id';
    const P_ITEM_ID = 'item_id';
    const P_ITEM_TYPE = 'item_type';
    const P_TEXT = 'text';
    const P_TIMESTAMP = 'timestamp';

    /**
     * @var array
     */
    protected $data = [];


    public function __construct($data = null)
    {
        if (is_array($data)) {
            $this->data = $data;
        }
    }

    public function getId()
    {
        return isset($this->data[self::P_COMMENT_ID])
            ? $this->data[self::P_COMMENT_ID]
            : null;
    }

    /**
     * @return UserInterface
     */
    public function getUser()
    {
        if ($this->user === null) {
            $this->user = User::find($this->data[self::P_USER_ID]);
        }

        return $this->user;
    }

    /**
     * @return CommentableInterface
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @return null|string
     */
    public function getText()
    {
        return $this->data[self::P_TEXT];
    }

    /**
     * @return int|null
     */
    public function getTimestamp()
    {
        return $this->data[self::P_TIMESTAMP];
    }

    /**
     * @param UserInterface $user
     *
     * @return $this
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
        $this->data[self::P_USER_ID] = $user->getId();

        return $this;
    }

    /**
     * @param CommentableInterface $item
     *
     * @return $this
     */
    public function setItem(CommentableInterface $item)
    {
        $this->item = $item;
        $this->data[self::P_ITEM_ID] = $item->getId();
        $this->data[self::P_ITEM_TYPE] = $item->getType();

        return $this;
    }

    /**
     * @param $text
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->data[self::P_TEXT] = $text;

        return $this;
    }

    /**
     * @param null $timestamp
     *
     * @return $this
     */
    public function setTimestamp($timestamp = null)
    {
        $this->data[self::P_TIMESTAMP] = $timestamp;

        return $this;
    }

    /**
     * @return $this
     */
    public function save()
    {
        $set = [
            self::P_USER_ID    => $this->data[self::P_USER_ID],
            self::P_ITEM_ID    => $this->data[self::P_ITEM_ID],
            self::P_ITEM_TYPE  => $this->data[self::P_ITEM_TYPE],
            self::P_TEXT       => $this->data[self::P_TEXT],
            self::P_TIMESTAMP  => $this->data[self::P_TIMESTAMP],
        ];

        $new = empty($this->data[self::P_COMMENT_ID]);

        if(!$new){
            $set[self::P_COMMENT_ID] = $this->data[self::P_COMMENT_ID];
        }

        $logSet = print_r($set, true);
        Service::logger()->addWarning("Comment save : {$logSet}");

        return $this;
    }

    public function destroy()
    {
        Service::logger()
            ->addWarning("Comment destroy : user {$this->user->getId()} / item {$this->item->getId()}-{$this->item->getType()}");

        return $this;
    }

    /**
     * @param UserInterface        $user
     * @param CommentableInterface $item
     * @param                      $text
     * @param null                 $timestamp
     *
     * @return static
     */
    public static function add(UserInterface $user, CommentableInterface $item, $text, $timestamp = null)
    {
        $model = new static();
        $model->setUser($user);
        $model->setItem($item);
        $model->setText($text);
        $model->setTimestamp($timestamp);
        $model->save();

        return $model;
    }

    public static function findByMember(UserInterface $user)
    {
        // TODO: Implement findByMember() method.
    }

    public static function findByItem(CommentableInterface $item)
    {
        // TODO: Implement findByItem() method.
    }

}

