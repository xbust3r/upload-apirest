<?php

namespace App\Http\Middleware;

use Closure;
use App\UploadsRequests;

class FloodProtect
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */

    private $requests_by_minute = 9;

    public function handle($request, Closure $next)
    {
        $auth_id = $request->get('auth.id');
        $ip = $request->ip();

        $r = UploadsRequests::where('auth_token_id', $auth_id)
            ->where('ip', $ip)
            ->WhereTime("created_at",">=",  date('H:i').":00")
            ->WhereTime("created_at","<=",  date('H:i').":59")
            ->get();


        if ($r->count() >= $this->requests_by_minute) {
            #$this->Response(false, 500, "Your credentials exceeded maximum limit for requests");
            return response()->json([
                'success' => false,
                'message' => "Your credentials exceeded maximum limit for requests, Total Requests:".$r->count()
            ],500);
        }

        $insert = UploadsRequests::create([
            "ip" => $ip,
            "auth_token_id" => $auth_id
        ]);
        return $next($request);
    }
}
