<?php

namespace TheJawker\Interrogator\Test;

use Illuminate\Database\Eloquent\Builder;
use TheJawker\Interrogator\Test\TestModels\User;
use TheJawker\Interrogator\Test\TestModels\UserFactory;

class HelperTest extends TestCase
{
    /** @test */
    public function accepts_a_query_builder_object()
    {
        $interrogator = interrogate(User::query());

        $this->assertInstanceOf(Builder::class, $interrogator->builder);
    }

    /** @test */
    public function accepts_a_relation()
    {
        $this->createUserAndPost();
        $relation = User::first()->posts();

        $interrogator = interrogate($relation);

        $this->assertInstanceOf(Builder::class, $interrogator->builder);
    }

    /** @test */
    public function accepts_a_model_class_constant()
    {
        $classConstant = User::class;

        $interrogator = interrogate($classConstant);

        $this->assertInstanceOf(Builder::class, $interrogator->builder);
    }

    /** @test */
    public function accepts_a_model()
    {
        $interrogator = interrogate(new User());

        $this->assertInstanceOf(Builder::class, $interrogator->builder);
    }

    private function createUserAndPost(): void
    {
        UserFactory::create()->posts()->create([
            'body' => 'Some story or something'
        ]);
    }
}