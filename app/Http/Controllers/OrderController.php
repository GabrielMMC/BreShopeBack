<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Breshop;
use App\Models\Card;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules\RequiredIf;

class OrderController extends Controller
{
    //Main function that decides which method of payment will be made
    public function store_order(OrderRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();
        $user = User::where('id', '=', $user->id)->with(['customer'])->first();

        switch ($request['paymant_method']) {
            case 'credit_card':
                $order = $this->credit_card($data, $user);
                break;
            case 'boleto':
                $order = $this->boleto($data, $user);
                break;
            case 'pix':
                $order = $this->pix($data, $user);
                break;
            default:
                return [];
                break;
        }

        return response()->json(['status' => true, 'order' => $order]);
        // $breshop = Breshop::where('id', '=', $id)->with(['recipient'])->first();
    }

    //Credit card order generation function
    public function credit_card($data, $user)
    {
        //Assembly verification of the object to be used in the request according to the data sent from the front
        if ($data['card_id']) {
            $card = Card::where('card_id', '=', $data['card_id'])->first();

            $credit_card = [
                "credit_card" => [
                    "operation_type" => "auth_and_capture",
                    "installments" => $data['installments'],
                    "card_id" => $card->card_id,
                ]
            ];
        } else {
            $credit_card = [
                "credit_card" => [
                    "card" => [
                        "billing_address_id" => $data['address_id'],
                        "number" => $data['number'],
                        "holder_name" => $data['holder_name'],
                        "holder_document" => $data['holder_document'],
                        "exp_month" => $data['exp_month'],
                        "exp_year" => $data['exp_year'],
                        "cvv" => $data['cvv']
                    ],
                    "operation_type" => "auth_and_capture",
                    "installments" => $data['installments'],
                ]
            ];
        }

        //Object assembly of course items previously added to the cart
        $list_items = array();
        foreach ($data['items'] as $item) {
            $list_items = [
                ...$list_items,
                [
                    "amount" => $item['amount'],
                    "description" => $item['description'],
                    "quantity" => $item['quantity'],
                    "code" => $item['id']
                ]
            ];
        }

        //Order request with the previous objects passed with spread
        $client = new Client();
        $order = $client->request('POST', 'https://api.pagar.me/core/v5/orders/', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic c2tfdGVzdF9hZHZNUVlQQzdVWUw3ejJiOg=='
            ],
            'json' => [
                "items" => [
                    ...$list_items,
                ],
                // "shipping" => [
                //     "address" => [
                //         "zip_code" => $data['zip_code'],
                //         "city" => $data['city'],
                //         "state" => $data['state'],
                //         "country" => $data['country'],
                //         "line_1" => $data['number'] . ', ' . $data['street'] . ', ' . $data['neighborhood'],
                //         "line_2" => empty($data['complement']) && $data['complement']
                //     ],
                //     "amount" => $data['shipping_amount'],
                //     "description" => "teste",
                //     "recipient_name" => "Adilson",
                //     "recipient_phone" => "17996664559"
                // ],
                "payments" => [
                    [
                        ...$credit_card,
                        "split" => [
                            [
                                "options" => [
                                    "liable" => true,
                                    "charge_remainder_fee" => true,
                                    "charge_processing_fee" => true
                                ],
                                "amount" => "100",
                                "type" => "percentage",
                                "recipient_id" => "rp_R1mv9M4SmSalyAEW"
                            ]
                        ],
                        "payment_method" => "credit_card"
                    ]
                ],
                "customer_id" => $user['customer']->customer_id,
                "closed" => false
            ],
            "http_errors" => false
        ]);
        $order = json_decode($order->getBody());

        //Filling in the data in our bank
        $newOrder = new Order();
        $newOrder->fill(['user_id' => $user->id, 'order_id' => $order->id])->save();

        return response()->json(['status' => true, 'order' => $order]);
    }

    //Function to list the orders of logged user
    public function list_orders(Request $request)
    {
        $user = auth()->user();
        $customer = Customer::where('user_id', '=', $user->id)->first();

        $client = new Client();
        $orders = $client->request('GET', 'https://api.pagar.me/core/v5/orders?customer_id=' . $customer->customer_id . '&page=' . $request->page . '&size=10', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic c2tfdGVzdF9hZHZNUVlQQzdVWUw3ejJiOg=='
            ],
            "http_errors" => false
        ]);
        $orders = json_decode($orders->getBody());

        return response()->json(['status' => true, 'orders' => $orders]);
    }

    //Function to list all orders of the system
    public function list_all_orders(Request $request)
    {
        $client = new Client();
        $orders = $client->request('GET', 'https://api.pagar.me/core/v5/orders' . '?page=' . $request->page . '&size=10', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic c2tfdGVzdF9hZHZNUVlQQzdVWUw3ejJiOg=='
            ],
            "http_errors" => false
        ]);
        $orders = json_decode($orders->getBody());

        return response()->json(['status' => true, 'orders' => $orders]);
    }
}
