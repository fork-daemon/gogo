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

}

