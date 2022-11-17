<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'email.required' => 'O campo E-mail é obrigatório',
            'email.email' => 'O campo E-mail deve ser um E-mail válido',
            'password.required' => 'O campo Senha é obrigatório',
            'password.min' => 'O campo Senha deve ter no mínimo 6 caractéres',
            'password.max' => 'O campo Senha deve ter no máximo 50 caractéres',
        ];
    }

    public function rules()
    {
        switch (strtolower($this->route()->getActionMethod())):
            case 'login':
                return [
                    'email' => 'required|email',
                    'password' => 'required|min:6|max:100',
                ];
                break;
            case 'register':
                return [
                    'name' => 'required|string',
                    'email' => 'required|email',
                    'password' => 'required|string|min:6|max:100',
                ];
                break;
            case 'edit_profile':
                return [
                    'name' => 'required|string',
                    'password' => 'sometimes|nullable|string|min:6|max:100|confirmed',
                    'imagem' => 'sometimes|nullable|image'
                ];
                break;

            default:
                return [];
                break;
        endswitch;
    }
}
