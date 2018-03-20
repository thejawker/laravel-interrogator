<?php

namespace TheJawker\Interrogator\Test;

use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
    public function multiple_filters_can_be_used()
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

    private function createUsers()
    {
        UserFactory::create([
            'name' => 'Aaron Fritsen',
            'email' => 'z@z.com'
        ]);
        UserFactory::create([
            'name' => 'Piet Jensson',
            'email' => 'g@g.com'
        ]);
        UserFactory::create([
            'name' => 'Zara Gulf',
            'email' => 'a@a.com'
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