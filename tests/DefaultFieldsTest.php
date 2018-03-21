<?php

namespace TheJawker\Interrogator\Test;

use Illuminate\Http\Request;
use TheJawker\Interrogator\Test\TestModels\User;
use TheJawker\Interrogator\Test\TestModels\UserFactory;

class DefaultFieldsTest extends TestCase
{
    /** @test */
    public function specific_fields_can_be_fetched()
    {
        $this->createUsers();

        $request = $this->selectFields('name,email');

        $user = interrogate(User::class)
            ->request($request)->query()->first()->toArray();

        $this->assertArrayHasKey('name', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayNotHasKey('value', $user);
    }

    private function selectFields(string $fields)
    {
        return new Request([
            'fields' => [$fields]
        ]);
    }

    private function createUsers()
    {
        UserFactory::create([
            'name' => 'Aaron Fritsen',
            'email' => 'z@z.com',
            'value' => 25
        ]);
        UserFactory::create([
            'name' => 'Piet Jensson',
            'email' => 'g@g.com',
            'value' => 50
        ]);
        UserFactory::create([
            'name' => 'Zara Gulf',
            'email' => 'a@a.com',
            'value' => 100
        ]);
        UserFactory::create([
            'name' => 'John Thombson',
            'email' => 'e@e.com',
            'value' => 100
        ]);
    }
}