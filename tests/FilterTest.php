<?php

namespace TheJawker\Interrogator\Test;

use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpKernel\Exception\HttpException;
use TheJawker\Interrogator\Test\TestModels\User;
use TheJawker\Interrogator\Test\TestModels\UserFactory;

class FilterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_filter_can_be_used()
    {
        $this->createUsers();

        $request = $this->setFilter('name', 'Zara Gulf');

        $users = interrogate(User::query())
            ->request($request)
            ->get();

        $this->assertCount(1, $users);
        $this->assertEquals('Zara Gulf', $users->first()->name);
    }

    /** @test */
    public function wild_cards_can_be_used_in_querying()
    {
        $this->createUsers();

        $request = $this->setFilter('name', '*ara*');

        $users = interrogate(User::query())
            ->request($request)
            ->get();

        $this->assertCount(1, $users);
        $this->assertEquals('Zara Gulf', $users->first()->name);
    }

    /** @test */
    public function multiple_filters_default_to_or_operator()
    {
        $this->createUsers();

        $request = $this->setFilters([
            'name' => 'Zara Gulf',
            'email' => 'g@*',
        ]);

        $users = interrogate(User::query())
            ->request($request)
            ->get();

        $this->assertCount(2, $users);
    }

    /** @test */
    public function an_or_where_can_be_specified()
    {
        $this->createUsers();

        $request = $this->setFilters([
            'name' => 'Zara Gulf',
            'email' => '[or]g@*',
        ]);

        $users = interrogate(User::query())
            ->request($request)
            ->get();

        $this->assertCount(2, $users);
    }

    /** @test */
    public function an_and_where_can_be_specified()
    {
        $this->createUsers();

        $request = $this->setFilters([
            'name' => 'John Thombson',
            'value' => '[and][ge]100',
        ]);

        $users = interrogate(User::query())
            ->request($request)
            ->get();

        $this->assertCount(1, $users);
    }

    /** @test */
    public function filters_can_be_allowed()
    {
        $this->expectException(HttpException::class);

        $this->createUsers();

        $request = $this->setFilter('name', 'Zara Gulf');

        $users = interrogate(User::query())
            ->request($request)
            ->allowFilters(['email'])
            ->get();

        $this->assertCount(1, $users);
        $this->assertEquals('Zara Gulf', $users->first()->name);
    }

    /** @test */
    public function where_clauses_are_nested_in_master_where()
    {
        UserFactory::create([])->posts()->createMany([
            ['body' => 'aaaa'],
            ['body' => 'bbbb'],
        ]);

        $request = $this->setFilter('body', 'aaaa');

        $this->assertCount(1, interrogate(User::first()->posts())->request($request)->get());
    }

    /** @test */
    public function a_list_of_values_can_be_used()
    {
        $this->createUsers();

        $request = $this->setFilter('id', '1,2');

        $users = interrogate(User::query())
            ->request($request)
            ->get();

        $this->assertCount(2, $users);
    }

    /** @test */
    public function explicit_math_operators_can_be_used()
    {
        $this->createUsers();

        $this->assertCount(3, interrogate(User::query())->request($this->setFilter('value', '[ge]50'))->get());
        $this->assertCount(2, interrogate(User::query())->request($this->setFilter('value', '[gt]50'))->get());
        $this->assertCount(2, interrogate(User::query())->request($this->setFilter('value', '[le]50'))->get());
        $this->assertCount(1, interrogate(User::query())->request($this->setFilter('value', '[lt]50'))->get());
    }

    /** @test */
    public function default_filters_can_be_set()
    {
        $this->createUsers();

        $users = interrogate(User::class)
            ->defaultFilters([
                'email' => 'g@g.com'
            ])
            ->get();

        $this->assertCount(1, $users);
        $this->assertEquals('Piet Jensson', $users->first()->name);
    }

    /** @test */
    public function null_filter_works_as_expected()
    {
        $this->createUsers();
        UserFactory::create([
            'name' => null
        ]);

        $request = $this->setFilter('name', '[null]');

        $users = interrogate(User::class)
            ->request($request)
            ->get();

        $this->assertCount(1, $users);
    }

    /** @test */
    public function null_can_be_used_in_combination_with_an_and_operator()
    {
        $this->createUsers();

        $request = $this->setFilters([
            'value' => 100,
            'verified_at' => '[and][null]'
        ]);

        $users = interrogate(User::class)
            ->request($request)
            ->get();

        $this->assertCount(1, $users);
        $user = $users->first();
        $this->assertEquals(100, $user->value);
        $this->assertEquals('John Thombson', $user->name);
    }

    /** @test */
    public function can_also_do_not_null()
    {
        $this->createUsers();

        $request = $this->setFilters([
            'value' => 100,
            'verified_at' => '[and][!null]'
        ]);

        $users = interrogate(User::class)
            ->request($request)
            ->get();

        $this->assertCount(1, $users);
        $user = $users->first();
        $this->assertEquals(100, $user->value);
        $this->assertEquals('Zara Gulf', $user->name);
    }

    private function createUsers()
    {
        UserFactory::create([
            'name' => 'Aaron Fritsen',
            'email' => 'z@z.com',
            'value' => 25,
            'verified_at' => now()
        ]);
        UserFactory::create([
            'name' => 'Piet Jensson',
            'email' => 'g@g.com',
            'value' => 50,
            'verified_at' => null
        ]);
        UserFactory::create([
            'name' => 'Zara Gulf',
            'email' => 'a@a.com',
            'value' => 100,
            'verified_at' => now()
        ]);
        UserFactory::create([
            'name' => 'John Thombson',
            'email' => 'e@e.com',
            'value' => 100,
            'verified_at' => null
        ]);
    }

    private function setFilters($array)
    {
        return new Request([
            'filter' => $array
        ]);
    }

    private function setFilter($key, $value)
    {
        return $this->setFilters([
            $key => $value
        ]);
    }
}