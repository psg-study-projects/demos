<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AlphaSpaces implements Rule
{
    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        return preg_match('/^[\pL\s,]+$/u', $value);
    }

    public function message()
    {
        return 'The :attribute must only contain alpha characters or whitespace.';
    }
}
/*
         //\Schema::defaultStringLength(191); // %PSG only
        // Custom validators
        //   https://laravel.com/docs/5.2/validation#custom-validation-rules
        \Validator::extend('alpha_spaces', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[\pL\s,]+$/u', $value);
        });
 */

