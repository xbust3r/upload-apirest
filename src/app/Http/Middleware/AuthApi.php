<?php

namespace App\Http\Middleware;

use App\AuthTokens;
use Closure;

class AuthApi
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */

    private $_service_status = null;
    private $_status_code = null;

    private function Response($status, $status_code = 200, $message = null)
    {
        $return = [
            'success' => $status,
            'message' => $message
        ];
        $this->_status_code = $status_code;
        $this->_service_status = $return;

    }

    public function handle($request, Closure $next)
    {
        $head = $request->headers->all();

        if (!isset($head['token-auth']) || !isset($head['token-pass'])) {
            $this->Response(false, 500, "Your Credentials are empty");
            return response()->json($this->_service_status, $this->_status_code);
        } else {
            #$model_token=new AuthTokens();
            $tokens = AuthTokens::where('token_user', $head['token-auth'])
                ->where('token_pass', $head['token-pass'])
                ->first();
            if (!$tokens) {
                $this->Response(false, 500, "Your Credentials are invalid");
                return response()->json($this->_service_status, $this->_status_code);
            }
        }
        #$request->session()->put('auth.id', $tokens->id);
        $request->attributes->add(['auth.id' => $tokens->id]);
        return $next($request);
    }
}
