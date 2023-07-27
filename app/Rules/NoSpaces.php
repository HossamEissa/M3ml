<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoSpaces implements Rule
{

    public function passes($attribute, $value)
    {
        return !preg_match('/\s/', $value);
    }

    public function message()
    {
        return  'الاسم يجب الايحتوى على مسافة';
    }
}
