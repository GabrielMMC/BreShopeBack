<?php

namespace App\Http\Controllers;

use App\Http\Requests\CardRequest;
use App\Models\Card;
use App\Models\Customer;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class CardController extends Controller
{
    //Funtion to list all cards
    public function list_cards()
    {
        try {
            $user = auth()->user();
            $customer = Customer::where('user_id', '=', $user->id)->first();

            $client = new Client();
            $cards = $client->request('GET', 'https://api.pagar.me/core/v5/customers/' . $customer->customer_id . '/cards/', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Basic c2tfdGVzdF9hZHZNUVlQQzdVWUw3ejJiOg=='
                ],
            ]);
            $cards = json_decode($cards->getBody());

            return response()->json(['status' => true, 'cards' => $cards]);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'error' => $ex]);
        }
    }

    //Function to decide if go store or update card
    public function store_card(CardRequest $request)
    {
        $data = $request->validated();
        try {
            $user = auth()->user();
            $customer = Customer::where('user_id', '=', $user->id)->with(['address'])->first();

            $client = new Client();
            $card = $client->request('POST', 'https://api.pagar.me/core/v5/customers/' . $customer->customer_id . '/cards/', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic c2tfdGVzdF9hZHZNUVlQQzdVWUw3ejJiOg=='
                ],
                'json' => [
                    "number" =>  $data['number'],
                    "holder_document" =>  $data['holder_document'],
                    "exp_month" =>  $data['exp_month'],
                    "exp_year" =>  $data['exp_year'],
                    "cvv" =>  $data['cvv'],
                    "brand" => $data['brand'],
                    "holder_name" => $data['holder_name'],
                    "billing_address_id" => $customer['address'][0]->address_id
                ],
                "http_errors" => false
            ]);
            $card = json_decode($card->getBody());

            $newCard = new Card();
            $newCard->fill(['customer_id' => $customer->id, 'card_id' => $card->id])->save();

            return response()->json(['status' => true, 'card' => $card]);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'error' => $ex]);
        }
    }

    //Function to get a card
    public function update_card(CardRequest $request, $id)
    {
        $data = $request->validated();
        try {
            $user = auth()->user();
            $customer = Customer::where('user_id', '=', $user->id)->first();

            $client = new Client();
            $card = $client->request('PUT', 'https://api.pagar.me/core/v5/customers/' . $customer->customer_id . '/cards/' . $id, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Basic c2tfdGVzdF9hZHZNUVlQQzdVWUw3ejJiOg=='
                ],
                'json' => [
                    "holder_document" => $data['holder_document'],
                    "exp_month" => $data['exp_month'],
                    "exp_year" => $data['exp_year'],
                    "holder_name" => $data['holder_name'],
                    "billing_address_id" => $customer['address'][0]->address_id
                ],
                "http_errors" => false
            ]);
            $card = json_decode($card->getBody());

            return response()->json(['status' => true, 'card' => $card]);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'error' => $ex]);
        }
    }

    //Function to delete a card
    public function delete_card($id)
    {
        try {
            $user = auth()->user();
            $customer = Customer::where('user_id', '=', $user->id)->first();

            $client = new Client();
            $card = $client->request('DELETE', 'https://api.pagar.me/core/v5/customers/' . $customer->customer_id . '/cards/' . $id, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Basic c2tfdGVzdF9hZHZNUVlQQzdVWUw3ejJiOg=='
                ],
            ]);
            $card = json_decode($card->getBody());

            return response()->json(['status' => true]);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'error' => $ex]);
        }
    }
}
