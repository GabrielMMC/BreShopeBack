<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\UserAddressRequest;
use App\Models\Customer;
use App\Models\UserAddress;
use App\Models\UserData;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class UserAddressController extends Controller
{
    //Function to list all addresses
    public function list_addresses(Request $request)
    {
        try {
            $user = auth()->user();
            $customer = Customer::where('user_id', '=', $user->id)->first();

            $client = new Client();
            $addresses = $client->request('GET', 'https://api.pagar.me/core/v5/customers/' . $customer->customer_id . '/addresses?page=' . $request->page . '&size=5/', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Basic c2tfdGVzdF9hZHZNUVlQQzdVWUw3ejJiOg=='
                ],
            ]);
            $addresses = json_decode($addresses->getBody());

            return response()->json(['status' => true, 'addresses' => $addresses]);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'error' => $ex]);
        }
    }

    //Function to store an address
    public function store_address(AddressRequest $request)
    {
        $data = $request->validated();
        try {
            $user = auth()->user();
            $customer = Customer::where('user_id', '=', $user->id)->with(['address'])->first();

            $client = new Client();
            $address = $client->request('POST', 'https://api.pagar.me/core/v5/customers/' . $customer->customer_id . '/addresses', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic c2tfdGVzdF9hZHZNUVlQQzdVWUw3ejJiOg=='
                ],
                'json' => [
                    "zip_code" => $data['zip_code'],
                    "city" => $data['city'],
                    "state" => $data['state'],
                    "country" => $data['country'],
                    "line_1" => $data['number'] . ', ' . $data['street'] . ', ' . $data['neighborhood'],
                    "line_2" => empty($data['complement']) ? '' : $data['complement']
                ],
                "http_errors" => false
            ]);
            $address = json_decode($address->getBody());

            $newAddress = new UserAddress();
            $newAddress->fill(['customer_id' => $customer->id, 'address_id' => $address->id])->save();

            return response()->json(['status' => true, 'address' => $address]);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'error' => $ex]);
        }
    }

    //Function to update an address
    public function update_address(AddressRequest $request, $id)
    {
        $data = $request->validated();
        try {
            $user = auth()->user();
            $customer = Customer::where('user_id', '=', $user->id)->first();

            $client = new Client();
            $address = $client->request('PUT', 'https://api.pagar.me/core/v5/customers/' . $customer->customer_id . '/addresses/' . $id, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic c2tfdGVzdF9hZHZNUVlQQzdVWUw3ejJiOg=='
                ],
                'json' => [
                    "zip_code" => $data['zip_code'],
                    "city" => $data['city'],
                    "state" => $data['state'],
                    "country" => $data['country'],
                    "line_1" => $data['number'] . ', ' . $data['street'] . ', ' . $data['neighborhood'],
                    "line_2" => empty($data['complement']) ? '' : $data['complement']
                ],
                "http_errors" => false
            ]);
            $address = json_decode($address->getBody());

            return response()->json(['status' => true, 'address' => $address]);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'error' => $ex]);
        }
    }

    //Function to delete an address
    public function delete_address($id)
    {
        try {
            $user = auth()->user();
            $customer = Customer::where('user_id', '=', $user->id)->first();

            $client = new Client();
            $address = $client->request('DELETE', 'https://api.pagar.me/core/v5/customers/' . $customer->customer_id . '/addresses/' . $id, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Basic c2tfdGVzdF9hZHZNUVlQQzdVWUw3ejJiOg=='
                ],
            ]);
            $address = json_decode($address->getBody());

            return response()->json(['status' => true]);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'error' => $ex]);
        }
    }
}
