<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! config('services.recaptcha.enabled')) {
            return;
        }

        if (! is_string($value) || $value === '') {
            $fail('Por favor, verifique o reCaptcha.');

            return;
        }

        $secret = (string) config('services.recaptcha.secret_key');
        if ($secret === '') {
            return;
        }

        $response = Http::asForm()
            ->timeout(8)
            ->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

        if (! $response->successful() || ! ($response->json('success') ?? false)) {
            $fail('Por favor, verifique o reCaptcha.');
        }
    }
}
