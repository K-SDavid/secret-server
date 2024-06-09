<?php

namespace App\Http\Controllers;

use App\Models\Secret;
use App\Services\ResponseFormatter\JsonResponseFormatter;
use App\Services\ResponseFormatter\XmlResponseFormatter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class SecretController extends Controller
{
    //Check the request's header's Accept parameter,
    //and decide on the response format
    private function getResponseFormat(Request $request)
    {
        $acceptHeader = $request->header('Accept');

        switch ($acceptHeader) {
            case 'application/xml':
                return new XmlResponseFormatter();
            case 'application/json':
            default:
                return new JsonResponseFormatter();
        }
    }

    public function store(Request $request) {
        //Determining the response format (currently xml or json)
        $responseFormat = $this->getResponseFormat($request);

        //Validation of the parameters
        $rules = [
            'secret' => 'required|String',
            'expireAfterViews' => 'required|numeric',
            'expireAfter' => 'required|numeric',
        ];
        $validator = \Validator::make($request->all(), $rules);
        if($validator->fails()){
            return $responseFormat->format($validator->errors()
                ->toArray(), 400);
        }

        //Casting
        $data = [
            'secret' => $request['secret'],
            'expireAfterViews' => (int)$request['expireAfterViews'],
            'expireAfter' => (int)$request['expireAfter'],
        ];

        //Current time + the 'expireAfter' minutes => the expiry date
        $expiresAt = Carbon::now('Europe/Budapest')
            ->addMinutes($data['expireAfter'])->format('Y-m-d H:i:s.v');
        //New variable to collect the neccessary fields for
        //the object creation/DB insertion
        $fields = [
            'hash' => bin2hex(random_bytes(16)),
            'secretText' => $request['secret'],
            'expiresAt' => $expiresAt,
            'remainingViews' => $request['expireAfterViews'],
        ];
        //Object creation and DB insertion,
        //furthermore the response message and status code
        Secret::create($fields);
        return $responseFormat->format(
                ['message' => 'Successfully added a secret!'], 201);
    }

    public function details(Secret $secret, Request $request) {
        //Determining the response format (currently xml or json)
        $responseFormat = $this->getResponseFormat($request);
        
        //Checking if the current secret is viewable
        //(views still remaining and it is not past the expiry date)
        $remainingViews = $secret->remainingViews;
        if($remainingViews <= 0){
            return $responseFormat->format(
                    ['error' => 'The chosen secret can not be viewed anymore!'], 403);
        }

        $expiresAt = Carbon::parse($secret->expiresAt)->format('Y-m-d H:i:s.v');
        if($expiresAt <= Carbon::now('Europe/Budapest')) {
            return $responseFormat->format(
                ['error' => 'The chosen secret has expired!'], 403);
        }
        
        //Decreasing the reamining views by 1
        $secret->update(['remainingViews' => $remainingViews-1]);
        //Returning the secret either as an xml or
        //a json response with the '200' status code
        return $responseFormat->format($secret->toArray(), 200);
    }
}
