<?php

namespace App\User;

class User implements UserInterface
{
    public function getId()
    {
        return 'item-123';
    }

    public function getName()
    {
        return 'xxx';
    }

    public function getMail()
    {
        // TODO: Implement getMail() method.
    }

    public function save()
    {
        // TODO: Implement save() method.
    }

    public function destroy()
    {
        // TODO: Implement destroy() method.
    }

    public static function create($name, $mail)
    {
        // TODO: Implement create() method.
    }

    public static function verify($hash)
    {
        // TODO: Implement verify() method.
    }

    public static function findById($id)
    {
        // TODO: Implement findById() method.
    }

    public static function findByMail($mail)
    {
        // TODO: Implement findByMail() method.
    }

}

