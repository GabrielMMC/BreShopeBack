<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecipientRequest;
use App\Http\Resources\RecipientResource;
use App\Models\Breshop;
use App\Models\Recipient;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class RecipientController extends Controller
{
    public function store_recipient(RecipientRequest $request)
    {
        $data = $request->validated();

        $user = auth()->user();
        $breshop = Breshop::where('user_id', '=', $user->id)->first();
        $recipient = Recipient::where('breshop_id', '=', $breshop->id)->first();

        if (empty($recipient)) {
            $response = $this->store($user, $data, 'POST', '', $breshop);
        } else {
            $response = $this->store($user, $data, 'PUT', $recipient->recipient_id, $breshop);
        }

        if ($response['error']) {
            return response()->json(['status' => false, 'error' => 'Houve um erro na criação de Recebedor, tente novamente mais tarde']);
        } else {
            return response()->json(['status' => true, 'recipient' => $response]);
        }
    }

    public function get_recipient()
    {
        // try {
        $user = auth()->user();
        $breshop = Breshop::where('user_id', '=', $user->id)->first();
        $recipient = Recipient::where('breshop_id', '=', $breshop->id)->first();

        if (isset($recipient)) {
            $client = new Client();
            $recipientReq = $client->request('GET', 'https://api.pagar.me/core/v5/recipients/' . $recipient->recipient_id, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Basic c2tfdGVzdF9hZHZNUVlQQzdVWUw3ejJiOg=='
                ],
            ]);
            $recipientReq = json_decode($recipientReq->getBody());

            return response()->json(['status' => true, 'recipient' => $recipientReq]);
        } else {
            return response()->json(['status' => false, 'recipient' => $recipient,]);
        }

        // } catch (Exception $ex) {
        // }
    }

    public function store($user, $data, $method, $id, $breshop)
    {
        if (strlen($data['document']) == 11) {
            $type = 'company';
        } else {
            $type = 'individual';
        }

        $client = new Client();
        $recipient = $client->request($method, 'https://api.pagar.me/core/v5/recipients/' . $id, [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic c2tfdGVzdF9hZHZNUVlQQzdVWUw3ejJiOg=='
            ],
            'json' => [
                "default_bank_account" => [
                    "holder_type" => $type,
                    "holder_document" => $data['document'],
                    "type" => "savings",
                    "account_check_digit" => $data['account_check_digit'],
                    "branch_check_digit" => $data['branch_check_digit'],
                    "account_number" => $data['account_number'],
                    "branch_number" => $data['branch_number'],
                    "bank" => $data['bank'],
                    "holder_name" => $data['name']
                ],
                "name" => $data['name'],
                "email" => $user->email,
                "document" => $data['document'],
                "type" => $type,
                // "code" => $user->id
                // "description" => "teste",
            ],
            "http_errors" => false
        ]);
        $recipient = json_decode($recipient->getBody());
        if (isset($recipient->id)) {
            if ($method == 'POST') {
                $newRecipient = new recipient();
                $newRecipient->fill(['recipient_id' => $recipient->id, 'breshop_id' => $breshop->id])->save();
            }

            return ['error' => false, 'recipient' => $recipient];
        } else {
            return ['error' => true, 'recipient' => $recipient];
        }
    }
}
