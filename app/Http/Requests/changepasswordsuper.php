<?php

namespace App\Http\Requests;

use App\Traits\responseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class changepasswordsuper extends FormRequest
{
    use responseTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' =>'required|exists:factories,user_name',
            'password' => 'required|confirmed|min:8',
        ];
    }
    public function messages()
    {
        return [
            'name.exists' => 'من فضلك ادخل اسم معمل موجود',
            'password.required' => 'من فضلك ادخل الرقم السرى الجديد' ,
            'password.confirmed' => 'الرقم السرى غير متطابق',
            'password.min' => 'يجب الايقل عن 8 حروف او ارقام',
        ] ;
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->returnValidationError($validator)
        );
    }
}
