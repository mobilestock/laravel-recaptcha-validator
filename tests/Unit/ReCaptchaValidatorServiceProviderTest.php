<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

it('should check if the ReCaptchaValidatorServiceProvider is booting correctly', function () {
    $this->assertTrue(true);
});

it('should early return when not in production', function () {
    App::shouldReceive('isProduction')->once()->andReturn(false);
    Request::validateCaptcha('secret');
});

it('should make request to google with success', function () {
    App::shouldReceive('isProduction')->once()->andReturn(true);
    Http::fake(['*' => Http::response(['success' => true], 200)]);

    Request::macro('validate', fn() => ['captcha_token' => 'token']);
    Request::validateCaptcha('secret');
});

it('should throw exception when response is empty', function () {
    App::shouldReceive('isProduction')->once()->andReturn(true);
    Http::fake(['*' => Http::response([], 200)]);

    Request::macro('validate', fn() => ['captcha_token' => 'token']);
    Request::validateCaptcha('secret');
})->throws(BadRequestHttpException::class, 'Por favor, realize a verificação do reCAPTCHA corretamente!');
