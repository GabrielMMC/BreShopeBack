<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAddressRequest;
use App\Models\UserAddress;
use App\Models\UserData;
use Exception;
use Illuminate\Http\Request;

class UserAddressController extends Controller
{
    public function store_user_address(UserAddressRequest $request)
    {
        try {
            $data = $request->validated();
            $user = auth()->user();

            $userAddress = new UserAddress();
            $userAddress->fill(['user_id' => $user->id])->fill($data)->save();

            return response()->json(['status' => true]);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'error' => $ex]);
        }
    }

    public function get_user_address()
    {
        try {
            $user = auth()->user();
            $userAddress = UserAddress::where('user_id', '=', $user->id)->first();

            return response()->json(['status' => true, 'user' => $userAddress]);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'error' => $ex]);
        }
    }

    public function update_user_address(UserAddressRequest $request)
    {
        try {
            $data = $request->validated();

            $userAddress = UserAddress::where('id', '=', $data['id'])->first();
            $userAddress->fill($data)->save();

            return response()->json(['status' => true]);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'error' => $ex]);
        }
    }
}
