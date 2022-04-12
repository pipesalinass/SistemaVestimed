<?php

namespace App\Actions\Fortify;

use App\Rules\Captcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Session;

class CaptchaValidation
{
    public function __invoke(Request $request, $next)
    {
        Validator::make($request->all(), [
            'g-recaptcha-response' => function ($attribute, $value, $fail){
                $secretKey = config('services.recaptcha.secret');
                $response = $value;
                $userIP = $_SERVER['REMOTE_ADDR'];
                $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$response&remoteip=$userIP";
                $response = $this->curl_get_file_contents($url);
                $response = json_decode($response);
                if(!$response->success){
                    Session::flash('g-recaptcha-response','porfavor marcar la recaptcha');
                    Session::flash('alert-class', 'alert-danger');
                    $fail($attribute.' google reCaptcha falló');
                }
            },
        ])->validate();

        return $next($request);
    }

    public function curl_get_file_contents($URL){   
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $URL);
        $contents = curl_exec($c);
        curl_close($c);

        if ($contents) return $contents;
        else return FALSE;
    }

    
}