<?php
namespace App;


use App\Comment\Comment;
use App\News\News;
use App\User\User;

class CommentTest extends \Codeception\TestCase\Test
{

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testMe()
    {
        $user = new User();
        $item = new News();
        Comment::add($user, $item , "asd");

        $this->assertTrue(true);
    }
}