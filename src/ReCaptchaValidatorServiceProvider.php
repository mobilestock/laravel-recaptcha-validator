<?php

namespace MobileStock\LaravelReCaptchaValidator;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ReCaptchaValidatorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Request::macro('validateCaptcha', function (string $captchaSecret): void {
            if (!App::isProduction()) {
                return;
            }

            $data = Request::validate(['captcha_token' => ['required', 'string']]);
            $response = Http::get('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $captchaSecret,
                'response' => $data['captcha_token'],
            ])->json();

            if (empty($response) || !$response['success']) {
                throw new BadRequestHttpException('Por favor, realize a verificação do reCAPTCHA corretamente!');
            }
        });
    }
}
