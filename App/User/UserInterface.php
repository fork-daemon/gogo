<?php

namespace App\User;

interface UserInterface
{
    public function getId();

    public function getName();

    public function getMail();

    // record

    public function save();

    public function destroy();

    // static

    public static function create($name, $mail);

    public static function verify($hash);

    public static function findById($id);

    public static function findByMail($mail);

}
