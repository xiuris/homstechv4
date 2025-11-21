<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
 codex/create-laravel-11-project-with-base-routes-00l5fh
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;
=======

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
 main
}
