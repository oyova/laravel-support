<?php

use App\Models\User;
use Illuminate\Support\Str;

if (! function_exists('isDebug')) {
    function isDebug(): bool
    {
        return config('app.debug');
    }
}

if (! function_exists('isLocal')) {
    function isLocal(): bool
    {
        // Using config() instead of app()->environment() to allow for earlier
        // access.
        return config('app.env') === 'local';
    }
}

if (! function_exists('isTesting')) {
    function isTesting(): bool
    {
        // Using config() instead of app()->environment() to allow for earlier
        // access.
        return config('app.env') === 'testing';
    }
}

if (! function_exists('isStaging')) {
    function isStaging(): bool
    {
        // Using config() instead of app()->environment() to allow for earlier
        // access.
        return config('app.env') === 'staging';
    }
}

if (! function_exists('isProduction')) {
    function isProduction(): bool
    {
        // Using config() instead of app()->environment() to allow for earlier
        // access.
        return config('app.env') === 'production';
    }
}

if (! function_exists('authUser')) {
    function authUser(): ?User
    {
        return auth()->user();
    }
}

if (! function_exists('authUserId')) {
    function authUserId(): ?int
    {
        return auth()->check() ? authUser()->id : null;
    }
}

if (! function_exists('str')) {
    /**
     * This function exists in Laravel >= 9.
     */
    function str($string = null)
    {
        if (func_num_args() === 0) {
            return new class()
            {
                public function __call($method, $parameters)
                {
                    return Str::$method(...$parameters);
                }

                public function __toString()
                {
                    return '';
                }
            };
        }

        return Str::of($string);
    }
}
