<?php

namespace App\Http\Controllers;

use App\Models\Breshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function store_order(Request $request)
    {
        $user = auth()->user();
        $breshop = Breshop::where('user_id', '=', $user->id)->first();

        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic c2tfdGVzdF9hZHZNUVlQQzdVWUw3ejJiOg=='
        ])->post(
            'https://api.pagar.me/core/v5/orders',
            [
                "items" => [
                    [
                        "amount" => 40,
                        "description" => "teste",
                        "quantity" => 1,
                        "code" => "123456789"
                    ]
                ],
                "shipping" => [
                    "address" => [
                        "country" => "BR",
                        "state" => "SP",
                        "city" => "Jales",
                        "zip_code" => "15706086",
                        "line_1" => "2913, Avenida São Lucas, Residencial São Lucas"
                    ],
                    "amount" => 11,
                    "description" => "teste",
                    "recipient_name" => "Adilson",
                    "recipient_phone" => "17996664559"
                ],
                "payments" => [
                    [
                        "boleto" => [
                            "bank" => "001",
                            "instructions" => "teste",
                            "nosso_numero" => "123456789",
                            "type" => "BDP",
                            "document_number" => "123456789"
                        ],
                        "split" => [
                            [
                                "options" => [
                                    "charge_processing_fee" => true,
                                    "charge_remainder_fee" => true,
                                    "liable" => true
                                ],
                                "amount" => "97",
                                "recipient_id" => "rp_XdWkPlKS7SOjY0Zb",
                                "type" => "percentage"
                            ],
                            [
                                "options" => [
                                    "charge_processing_fee" => true,
                                    "charge_remainder_fee" => true,
                                    "liable" => true
                                ],
                                "amount" => "3",
                                "recipient_id" => "rp_XdWkPlKS7SOjY0Zb",
                                "type" => "percentage"
                            ]
                        ],
                        "payment_method" => "boleto"
                    ]
                ],
                "code" => "123456789",
                "customer_id" => "cus_mJdN9XmfofgwpOyK",
                "closed" => false
            ]
        );
    }
}
