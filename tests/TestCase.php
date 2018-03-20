<?php

namespace TheJawker\Interrogator\Test;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /** @var MockInterface spy */
    public $spy;
    public function setUp()
    {
        parent::setUp();
        $this->spy = Mockery::spy();

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->timestamps();
        });
    }
    public function tearDown()
    {
        Mockery::close();
    }
    protected function getPackageProviders($app)
    {
//        return [RouteModuleMacroServiceProvider::class];
    }
}