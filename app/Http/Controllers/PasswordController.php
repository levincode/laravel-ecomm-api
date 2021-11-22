<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function forgot(Request $request){
        $email = $request->input('email');
        $token = Str::random(12);

        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('reset', ['token' => $token], function(Message $message) use($email){
            $message->subject('Reset your password!');
            $message->to($email);
        });

        return [
            'message' => 'Check your email!'
        ];
    }

    public function reset(Request $request){
        if($request->input('password') !== $request->input('password_confirm')){
            return response([
                'error' => 'passwords do not match!'
            ], 400);
        }

        $passwordResets = DB::table('password_resets')->where('token', $request->input('token'))->first();

        $user = User::where('email', $passwordResets->email)->first();

        if(!$user){
            return response([
                'error' => 'user not found!'
            ], 404);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return [
            'message' => 'success!'
        ];
    }
}
