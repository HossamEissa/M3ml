<?php

namespace App\Http\Requests;

use App\Rules\NoSpaces;
use App\Traits\responseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateFactoryRequest extends FormRequest
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
            'user_name' => ['required', 'string', new NoSpaces(), 'unique:factories,user_name'],
            'name' => '|string',
            'title' => '|string',
            'facebook' => 'nullable|string',
            'whatsApp' => 'nullable|string',
            'description' => '|string',
            'active' => 'required|boolean'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->returnValidationError($validator)
        );
    }
}
