<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardRequest extends FormRequest
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
            'number.required' => 'O campo Número é Obrigatório',
            'holder_name.required' => 'O campo Nome do títular é Obrigatório',
            'holder_document.required' => 'O campo CPF do títular é Obrigatório',
            'exp_month.required' => 'O campo Mês de expiração é Obrigatório',
            'exp_year.required' => 'O campo Ano de expiração é Obrigatório',
            'cvv.required' => 'o campo CVV é Obrigatório',
            'brand.required' => 'Ocorreu um erro com a bandeira do cartão',
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
            case 'store_card':
                return [
                    'number' => 'required',
                    'holder_name' => 'required',
                    'holder_document' => 'required',
                    'exp_month' => 'required',
                    'exp_year' => 'required',
                    'cvv' => 'required',
                    "brand" => "required",
                ];
                break;
            case 'update_card':
                return [
                    'holder_name' => 'required',
                    'holder_document' => 'required',
                    'exp_month' => 'required',
                    'exp_year' => 'required',
                ];
            default:
                return [];
                break;
        endswitch;
    }
}
