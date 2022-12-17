<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class OrderRequest extends FormRequest
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
            'name.required' => 'O campo Nome é Obrigatório',
            'document.required' => 'O campo CPF/CNPJ é Obrigatório',
            'account_check_digit.required' => 'o campo Código da conta é Obrigatório',
            'branch_check_digit.required' => 'o campo Código da agência é Obrigatório',
            'account_number.required' => 'o campo Número da conta é Obrigatório',
            'branch_number.required' => 'o campo Número da agência é Obrigatório',
            'bank.required' => 'o campo Código do banco é Obrigatório',
            'country.required' => 'O campo País é Obrigatório',
            'state.required' => 'O campo Estado é Obrigatório',
            'city.required' => 'O campo Cidade é Obrigatório',
            'zip_code.required' => 'O campo CEP é Obrigatório',
            'number.required' => 'o campo Número é Obrigatório',
            'street.required' => 'o campo Rua é Obrigatório',
            'neighborhood.required' => 'o Bairro Número é Obrigatório',

            'boleto.required' => 'o Boleto é Obrigatório',
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
            case 'store_order':
                if ($this->paymant_method == 'credit_card') {
                    return [
                        'items' => 'required',
                        'number' => [new RequiredIf(empty($this->card_id))],
                        'holder_name' => [new RequiredIf(empty($this->card_id))],
                        'holder_document' => [new RequiredIf(empty($this->card_id))],
                        'exp_month' => [new RequiredIf(empty($this->card_id))],
                        'exp_year' => [new RequiredIf(empty($this->card_id))],
                        'cvv' => [new RequiredIf(empty($this->card_id))],
                        'installments' => 'required',
                        'address_id' => 'sometimes',
                        'card_id' => 'sometimes',
                        'country' => 'sometimes',
                        'state' => 'sometimes',
                        'city' => 'sometimes',
                        'zip_code' => 'sometimes',
                        'number' => 'sometimes',
                        "street" => "sometimes",
                        "neighborhood" => "sometimes",
                        "complement" => 'sometimes',
                        "paymant_method" => 'required',
                    ];
                    break;
                } else if ($this->paymant_method == 'boleto') {
                    return [
                        "boleto" => 'required',
                        "paymant_method" => 'required',
                    ];
                    break;
                }
            default:
                return [];
                break;
        endswitch;
    }
}
