<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAddressRequest extends FormRequest
{
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
        return [];
    }

    public function rules()
    {
        switch (strtolower($this->route()->getActionMethod())):
            case 'store_user_address':
                return [
                    'zip_code' => 'required|integer',
                    'state' => 'required|string',
                    'city' => 'required|string',
                    'nbhd' => 'required|string',
                    'street' => 'required|string',
                    'number' => 'required|integer',
                ];
                break;
            case 'update_user_address':
                return [
                    'zip_code' => 'required|integer',
                    'state' => 'required|string',
                    'city' => 'required|string',
                    'nbhd' => 'required|string',
                    'street' => 'required|string',
                    'number' => 'required|integer',
                    'id' => 'required',
                ];
                break;
            default:
                return [];
                break;
        endswitch;
    }
}
