<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'country.required' => 'O campo País é Obrigatório',
            'state.required' => 'O campo Estado é Obrigatório',
            'city.required' => 'O campo Cidade é Obrigatório',
            'zip_code.required' => 'O campo CEP é Obrigatório',
            'number.required' => 'o campo Número é Obrigatório',
            'street.required' => 'o campo Rua é Obrigatório',
            'neighborhood.required' => 'o Bairro Número é Obrigatório',
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
            case 'store_address':
                return [
                    'country' => 'required',
                    'state' => 'required',
                    'city' => 'required',
                    'zip_code' => 'required',
                    'number' => 'required',
                    "street" => "required",
                    "neighborhood" => "required",
                    "complement" => 'sometimes'
                ];
                break;
            case 'update_address':
                return [

                    'country' => 'required',
                    'state' => 'required',
                    'city' => 'required',
                    'zip_code' => 'required',
                    'number' => 'required',
                    "street" => "required",
                    "neighborhood" => "required",
                    "complement" => 'sometimes'
                ];
                break;
            default:
                return [];
                break;
        endswitch;
    }
}
