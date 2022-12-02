<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserDataRequest;
use App\Http\Resources\UserDataResource;
use App\Models\Customer;
use App\Models\UserData;
use App\Models\UserPhone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class UserDataController extends Controller
{
    public function get_user_data()
    {
        try {
            $user = auth()->user();
            $userData = UserData::where('user_id', '=', $user->id)->get();

            return response()->json(['status' => true, 'user_data' => UserDataResource::collection($userData)]);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'error' => $ex]);
        }
    }

    public function store_user_data(UserDataRequest $request)
    {
        // try {
        $data = $request->validated();
        $user = auth()->user();

        $userData = new UserData();
        $userPhone = new UserPhone();
        $userData->fill(['user_id' => $user->id])->fill($data);

        if ($request->file('file_path')) {
            $document = $request->file('file_path');
            $name = uniqid('photo_') . '.' . $document->getClientOriginalExtension();
            $userData->file_path = $document->storeAs('photos', $name, ['disk' => 'public']);
        }
        $userData->save();
        $userPhone->fill(['user_data_id' => $userData->id])->fill($data)->save();

        $customer = $this->store_customer($user, $userData, $userPhone);
        return response()->json(['status' => true, 'customer' => $customer]);
        // } catch (Exception $ex) {
        //     return response()->json(['status' => false, 'error' => $ex]);
        // }
    }

    public function update_user_data(UserDataRequest $request)
    {
        $data = $request->validated();

        $userData = UserData::where('id', '=', $data['id'])->first();
        $userPhone = UserPhone::where('user_data_id', '=', $userData->id)->first();
        $userData->fill($data);

        if ($request->file('file_path')) {
            $document = $request->file('file_path');
            $name = uniqid('photo_') . '.' . $document->getClientOriginalExtension();
            $userData->file_path = $document->storeAs('photos', $name, ['disk' => 'public']);
        }
        $userData->save();
        $userPhone->fill($data)->save();

        return response()->json(['status' => true]);
    }

    public function store_customer($user, $data, $phone)
    {
        $client = new Client();
        $customer = $client->request('POST', 'https://api.pagar.me/core/v5/customers', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic c2tfdGVzdF9hZHZNUVlQQzdVWUw3ejJiOg=='
            ],
            'json' => [
                "phones" => [
                    "mobile_phone" => [
                        "country_code" => $phone->country_code,
                        "area_code" => $phone->area_code,
                        "number" => $phone->number
                    ],
                ],
                "birthdate" => $data->birthdate,
                "name" => $data->name,
                "email" => $user->email,
                "code" => $user->id,
                "document" => $data->document,
                "document_type" => "CPF",
                "type" => "individual",
                "gender" => $data->gender
            ],
            "http_errors" => false
        ]);

        $customer = json_decode($customer->getBody());
        $newCustomer = new Customer();
        $newCustomer->fill(['customer_id' => $customer->id, 'user_id' => $user->id])->save();
        return $customer;
    }
}

// "country_code" => $phone->country_code,
//                         "area_code" => $phone->area_code,
//                         "number" => $phone->number_code
