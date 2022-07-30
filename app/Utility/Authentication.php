<?php

namespace App\Utility;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class Authentication
{

    public function login($request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required',
        ]); //validate request

        if ($validator->fails()) {
            $response = [
                'errors' => $validator->errors()->all(),
                'code' => 422
            ];

            return $response; //return error validator error
        }

        $user = array('email' => $request->get('email'), 'password' => $request->get('password'));

        if (!Auth::attempt($user)) {
            $errors = [
                'errors' => ['invalid login credentials'],
                'code' => 400
            ];
            return $errors;
        }

        if (Auth::user()->status == User::NOT_ACTIVE) { // this checks if the user is active or not based on a column key in the user model and migration
            $errors = [
                'error' => ['Login Sequence Blocked, Please talk to our support'],
                'code' => 400
            ];

            return $errors;
        }

        $user_token = User::where('email', $request->get('email'))->first();

        $access_token = $user_token->createToken('user token')->plainTextToken;

        $response = [
            'user' => User::where('email', $request->get('email'))->first(),
            'token' => $access_token,
            'success' => 'Login Attempt Successful',
            'code' => 200
        ];

        return $response;
    }



    public function register($request, $send_otp = false)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|confirmed|min:8',
            'name' => 'required|string',
        ]); //validate request

        if ($validator->fails()) {
            $response = [
                'errors' => $validator->errors()->all(),
                'code' => 422
            ];

            return $response;
        }

        if ($send_otp) {
            $email_otp = rand(10000, 99999); //otp generation
            $this->generate_otp($request->get('email'), $email_otp);
        }

        $user =  new User;
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));
        $user->save();

        $response = [
            'success' => 'Registration Successful',
            'code' => 200
        ];

        return $response;

    }


    public function generate_otp($email, $email_otp)
    {
        // write a code to send email or a call a class function here to send the email to the user
    }
}

