<?php

namespace Tests;

use MobileStock\LaravelReCaptchaValidator\ReCaptchaValidatorServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [ReCaptchaValidatorServiceProvider::class];
    }
}
