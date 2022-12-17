<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecipientRequest extends FormRequest
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
            case 'store_recipient':
                return [
                    'name' => 'required',
                    'document' => 'required',
                    'account_check_digit' => 'required',
                    'branch_check_digit' => 'required',
                    'account_number' => 'required',
                    'branch_number' => 'required',
                    'bank' => 'required',
                ];
                break;
            default:
                return [];
                break;
        endswitch;
    }
}
