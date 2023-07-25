<?php

namespace App\Http\Requests;

use App\Traits\responseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResetPasswordUserRequest extends FormRequest
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
            'id' => 'required|exists:users,id',
            'code' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'هذا الحقل مطلوب من فضلك ادخل الكود',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->returnValidationError($validator)
        );
    }

}
