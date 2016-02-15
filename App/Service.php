<?php

namespace App;

use App\Exception\ImplementException;

class Service
{

    public function db()
    {
        throw new ImplementException();
    }

    public function redis()
    {
        throw new ImplementException();
    }

    public function elastic()
    {
        throw new ImplementException();
    }

}