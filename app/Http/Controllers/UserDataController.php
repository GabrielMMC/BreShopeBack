<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserDataRequest;
use App\Http\Resources\UserDataResource;
use App\Models\Customer;
use App\Models\User;
use App\Models\UserData;
use App\Models\UserPhone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class UserDataController extends Controller
{
    // public function get_user_data()
    // {
    //     try {
    //         $user = auth()->user();
    //         $userData = UserData::where('user_id', '=', $user->id)->get();

    //         return response()->json(['status' => true, 'user_data' => UserDataResource::collection($userData)]);
    //     } catch (Exception $ex) {
    //         return response()->json(['status' => false, 'error' => $ex]);
    //     }
    // }

    public function get_user_data()
    {
        $user = auth()->user();
        $customer = Customer::where('user_id', '=', $user->id)->first();

        if (isset($customer)) {
            $client = new Client();
            $customer = $client->request('GET', 'https://api.pagar.me/core/v5/customers/' . $customer->customer_id, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Basic c2tfdGVzdF9hZHZNUVlQQzdVWUw3ejJiOg=='
                ],
            ]);
            $customer = json_decode($customer->getBody());
            return response()->json(['status' => true, 'user_data' => $user, 'customer' => $customer]);
        } else {
            return response()->json(['status' => false, 'user_data' => $user]);
        }
    }

    public function store_user_data(UserDataRequest $request)
    {
        // try {
        $data = $request->validated();
        $user = auth()->user();
        $new_user = User::where('id', '=', $user->id)->first();

        $new_user->fill($data);
        if ($request->file('file_path')) {
            $document = $request->file('file_path');
            $name = uniqid('photo_') . '.' . $document->getClientOriginalExtension();
            $new_user->file = $document->storeAs('photos', $name, ['disk' => 'public']);
        }
        $new_user->save();

        $customer = $this->store_customer($user, $data, 'POST');
        return response()->json(['status' => true, 'customer' => $customer]);
        // } catch (Exception $ex) {
        //     return response()->json(['status' => false, 'error' => $ex]);
        // }
    }

    public function update_user_data(UserDataRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();
        $new_user = User::where('id', '=', $user->id)->first();

        $new_user->fill($data);
        if ($request->file('file_path')) {
            $document = $request->file('file_path');
            $name = uniqid('photo_') . '.' . $document->getClientOriginalExtension();
            $new_user->file = $document->storeAs('photos', $name, ['disk' => 'public']);
        }
        $new_user->save();

        $customer = $this->store_customer($user, $data, 'PUT');
        return response()->json(['status' => true, 'customer' => $customer]);
    }

    public function store_customer($user, $data, $method)
    {
        $client = new Client();
        $customer = $client->request($method, 'https://api.pagar.me/core/v5/customers', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic c2tfdGVzdF9hZHZNUVlQQzdVWUw3ejJiOg=='
            ],
            'json' => [
                "phones" => [
                    "mobile_phone" => [
                        "country_code" => '55',
                        "area_code" => $data['area_code'],
                        "number" => $data['number']
                    ],
                ],
                "birthdate" => $data['birthdate'],
                "name" => $data['name'],
                "email" => $data['email'],
                "code" => $user->id,
                "document" => $data['document'],
                "document_type" => "CPF",
                "type" => "individual",
                "gender" => $data['gender']
            ],
            "http_errors" => false
        ]);
        $customer = json_decode($customer->getBody());
        if ($method == 'POST') {
            $newCustomer = new Customer();
            $newCustomer->fill(['customer_id' => $customer->id, 'user_id' => $user->id])->save();
        }
        return $customer;
    }
}

// "country_code" => $phone->country_code,
//                         "area_code" => $phone->area_code,
//                         "number" => $phone->number_code
