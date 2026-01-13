<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Addresse;

class AddressController extends Controller
{
    // GET /api/addresses
    public function index(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Addresse::where('user_id', $request->user()->id)->get()
        ]);
    }

    // // POST /api/addresses
    // public function store(Request $request)
    // {
    //     $data = $request->validate([
    //         'country'   => 'required|string',
    //         'city'      => 'required|string',
    //         'street'    => 'required|string',
    //         'post_code' => 'required|string',
    //         'phone'     => 'required|string',
    //     ]);

    //     $data['user_id'] = $request->user()->id;

    //     $address = Addresse::create($data);

    //     return response()->json([
    //         'success' => true,
    //         'data' => $address
    //     ], 201);
    // }


      public function store(Request $request)
    {
        $request->validate([
            'country'   => 'required|string',
            'city'      => 'required|string',
            'street'    => 'required|string',
            'post_code' => 'required|string',
            'phone'     => 'required|string',
        ]);

        $address = Addresse::create([
            'user_id'   => auth()->id(),
            'country'   => $request->country,
            'city'      => $request->city,
            'street'    => $request->street,
            'post_code' => $request->post_code,
            'phone'     => $request->phone,
        ]);

        return response()->json([
            'success' => true,
            'data' => $address
        ], 201);
    }
}
