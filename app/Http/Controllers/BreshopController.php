<?php

namespace App\Http\Controllers;

use App\Models\Breshop;
use Illuminate\Http\Request;

class BreshopController extends Controller
{
    public function get_breshop($id)
    {
        $breshop = Breshop::where('user_id', '=', $id)->first();
        if ($breshop == null) {
            return response()->json([
                'status' => false,
            ]);
        } else {
            return response()->json([
                'status' => true,
            ]);
        }
    }

    public function store_breshop(Request $request)
    {
        return $request;
    }
}
