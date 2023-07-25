<?php

namespace App\Http\Requests;

use App\Traits\responseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ForgetPasswordRequest extends FormRequest
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
            'phone' => 'required|numeric|digits:11|exists:users,phone_number',
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'من فضلك ادخل رقم الهاتف',
            'phone.numeric'=> 'يجب ان لا يحتوى رقم الهاتف على حروف',
            'phone.digits' => 'يجب الا يقل ولا يزيد رقم الهاتف عن 11 رقم',
            'phone.exists' => 'هذا الرقم غير موجود'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
             $this->returnValidationError($validator)
        );
    }
}
