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

        return $request->adress['cep'];

        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . '95C1D59C6BCD4F829A9805FC4882510A'
        ])->post('https://sandbox.api.pagseguro.com/orders', [
            "reference_id" => "ex-00001",
            "customer" => [
                "name" => $user->name,
                "email" => $user->email,
                "tax_id" => "12345678909",
                "phones" => [
                    [
                        "country" => $user->country,
                        "area" => $user->area,
                        "number" => $user->number,
                        "type" => "MOBILE"
                    ]
                ]
            ],
            "items" => [
                [
                    "reference_id" => $request->product['id'],
                    "name" => $request->product['name'],
                    "quantity" => 1,
                    "unit_amount" => $request->product['price']
                ]
            ],
            "qr_code" => [
                "amount" => [
                    "value" => 500
                ]
            ],
            "shipping" => [
                "address" => [
                    "street" => $request->adress['street'],
                    "number" => $request->adress['number'],
                    "complement" => "apto 12",
                    "locality" => "Pinheiros",
                    "city" => $request->adress['city'],
                    "region_code" => $request->adress['state'],
                    "country" => "BRA",
                    "postal_code" => $request->adress['cep']
                ]
            ],
            "notification_urls" => [
                "https=>//meusite.com/notificacoes"
            ],
            "charges" => [
                [
                    "reference_id" => "referencia do pagamento",
                    "description" => "descricao do pagamento",
                    "amount" => [
                        "value" => 500,
                        "currency" => "BRL"
                    ],
                    "payment_method" => [
                        "type" => "CREDIT_CARD",
                        "installments" => 1,
                        "capture" => true,
                        "card" => [
                            "number" => $request->paymant['number'],
                            "exp_month" => "12",
                            "exp_year" => "2026",
                            "security_code" =>  $request->paymant['cvv'],
                            "holder" => [
                                "name" =>  $request->paymant['name']
                            ],
                            "store" => false
                        ]
                    ],
                    "notification_urls" => [
                        "https=>//meusite.com/notificacoes"
                    ]
                ]
            ]
        ]);
    }
}
