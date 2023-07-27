<?php

namespace App\Http\Requests;

use App\Rules\NoSpaces;
use App\Traits\responseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterAdminRequest extends FormRequest
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
            'name' => ['required', 'exists:factories,name', 'string', new NoSpaces()],
            'phone' => 'required|digits:11|numeric',
            'password' => 'required|confirmed|min:8',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'هذا الحقل مطلوب',
            'name.exists' => 'هذا المعمل غير موجود لدينا',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->returnValidationError($validator)
        );
    }
}
