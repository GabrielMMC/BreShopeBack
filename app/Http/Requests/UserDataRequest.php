<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserDataRequest extends FormRequest
{
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
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'name.required' => 'O campo Nome é obrigatório',
            'document.required' => 'O campo CPF é obrigatório',
            'birthdate.required' => 'O campo Data de Nascimento é obrigatório',
            'gender.required' => 'O campo Sexo é obrigatório',
            'area_code.required' => 'O campo DDD é obrigatório',
            'number.required' => 'O campo Número é obrigatório',
        ];
    }

    public function rules()
    {
        switch (strtolower($this->route()->getActionMethod())):
            case 'store_user_data':
                return [
                    'name' => 'required|string',
                    'email' => 'required|string',
                    'document' => 'required|string',
                    'birthdate' => 'required|string',
                    'gender' => 'required|string',
                    'file' => 'sometimes|file',
                    'area_code' => 'required|integer',
                    'number' => 'required|integer',
                ];
                break;

            case 'update_user_data':
                return [
                    'name' => 'required|string',
                    'email' => 'required|string',
                    'document' => 'required|string',
                    'birthdate' => 'required|string',
                    'gender' => 'required|string',
                    'file' => 'sometimes|file',
                    'area_code' => 'required|integer',
                    'number' => 'required|integer',
                    'id' => 'sometimes|string'
                ];
                break;
            default:
                return [];
                break;
        endswitch;
    }
}
