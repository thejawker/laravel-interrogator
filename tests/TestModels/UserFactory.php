<?php

namespace TheJawker\Interrogator\Test\TestModels;

class UserFactory
{
    public static function create($overrides = [])
    {
        return User::create(array_merge([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'value' => 23
        ], $overrides));
    }
}