<?php

namespace TheJawker\Interrogator\Test;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpKernel\Exception\HttpException;
use TheJawker\Interrogator\Test\TestModels\User;
use TheJawker\Interrogator\Test\TestModels\UserFactory;

class SortingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function results_can_be_ordered_by_a_field()
    {
        $this->createUsers();

        $request = $this->setSort('email');

        $users = interrogate(User::query())
            ->request($request)
            ->get();

        $this->assertSort($users, 'email');
    }

    /** @test */
    public function results_can_be_ordered_descending()
    {
        $this->createUsers();

        $request = $this->setSort('-name');

        $users = interrogate(User::query())
            ->request($request)
            ->get();

        $this->assertSort($users, 'name', 'desc');
    }
    
    /** @test */
    public function specific_sort_types_can_be_allowed()
    {
        $this->expectException(HttpException::class);

        $this->createUsers();

        $request = $this->setSort('!name');

        interrogate(User::query())
            ->request($request)
            ->allowSortBy(['email'])
            ->get();
    }
    
    /** @test */
    public function default_sorting_can_be_defined()
    {
        $this->createUsers();

        $users = interrogate(User::query())
            ->defaultSortBy('-email')
            ->get();

        $this->assertSort($users, 'email', 'desc');
    }

    private function assertSort(Collection $collection, $key, $order = 'asc')
    {
        $sorted = User::all()
            ->sortBy($key, null, $order !== 'asc')
            ->values()->all();

        $this->assertEquals($sorted, $collection->values()->all(), 'Order does not match.');
    }

    private function setSort($sortBy)
    {
        return new Request([
            'sort' => $sortBy
        ]);
    }

    private function createUsers()
    {
        UserFactory::create([
            'name' => 'A',
            'email' => 'z@z.com',
            'value' => 50
        ]);
        UserFactory::create([
            'name' => 'Z',
            'email' => 'a@a.com',
            'value' => 50
        ]);
    }
}