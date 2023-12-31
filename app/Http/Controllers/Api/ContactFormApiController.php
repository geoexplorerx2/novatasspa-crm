<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ContactForm;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class ContactFormApiController extends Controller
{
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name_surname' => 'required',
            'phone' => 'required',
            'country' => 'required',
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            if(intval(ContactForm::where('email',$request->email)->count())==0){
                if (ContactForm::create([
                    'name_surname' => $request->name_surname,
                    'phone' => $request->phone,
                    'country' => $request->country,
                    'email' => $request->email,
                    'form_status_id' => 1,
                    'answered_time' => null,
                ])) {
                    return response()->json([
                        "code" => 200,
                        "data" => "your info recorded successfully",
                    ]);
                } else {
                    return response()->json([
                        "code" => 400,
                        "data" => "operation failed",
                    ]);
                }
            }else{
                return response()->json([
                    "code" => 400,
                    "data" => "This Email Already recorded",
                ]);
            }
        }
    }
}
