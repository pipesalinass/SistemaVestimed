<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\ValidHCaptcha;
use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session as FacadesSession;
use Illuminate\Support\Facades\Validator;
use Session;

use App\Rules\Recaptcha;

use Laravel\Fortify\Contracts\CreatesNewUsers;


class LoginController extends Controller
{   
    protected function authenticate(Request $request)
    {

        Validator::make($request, [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required'],
            'recaptcha_token' => ['required', new Recaptcha($request['recaptcha_token'])],
        ])->validate();


      
    }
}
