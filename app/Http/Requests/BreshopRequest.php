<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BreshopRequest extends FormRequest
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
            'name.required' => 'O campo nome é obrigatório',
            'number.required' => 'O campo número é obrigatório',
            'cep.required' => 'O campo CEP é obrigatório',
            'state.required' => 'O campo estado é obrigatório',
            'city.required' => 'O campo cidade é obrigatório',
            'description.required' => 'O campo descrição é obrigatório'
        ];
    }

    public function rules()
    {
        switch (strtolower($this->route()->getActionMethod())):
            case 'store_breshop':
                return [
                    'user_id' => 'required',
                    'name' => 'required',
                    'number' => 'required',
                    'cep' => 'required',
                    'state' => 'required',
                    'city' => 'required',
                    'description' => 'required',
                    'file' => 'sometimes'
                ];
                break;

            default:
                return [];
                break;
        endswitch;
    }
}
