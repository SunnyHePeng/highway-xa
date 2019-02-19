<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'manage/file/*',
        'api/*',
        'snbhz/file/*',
        'notice/file/*',
        'smog/*/upload',
        'stretch/file/*',
        'mudjack/file/*'
    ];
}
