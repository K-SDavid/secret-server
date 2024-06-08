<?php

namespace App\Http\Controllers;

use App\Models\Secret;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class SecretController extends Controller
{
    public function index() {}

    public function store(Request $request) {
        //Validation of the parameters
        $rules = [
            'secret' => 'required|String',
            'expireAfterViews' => 'required|numeric',
            'expireAfter' => 'required|numeric',
        ];
        $validator = \Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        //Casting
        $data = [
            'secret' => $request['secret'],
            'expireAfterViews' => (int)$request['expireAfterViews'],
            'expireAfter' => (int)$request['expireAfter'],
        ];

        //Current time + the 'expireAfter' parameter in the 'Y-m-d\TH:i:s.v\Z' format
        $expiresAt = Carbon::now()->addMinutes($data['expireAfter'])->format('Y-m-d\TH:i:s.v\Z');
        //New variable to collect the neccessary fields for the object creation/DB insertion
        $fields = [
            'hash' => bin2hex(random_bytes(16)),
            'secretText' => $request['secret'],
            'expiresAt' => $expiresAt,
            'remainingViews' => $request['expireAfterViews'],
        ];
        //Object creation and DB insertion
        Secret::create($fields);
        return response()->json('Successfully added a secret!', 200);
    }
}
