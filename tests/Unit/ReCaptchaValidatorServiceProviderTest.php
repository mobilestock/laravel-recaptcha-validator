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

dataset('datasetGoogleRequests', function () {
    return [
        'with success' => [['success' => true]],
        'and throw an exception when response is empty' => [[]],
    ];
});

it('should make request to google :dataset', function (array $response) {
    App::shouldReceive('isProduction')->once()->andReturn(true);
    Http::fake(['*' => Http::response($response, 200)]);

    Request::macro('validate', fn() => ['captcha_token' => 'token']);
    try {
        Request::validateCaptcha('secret');
    } catch (BadRequestHttpException $e) {
        $this->assertEquals('Por favor, realize a verificação do reCAPTCHA corretamente!', $e->getMessage());
    }
})->with('datasetGoogleRequests');
