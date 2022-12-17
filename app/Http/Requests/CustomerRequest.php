<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
    public function messages()
    {
        return [
            'birthdate.required' => 'O campo Data de Nascimento é Obrigatório',
            'name.required' => 'O campo Nome é Obrigatório',
            'document.required' => 'O campo CPF é Obrigatório',
            'gender.required' => 'O campo Gênero é Obrigatório',
            'country_code.required' => 'o campo Código do País é Obrigatório',
            'area_code.required' => 'o campo DDD é Obrigatório',
            'number.required' => 'o campo Número é Obrigatório',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch (strtolower($this->route()->getActionMethod())):
            case 'store_customer':
                return [

                    'birthdate' => 'required',
                    'name' => 'required',
                    'document' => 'required',
                    'gender' => 'required',
                    'country_code' => 'required',
                    "area_code" => "required",
                    "number" => "required"
                ];
                break;
            default:
                return [];
                break;
        endswitch;
    }
}
