<?php

namespace Tests;

use Illuminate\Contracts\Container\Container;
use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public $oldExceptionHandler;

    protected function signIn($user = null)
    {
        $user = $user ?: create('App\User');

        $this->actingAs($user);

        return $this;
    }

    protected function setUp()
    {
        parent::setUp();
        $this->disableExceptionHandling();
    }

    public function disableExceptionHandling()
    {
        $this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);

        $this->app->instance(ExceptionHandler::class, new class extends Handler {
           public function __construct()
           {}
           public function report(\Exception $exception)
           {}
           public function render($request, \Exception $exception)
           {
                throw $exception;
           }
        });
    }

    public function withExceptionHandler()
    {
        $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);

        return $this;
    }
}
