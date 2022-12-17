<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //Main function to decide if the customer be created or updated
    public function store_customer(CustomerRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();
        $customer = Customer::where('user_id', '=', $user->id)->first();

        if (empty($customer)) {
            $response = $this->store($user, $data, 'POST', '');
        } else {
            $response = $this->store($user, $data, 'PUT', $customer->customer_id);
        }

        return response()->json(['status' => true, 'customer' => $response]);
    }

    //Function to get the customer linked to logged user
    public function get_customer()
    {
        $user = auth()->user();
        $customer = Customer::where('user_id', '=', $user->id)->first();

        $client = new Client();
        $customer = $client->request('GET', 'https://api.pagar.me/core/v5/customers/' . $customer->customer_id, [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Basic c2tfdGVzdF9hZHZNUVlQQzdVWUw3ejJiOg=='
            ],
        ]);
        $customer = json_decode($customer->getBody());
        return $customer;
    }

    //Function to store the customer
    public function store($user, $data, $method, $id)
    {
        $client = new Client();
        $customer = $client->request($method, 'https://api.pagar.me/core/v5/customers/' . $id, [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic c2tfdGVzdF9hZHZNUVlQQzdVWUw3ejJiOg=='
            ],
            'json' => [
                "phones" => [
                    "mobile_phone" => [
                        "country_code" => $data['country_code'],
                        "area_code" => $data['area_code'],
                        "number" => $data['number']
                    ],
                ],
                "birthdate" => $data['birthdate'],
                "name" => $data['name'],
                "email" => $user['email'],
                "code" => $user['id'],
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
