<?php

namespace App\Http\Requests;

use App\Traits\responseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class EditUserProfileRequest extends FormRequest
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
        $userId = auth('api')->id();
        return [
            'name' => 'string',
            'phone_number' => [
                'numeric',
                'digits:11',
                Rule::unique('users', 'phone_number')->ignore($userId),
            ],
            'date_of_birth' => 'string',
            'gender' => 'in:male,female',
            'password' => 'confirmed|min:8',
            'photo' => 'mimes:jpg,jpeg,png,gif,svg',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->returnValidationError($validator)
        );
    }
}
