<?php

namespace TheJawker\Interrogator\Test;

class UserFactory
{
    public static function create($array)
    {
        return User::create($array);
    }
}