<?php

namespace App\Http\Controllers;

use App\Http\Requests\BreshopRequest;
use App\Models\Breshop;
use Exception;
use Illuminate\Http\Request;

class BreshopController extends Controller
{
    public function get_breshop()
    {
        $user = auth()->user();
        $breshop = Breshop::where('user_id', '=', $user->id)->first();
        if ($breshop == null) {
            return response()->json([
                'status' => false,
            ]);
        } else {
            return response()->json([
                'status' => true,
                'breshop' => $breshop,
            ]);
        }
    }

    public function store_breshop(BreshopRequest $request)
    {
        // try {
        // dd($request);
        $data = $request->validated();
        $user = auth()->user();

        $breshop = new Breshop();
        if ($request->file('file')) {
            $img = $request->file('file');
            $name = uniqid('banner_') . '.' . $img->getClientOriginalExtension();
            $breshop->file = $img->storeAs('photos', $name, ['disk' => 'public']);
        }
        $breshop->active = true;
        $breshop->fill(['user_id' => $user->id])->fill($data)->save();

        return response()->json([
            'status' => true,
        ]);
        // } catch (Exception $ex) {
        //     return response()->json([
        //         'status' => false,
        //         'error' => $ex,
        //     ]);
        // }
    }
}
