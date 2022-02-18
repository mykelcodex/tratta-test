<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IndexController extends Controller
{
	//Homepage
	public function index(){
		return view('index');
	}

	//Filter and fetch data
	public function filter(Request $request){
		$response = Http::withHeaders(['Authorization' => 'Basic '.getenv('TWILIO_KEY')])
			->get('https://api.twilio.com/2010-04-01/Accounts/'.$request->accountSid.'/Usage/Records.json',[
				'StartDate'=>$request->StartDate,
				'EndDate'=>$request->EndDate,
			]);
		
		if($response->failed()){
			return response()->json($response->json(), $response->status());
		}

		return response()->json($response->json());

	}

}
